<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query()->with('latestPayment');

        // Filtros básicos
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'ILIKE', '%' . $search . '%')
                    ->orWhere('customer_name', 'ILIKE', '%' . $search . '%')
                    ->orWhere('customer_email', 'ILIKE', '%' . $search . '%');
            });
        }

        $orders = $query
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        // Resumen por estado
        $statusCounts = Order::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.orders.index', [
            'orders'        => $orders,
            'statusCounts'  => $statusCounts,
            'filters'       => [
                'status' => $request->status,
                'from'   => $request->from,
                'to'     => $request->to,
                'search' => $request->search,
            ],
        ]);
    }

    public function show($id)
    {
        $order = Order::with([
                'items',
                'payments' => function ($q) {
                    $q->orderByDesc('created_at');
                },
                'statusHistory' => function ($q) {
                    $q->orderByDesc('created_at');
                },
                'user',
            ])->findOrFail($id);

        // Timeline = statusHistory
        $timeline = $order->statusHistory;

        return view('admin.orders.show', [
            'order'    => $order,
            'timeline' => $timeline,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;

        DB::transaction(function () use ($order, $request, $oldStatus) {
            $order->status = $request->status;
            $order->save();

            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'status'     => $request->status,
                'comment'    => 'Cambio manual desde el panel (de ' . $oldStatus . ' a ' . $request->status . ')',
                'changed_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Estado de la orden actualizado.');
    }

    public function registerPayment(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        DB::transaction(function () use ($order) {
            OrderPayment::create([
                'order_id'         => $order->id,
                'amount'           => $order->total,
                'payment_method'   => 'manual',
                'payment_reference'=> 'MAN-' . now()->format('YmdHis'),
                'status'           => 'confirmed',
                'paid_at'          => now(),
            ]);

            $order->status  = 'paid';
            $order->paid_at = now();
            $order->save();

            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'status'     => 'paid',
                'comment'    => 'Pago manual registrado desde el panel.',
                'changed_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Pago registrado correctamente.');
    }

    public function resendPaymentLink($id)
    {
        $order = Order::findOrFail($id);

        // 🔜 Futuro: acá va integración Bancard (re-generar link + enviar email/SMS)

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Se simuló el reenvío del enlace de pago (placeholder).');
    }
}