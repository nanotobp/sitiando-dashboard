<?php

namespace App\Http\Controllers\Controller;

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
            'product_id' => 'required|integer|exists:products,id',
            'qty'        => 'nullable|integer|min:1',
        ]);

        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Iniciá sesión para agregar al carrito.');
        }

        // Buscar (o crear) carrito activo del usuario
        $cart = Cart::firstOrCreate(
            [
                'user_id' => $user->id,
                'status'  => 'active',
            ],
            [
                'total'   => 0,
            ]
        );

        $productId = (int) $request->input('product_id');
        $qty       = (int) $request->input('qty', 1);

        $product = Product::findOrFail($productId);

        // Buscar si ya existe el item en el carrito
        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $item->qty   += $qty;
            $item->total = $item->qty * $item->price;
            $item->save();
        } else {
            $item = CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $product->id,
                'qty'        => $qty,
                'price'      => $product->precio, // campo de tu tabla products
                'total'      => $product->precio * $qty,
            ]);
        }

        // Recalcular total del carrito
        $cart->total = $cart->items()->sum('total');
        $cart->save();

        return back()->with('success', 'Producto agregado al carrito.');
    }

    /**
     * Actualizar cantidad de un ítem del carrito
     */
    public function updateItem(Request $request, string $itemId)
    {
        $request->validate([
            'qty' => 'required|integer|min:0',
        ]);

        $user = $request->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Iniciá sesión.');
        }

        $item = CartItem::with('cart')
            ->where('id', $itemId)
            ->whereHas('cart', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->where('status', 'active');
            })
            ->firstOrFail();

        $qty = (int) $request->input('qty');

        if ($qty === 0) {
            $cart = $item->cart;
            $item->delete();

            $cart->total = $cart->items()->sum('total');
            $cart->save();

            return back()->with('success', 'Producto eliminado del carrito.');
        }

        $item->qty   = $qty;
        $item->total = $item->qty * $item->price;
        $item->save();

        $cart = $item->cart;
        $cart->total = $cart->items()->sum('total');
        $cart->save();

        return back()->with('success', 'Carrito actualizado.');
    }

    /**
     * Eliminar un ítem puntual del carrito
     */
    public function removeItem(Request $request, string $itemId)
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Iniciá sesión.');
        }

        $item = CartItem::with('cart')
            ->where('id', $itemId)
            ->whereHas('cart', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->where('status', 'active');
            })
            ->firstOrFail();

        $cart = $item->cart;

        $item->delete();

        $cart->total = $cart->items()->sum('total');
        $cart->save();

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    /**
     * Vaciar por completo el carrito
     */
    public function clear(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Iniciá sesión.');
        }

        $cart = Cart::where('user_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if ($cart) {
            $cart->items()->delete();
            $cart->total = 0;
            $cart->save();
        }

        return back()->with('success', 'Carrito vaciado.');
    }
}
