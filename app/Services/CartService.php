<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Models\CartActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartService
{
    /**
     * Obtiene o crea el carrito activo del usuario.
     */
    public function getOrCreateCartForUser(User $user): Cart
    {
        return Cart::firstOrCreate(
            [
                'user_id' => $user->id,
                'status'  => 'active',
            ],
            [
                'total' => 0,
            ]
        );
    }

    /**
     * Agregar producto al carrito.
     */
    public function addItem(User $user, string $productId, int $qty = 1): Cart
    {
        return DB::transaction(function () use ($user, $productId, $qty) {

            if ($qty < 1) $qty = 1;

            $cart = $this->getOrCreateCartForUser($user);

            $product = Product::where('activo', true)->findOrFail($productId);

            // Validar stock
            if (!is_null($product->stock) && $product->stock < $qty) {
                throw ValidationException::withMessages([
                    'qty' => 'No hay stock suficiente para este producto.',
                ]);
            }

            // Buscar item existente con lock
            $item = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->lockForUpdate()
                ->first();

            if (!$item) {
                $item = new CartItem();
                $item->cart_id    = $cart->id;
                $item->product_id = $product->id;
                $item->qty        = $qty;
            } else {
                $item->qty += $qty;
            }

            $item->price = $product->precio;
            $item->total = $item->qty * $item->price;
            $item->save();

            $this->recalculateTotals($cart);

            $this->logEvent($cart, 'add_item', [
                'product_id' => $product->id,
                'qty'        => $qty,
            ]);

            return $cart->fresh(['items.product']);
        });
    }

    /**
     * Actualizar cantidad de un item.
     */
    public function updateItemQuantity(User $user, string $itemId, int $qty): Cart
    {
        return DB::transaction(function () use ($user, $itemId, $qty) {

            $cart = $this->getOrCreateCartForUser($user);

            $item = CartItem::where('cart_id', $cart->id)
                ->lockForUpdate()
                ->findOrFail($itemId);

            if ($qty <= 0) {
                $productId = $item->product_id;
                $item->delete();

                $this->recalculateTotals($cart);
                $this->logEvent($cart, 'remove_item', [
                    'product_id' => $productId,
                    'reason'     => 'qty_zero',
                ]);

                return $cart->fresh(['items.product']);
            }

            $product = Product::where('activo', true)->findOrFail($item->product_id);

            if (!is_null($product->stock) && $product->stock < $qty) {
                throw ValidationException::withMessages([
                    'qty' => 'No hay stock suficiente.',
                ]);
            }

            $item->qty   = $qty;
            $item->price = $product->precio;
            $item->total = $qty * $item->price;
            $item->save();

            $this->recalculateTotals($cart);

            $this->logEvent($cart, 'update_qty', [
                'product_id' => $product->id,
                'qty'        => $qty,
            ]);

            return $cart->fresh(['items.product']);
        });
    }

    /**
     * Eliminar un ítem del carrito.
     */
    public function removeItem(User $user, string $itemId): Cart
    {
        return DB::transaction(function () use ($user, $itemId) {

            $cart = $this->getOrCreateCartForUser($user);

            $item = CartItem::where('cart_id', $cart->id)
                ->lockForUpdate()
                ->findOrFail($itemId);

            $productId = $item->product_id;
            $item->delete();

            $this->recalculateTotals($cart);

            $this->logEvent($cart, 'remove_item', [
                'product_id' => $productId,
                'reason'     => 'manual',
            ]);

            return $cart->fresh(['items.product']);
        });
    }

    /**
     * Vaciar el carrito.
     */
    public function clear(User $user): Cart
    {
        return DB::transaction(function () use ($user) {

            $cart = $this->getOrCreateCartForUser($user);

            CartItem::where('cart_id', $cart->id)->delete();

            $this->recalculateTotals($cart);

            $this->logEvent($cart, 'clear_cart');

            return $cart->fresh(['items.product']);
        });
    }

    public function markCompleted(Cart $cart): void
    {
        $cart->update(['status' => 'completed']);
        $this->logEvent($cart, 'completed');
    }

    public function markAbandoned(Cart $cart): void
    {
        $cart->update(['status' => 'abandoned']);
        $this->logEvent($cart, 'abandoned');
    }

    public function attachCartToUser(Cart $cart, User $user): void
    {
        $cart->update(['user_id' => $user->id]);
        $this->logEvent($cart, 'attach_user', ['user_id' => $user->id]);
    }

    private function recalculateTotals(Cart $cart): void
    {
        $cart->update([
            'total' => $cart->items()->sum('total'),
        ]);
    }

    private function logEvent(Cart $cart, string $event, array $payload = []): void
    {
        try {
            CartActivityLog::create([
                'cart_id' => $cart->id,
                'event'   => $event,
                'payload' => $payload,
            ]);
        } catch (\Throwable $e) {
            \Log::warning('Cart log failed', [
                'cart_id' => $cart->id,
                'event'   => $event,
                'error'   => $e->getMessage(),
            ]);
        }
    }
}
