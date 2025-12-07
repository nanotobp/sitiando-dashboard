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
            'customer_phone' => 'required|string|min:6',
        ]);

        $user = $request->user();

        $cart = Cart::with('items')->where('user_id', $user->id)->where('status', 'active')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'No hay productos en el carrito.');
        }

        // Crear número de orden único
        $orderNumber = 'ORD-' . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT);

        // Crear orden
        $order = Order::create([
            'user_id'        => $user->id,
            'order_number'   => $orderNumber,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'subtotal'       => $cart->items->sum('total'),
            'discount'       => 0,
            'total'          => $cart->items->sum('total'),
            'status'         => 'pending',
        ]);

        // Crear items de la orden
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product_id,
                'qty'        => $item->qty,
                'price'      => $item->price,
                'total'      => $item->total,
            ]);
        }

        // Crear registro de pago (pendiente)
        OrderPayment::create([
            'order_id'       => $order->id,
            'status'         => 'pending',
            'amount'         => $order->total,
            'payment_method' => 'bancard',
            'transaction_id' => 'TX-' . uniqid(),
        ]);

        // Vaciar carrito
        $cart->items()->delete();
        $cart->update(['total' => 0, 'status' => 'processed']);

        return redirect()->route('checkout.success', $order->id);
    }

    /**
     * Pantalla de éxito
     */
    public function success($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('checkout.success', compact('order'));
    }
}
