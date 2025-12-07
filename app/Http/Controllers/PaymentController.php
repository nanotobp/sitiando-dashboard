<?php

namespace App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Redirige al usuario al checkout de Bancard
     */
    public function pay($orderId, PaymentService $bancard)
    {
        $order = Order::findOrFail($orderId);

        $checkout = $bancard->generateCheckout($order);

        return view('payment.redirect', [
            'formUrl' => $checkout['form_url'],
            'payload' => $checkout['payload'],
        ]);
    }

    /**
     * Retorno del cliente luego del pago
     */
    public function paymentReturn(Request $request)
    {
        $orderId = $request->shop_process_id;

        $order = Order::find($orderId);

        if (!$order) {
            return "Orden no encontrada";
        }

        return view('payment.return', compact('order'));
    }

    /**
     * Notificación POST de Bancard (estado REAL)
     */
    public function notify(Request $request, PaymentService $bancard)
    {
        $orderId = $request->operation['shop_process_id'] ?? null;

        if (!$orderId) {
            return response()->json(['error' => 'shop_process_id missing'], 400);
        }

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['error' => 'order not found'], 404);
        }

        $status = $bancard->checkPaymentStatus($orderId);

        $result = $status['confirmation']['response']['status'] ?? 'failed';

        // Actualizar
        $payment = OrderPayment::where('order_id', $order->id)->first();
        $payment->update(['status' => $result]);

        $order->update([
            'status' => $result === 'success' ? 'paid' : 'failed'
        ]);

        return response()->json(['ok' => true]);
    }

    public function cancel(Request $request)
    {
        return view('payment.cancel');
    }
}
