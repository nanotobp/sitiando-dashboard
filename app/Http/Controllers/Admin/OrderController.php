<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * LISTADO DE ÓRDENES (Admin / Manager / Seller)
     */
    public function index(Request $request)
    {
        $query = Order::query()
            ->with(['latestPayment'])
            ->orderByDesc('created_at');

        // Filtros opcionales
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * DETALLE DE LA ORDEN
     */
    public function show($id)
    {
        $order = Order::with([
            'items.product',
            'payments',
            'statusHistory',
            'latestPayment'
        ])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * ACTUALIZAR STATUS
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $order = Order::findOrFail($id);

        $old = $order->status;
        $new = $request->status;

        if ($old !== $new) {
            $order->status = $new;
            $order->save();

            // registrar historial
            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'old_status' => $old,
                'new_status' => $new,
                'changed_by' => auth()->id(),
            ]);
        }

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', 'Estado actualizado correctamente.');
    }
}
