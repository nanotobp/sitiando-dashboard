<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Mostrar el carrito actual del usuario
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            // Si querés forzar login para carrito
            return redirect()->route('login')->with('error', 'Iniciá sesión para ver tu carrito.');
        }

        $cart = Cart::with(['items.product'])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        return view('cart.index', [
            'cart' => $cart,
        ]);
    }

    /**
     * Agregar producto al carrito
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1'
        ]);

        $user = $request->user();

        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'active'],
            ['total' => 0]
        );

        $item = CartItem::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $request->product_id
            ],
            [
                'quantity' => \DB::raw("quantity + {$request->qty}")
            ]
        );

        return redirect()->route('cart.index')->with('success', 'Producto agregado al carrito.');
    }
}
