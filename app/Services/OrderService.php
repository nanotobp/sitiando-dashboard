<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderService
{
    /**
     * Listado PRO con filtros correctos
     */
    public function list($req)
    {
        $q = Order::query();

        // =============================
        // BUSCADOR GENERAL
        // =============================
        if ($req->search) {
            $search = $req->search;

            $q->where(function ($x) use ($search) {
                $x->where('id', $search)
                  ->orWhere('order_number', 'LIKE', "%{$search}%")
                  ->orWhere('customer_email', 'LIKE', "%{$search}%")
                  ->orWhere('customer_phone', 'LIKE', "%{$search}%")
                  ->orWhere('shipping_address->full_name', 'LIKE', "%{$search}%")
                  ->orWhere('billing_address->full_name', 'LIKE', "%{$search}%");
            });
        }

        // =============================
        // FILTROS
        // =============================
        if ($req->status) {
            $q->where('status', $req->status);
        }

        if ($req->date_from) {
            $q->whereDate('created_at', '>=', $req->date_from);
        }

        if ($req->date_to) {
            $q->whereDate('created_at', '<=', $req->date_to);
        }

        if ($req->min_total) {
            $q->where('total', '>=', $req->min_total);
        }

        if ($req->max_total) {
            $q->where('total', '<=', $req->max_total);
        }

        return $q->latest()->paginate(20);
    }

    /**
     * Detalle completo con relaciones
     */
    public function find($id)
    {
        return Order::with([
                "items.product",
                "payments",
                "statusHistory"
            ])
            ->findOrFail($id);
    }

    /**
     * Exportación CSV corregida con datos reales
     */
    public function exportCsv()
    {
        $orders = Order::all();

        $csv = "ID,Orden,Cliente,Email,Teléfono,Total,Estado,Fecha\n";

        foreach ($orders as $o) {

            $clientName = $o->shipping_address['full_name'] ?? 'N/D';

            $csv .= implode(",", [
                $o->id,
                $o->order_number,
                $clientName,
                $o->customer_email,
                $o->customer_phone,
                $o->total,
                $o->status,
                $o->created_at,
            ]) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename=orders.csv');
    }

    /**
     * Enviar correo al cliente
     */
    public function sendEmail($id)
    {
        $order = Order::findOrFail($id);

        // Mail::to($order->customer_email)->send(new OrderMail($order));

        return true;
    }

    /**
     * Refrescar estado del pago (placeholder Bancard)
     */
    public function refreshPayment($id)
    {
        return true;
    }

    /**
     * Acciones masivas
     */
    public function bulkAction($action, $ids)
    {
        if (!$ids) return;

        $q = Order::whereIn('id', $ids);

        if ($action === 'mark_paid') {
            $q->update(['status' => 'paid']);
        }

        if ($action === 'delete') {
            $q->delete();
        }
    }
}
