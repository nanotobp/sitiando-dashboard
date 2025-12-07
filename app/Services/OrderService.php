<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderService
{
    public function list($req)
    {
        $q = Order::query();

        if ($req->search) {
            $q->where(function ($x) use ($req) {
                $x->where('id', $req->search)
                  ->orWhere('customer_email', 'LIKE', "%{$req->search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$req->search}%");
            });
        }

        if ($req->status) $q->where('status', $req->status);
        if ($req->date_from) $q->whereDate('created_at', '>=', $req->date_from);
        if ($req->date_to) $q->whereDate('created_at', '<=', $req->date_to);
        if ($req->min_total) $q->where('total', '>=', $req->min_total);
        if ($req->max_total) $q->where('total', '<=', $req->max_total);

        return $q->latest()->paginate(20);
    }

    public function find($id)
    {
        return Order::with(["items.product", "payments", "statusHistory"])
            ->findOrFail($id);
    }

    public function exportCsv()
    {
        $orders = Order::all();

        $csv = "ID,Cliente,Email,Total,Estado,Fecha\n";

        foreach ($orders as $o) {
            $csv .= "{$o->id},{$o->customer_name},{$o->customer_email},{$o->total},{$o->status},{$o->created_at}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename=orders.csv');
    }

    public function sendEmail($id)
    {
        $order = Order::findOrFail($id);
        // Mail::to($order->customer_email)->send(new OrderMail($order));
        return true;
    }

    public function refreshPayment($id)
    {
        // Aquí irá la integración Bancard REAL
        return true;
    }

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
