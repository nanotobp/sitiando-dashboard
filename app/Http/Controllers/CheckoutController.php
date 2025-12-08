<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;

class CheckoutController extends Controller
{
    /**
     * Mostrar checkout con datos del carrito
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $cart = Cart::with(['items.product'])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Tu carrito está vacío.');
        }

        return view('checkout.index', compact('cart'));
    }

    /**
     * Procesar checkout y generar orden
     */
    public function process(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|min:3',
            'customer_email' => 'required|email',
        ]);

        $user = $request->user();

        $cart = Cart::with(['items.product'])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->firstOrFail();

        // Crear orden
        $order = Order::create([
            'user_id'        => $user->id,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'total'          => $cart->total,
            'status'         => 'pending',
        ]);

        // Crear items de la orden
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price,
                'subtotal'   => $item->quantity * $item->product->price,
            ]);
        }

        // Registrar pago base
        OrderPayment::create([
            'order_id' => $order->id,
            'amount'   => $order->total,
            'method'   => 'manual',
            'status'   => 'pending'
        ]);

        // Cerrar el carrito
        $cart->update(['status' => 'completed']);

        return redirect()
            ->route('orders.show', $order->id)
            ->with('success', 'Compra realizada con éxito.');
    }
}
