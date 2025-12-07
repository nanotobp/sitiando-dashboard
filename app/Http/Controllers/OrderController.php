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
            ->paginate(15);

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'payments', 'statusHistory'])
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $order = Order::findOrFail($id);

        DB::transaction(function () use ($order, $request) {
            $order->update([
                'status' => $request->status
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => $request->status,
                'comment' => $request->comment
            ]);
        });

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}
