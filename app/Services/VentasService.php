<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;

class VentasService
{
    /**
     * Devuelve listado paginado de ventas con filtros.
     */
    public function list(Request $req)
    {
        $q = Order::query();

        // Búsqueda por ID, email o nombre de cliente
        if ($search = $req->input('search')) {
            $q->where(function ($x) use ($search) {
                $x->where('id', $search)
                  ->orWhere('customer_email', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($status = $req->input('status')) {
            $q->where('status', $status);
        }

        // Rango de fechas
        if ($from = $req->input('date_from')) {
            $q->whereDate('created_at', '>=', $from);
        }

        if ($to = $req->input('date_to')) {
            $q->whereDate('created_at', '<=', $to);
        }

        // Ordenado por más reciente
        return $q->latest()->paginate(20);
    }

    /**
     * Devuelve una sola venta con todas sus relaciones.
     */
    public function find(int $id): Order
    {
        return Order::with([
                'items.product',
                'payments',
                'statusHistory',
            ])
            ->findOrFail($id);
    }
}
