<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartActivityLog;

class CartController extends Controller
{
    /**
     * Listado general
     */
    public function index()
    {
        $active = Cart::where('status', 'active')
            ->with('user')
            ->orderBy('updated_at', 'DESC')
            ->paginate(20);

        $abandoned = Cart::where('status', 'abandoned')
            ->with('user')
            ->orderBy('updated_at', 'DESC')
            ->paginate(20);

        $abandonedValue = Cart::where('status', 'abandoned')->sum('total');

        return view('admin.carts.index', compact('active', 'abandoned', 'abandonedValue'));
    }

    /**
     * Ficha de un carrito
     */
    public function show($id)
    {
        $cart = Cart::with(['user', 'items.product'])->findOrFail($id);

        $timeline = CartActivityLog::where('cart_id', $id)
            ->orderBy('created_at', 'ASC')
            ->get();

        return view('admin.carts.show', compact('cart', 'timeline'));
    }
}
