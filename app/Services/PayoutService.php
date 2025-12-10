<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use Illuminate\Support\Facades\DB;

class PayoutService
{
    /**
     * Genera un payout mensual (pendiente de pago)
     */
    public function generatePayout(Affiliate $affiliate): AffiliatePayout
    {
        return DB::transaction(function () use ($affiliate) {

            // Obtener comisiones aprobadas aún no pagadas
            $commissions = AffiliateCommission::where('affiliate_id', $affiliate->id)
                ->where('status', 'approved')
                ->whereNull('paid_at')
                ->get();

            if ($commissions->isEmpty()) {
                throw new \Exception("El afiliado no tiene comisiones pendientes de pago.");
            }

            $totalAmount = $commissions->sum('commission_amount');
            $feeAmount   = 0; // si luego tenés reglas, acá se aplican
            $netAmount   = $totalAmount - $feeAmount;

            // Crear payout
            $payout = AffiliatePayout::create([
                'affiliate_id'   => $affiliate->id,
                'commission_ids' => $commissions->pluck('id')->toArray(),
                'total_amount'   => $totalAmount,
                'fee_amount'     => $feeAmount,
                'net_amount'     => $netAmount,
                'status'         => 'pending',
                'period_start'   => now()->startOfMonth(),
                'period_end'     => now()->endOfMonth(),
                'metadata'       => [],
            ]);

            return $payout;
        });
    }

    /**
     * Marca payout como pagado y actualiza comisiones relacionadas.
     */
    public function markAsPaid(AffiliatePayout $payout, string $paymentReference = null)
    {
        return DB::transaction(function () use ($payout, $paymentReference) {

            $payout->update([
                'status'            => 'paid',
                'paid_at'           => now(),
                'payment_reference' => $paymentReference,
                'processed_by'      => auth()->id(),
            ]);

            // Actualizar comisiones
            AffiliateCommission::whereIn('id', $payout->commission_ids)
                ->update([
                    'status'      => 'paid',
                    'paid_at'     => now(),
                    'updated_at'  => now(),
                ]);

            return $payout;
        });
    }

    /**
     * Recalcular payout si hubo errores (opcional)
     */
    public function recalc(AffiliatePayout $payout)
    {
        $commissions = AffiliateCommission::whereIn('id', $payout->commission_ids)->get();

        $total  = $commissions->sum('commission_amount');
        $fee    = $payout->fee_amount ?? 0;
        $net    = $total - $fee;

        $payout->update([
            'total_amount' => $total,
            'net_amount'   => $net,
        ]);

        return $payout;
    }
}
