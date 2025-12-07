<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use Illuminate\Http\Request;
use App\Mail\AffiliatePayoutReady;
use Illuminate\Support\Facades\Mail;

class AffiliatePayoutController extends Controller
{
    /**
     * Listado de liquidaciones
     */
    public function index()
    {
        $payouts = AffiliatePayout::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.payouts.index', compact('payouts'));
    }

    /**
     * Crear una liquidación nueva (pantalla)
     */
    public function create()
    {
        $affiliates = Affiliate::where('is_active', true)->get();
        return view('admin.payouts.create', compact('affiliates'));
    }

    /**
     * Guardar nueva liquidación
     */
    public function store(Request $request)
    {
        $request->validate([
            'affiliate_id' => 'required|exists:affiliates,id',
            'period_start' => 'required|date',
            'period_end'   => 'required|date|after_or_equal:period_start',
        ]);

        $commissions = AffiliateCommission::where('affiliate_id', $request->affiliate_id)
            ->where('status', 'approved')
            ->whereBetween('created_at', [$request->period_start, $request->period_end])
            ->get();

        if ($commissions->isEmpty()) {
            return back()->with('error', 'No hay comisiones aprobadas en este período.');
        }

        $totalAmount = $commissions->sum('commission_amount');

        $payout = AffiliatePayout::create([
            'affiliate_id'    => $request->affiliate_id,
            'period_start'    => $request->period_start,
            'period_end'      => $request->period_end,
            'commission_ids'  => $commissions->pluck('id'),
            'total_amount'    => $totalAmount,
            'fee_amount'      => 0,
            'net_amount'      => $totalAmount,
            'status'          => 'pending',
        ]);

        return redirect()
            ->route('admin.payouts.show', $payout->id)
            ->with('success', 'Payout generado correctamente.');
    }

    /**
     * Ver detalle
     */
    public function show($id)
    {
        $payout = AffiliatePayout::with('affiliate')->findOrFail($id);
        $commissions = AffiliateCommission::whereIn('id', $payout->commission_ids)->get();

        return view('admin.payouts.show', compact('payout', 'commissions'));
    }

    /**
     * Actualizar estado
     */
    public function updateStatus(Request $request, $id)
    {
        $payout = AffiliatePayout::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,paid,failed,cancelled',
        ]);

        $payout->update([
            'status'             => $request->status,
            'payment_reference'  => $request->payment_reference,
            'notes'              => $request->notes,
        ]);

        // Notificar SOLO cuando se marca como "paid" o "processing"
        if (in_array($request->status, ['processing', 'paid'])) {
            try {
                Mail::to($payout->affiliate->email)
                    ->send(new AffiliatePayoutReady($payout));
            } catch (\Exception $e) {
                \Log::error("Email payout error: " . $e->getMessage());
            }
        }

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    /**
     * Subir comprobante
     */
    public function uploadProof(Request $request, $id)
    {
        $payout = AffiliatePayout::findOrFail($id);

        $request->validate([
            'proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096'
        ]);

        $path = $request->file('proof')->store('payouts/proofs', 'public');

        $payout->update([
            'payment_proof_url' => $path,
        ]);

        return back()->with('success', 'Comprobante subido correctamente.');
    }

    /**
     * Exportar payout a CSV
     */
    public function exportCsv($id)
    {
        $payout = AffiliatePayout::with('affiliate')->findOrFail($id);

        $filename = "payout_{$id}.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate",
            "Expires"             => "0"
        ];

        $columns = [
            'Payout ID',
            'Afiliado',
            'Periodo Inicio',
            'Periodo Fin',
            'Total',
            'Neto',
            'Estado',
            'Fecha Creado',
        ];

        $callback = function() use ($payout, $columns) {
            $file = fopen('php://output', 'w');

            fputcsv($file, $columns);

            fputcsv($file, [
                $payout->id,
                $payout->affiliate?->full_name,
                $payout->period_start,
                $payout->period_end,
                $payout->total_amount,
                $payout->net_amount,
                $payout->status,
                $payout->created_at,
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar comisiones CSV
     */
    public function exportCommissionsCsv($id)
    {
        $payout = AffiliatePayout::findOrFail($id);

        $commissions = AffiliateCommission::whereIn('id', $payout->commission_ids)
            ->with('affiliate')
            ->get();

        $filename = "payout_{$id}_commissions.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate",
            "Expires"             => "0"
        ];

        $columns = [
            'Commission ID',
            'Order ID',
            'Base',
            'Porcentaje',
            'Comisión',
            'Tipo',
            'Fecha',
        ];

        $callback = function() use ($commissions, $columns) {
            $file = fopen('php://output', 'w');

            fputcsv($file, $columns);

            foreach ($commissions as $c) {
                fputcsv($file, [
                    $c->id,
                    $c->order_id,
                    $c->commission_base,
                    $c->commission_rate,
                    $c->commission_amount,
                    $c->commission_type,
                    $c->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
