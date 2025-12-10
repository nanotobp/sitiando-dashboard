<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;

class VentasService
{
    /**
     * Listado PRO de ventas con filtros avanzados.
     */
    public function list(Request $req)
    {
        $q = Order::query()
            ->with(['customer', 'latestPayment']) // Relación útil
            ->orderByDesc('created_at');

        // =========================
        // 🔍 BUSCADOR
        // =========================
        if ($search = $req->input('search')) {

            $q->where(function ($x) use ($search) {
                $x->where('id', 'LIKE', "%{$search}%")                      // UUID
                  ->orWhere('order_number', 'LIKE', "%{$search}%")         // Nº orden
                  ->orWhere('customer_email', 'LIKE', "%{$search}%")       // Email
                  ->orWhere('customer_phone', 'LIKE', "%{$search}%");      // Teléfono
            });
        }

        // =========================
        // 🎯 ESTADOS
        // =========================
        if ($status = $req->input('status')) {
            $q->where('status', $status);
        }

        // =========================
        // 🗓 RANGO DE FECHAS
        // =========================
        if ($from = $req->input('date_from')) {
            $q->whereDate('created_at', '>=', $from);
        }

        if ($to = $req->input('date_to')) {
            $q->whereDate('created_at', '<=', $to);
        }

        // =========================
        // 💰 FILTRO POR TOTAL
        // =========================
        if ($min = $req->input('min_total')) {
            $q->where('total', '>=', $min);
        }

        if ($max = $req->input('max_total')) {
            $q->where('total', '<=', $max);
        }

        return $q->paginate(20);
    }

    /**
     * Obtener una venta con TODAS sus relaciones profundas.
     */
    public function find(string $id): Order
    {
        return Order::with([
                'items.product',
                'payments',
                'latestPayment',
                'statusHistory',
                'customer',
            ])
            ->findOrFail($id);
    }
}
