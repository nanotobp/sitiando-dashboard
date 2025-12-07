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
                'total'   => 0,
            ]
        );
    }

    /**
     * Agregar producto al carrito.
     */
    public function addItem(User $user, int $productId, int $qty = 1): Cart
    {
        return DB::transaction(function () use ($user, $productId, $qty) {

            if ($qty < 1) {
                $qty = 1;
            }

            $cart = $this->getOrCreateCartForUser($user);

            $product = Product::where('activo', true)->findOrFail($productId);

            // Validar stock (opcional, pero PRO)
            if ($product->stock !== null && $product->stock < $qty) {
                throw ValidationException::withMessages([
                    'qty' => 'No hay stock suficiente para este producto.',
                ]);
            }

            /** @var CartItem $item */
            $item = CartItem::firstOrNew([
                'cart_id'    => $cart->id,
                'product_id' => $product->id,
            ]);

            $item->qty   = $item->exists ? $item->qty + $qty : $qty;
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
    public function updateItemQuantity(User $user, int $itemId, int $qty): Cart
    {
        return DB::transaction(function () use ($user, $itemId, $qty) {

            $cart = $this->getOrCreateCartForUser($user);

            /** @var CartItem $item */
            $item = CartItem::where('cart_id', $cart->id)->findOrFail($itemId);

            if ($qty <= 0) {
                // Si la cantidad es 0 o menos, eliminamos el ítem
                $productId = $item->product_id;
                $item->delete();

                $this->recalculateTotals($cart);

                $this->logEvent($cart, 'remove_item', [
                    'product_id' => $productId,
                    'reason'     => 'qty_zero',
                ]);

                return $cart->fresh(['items.product']);
            }

            // Validar stock contra el producto
            $product = Product::findOrFail($item->product_id);
            if ($product->stock !== null && $product->stock < $qty) {
                throw ValidationException::withMessages([
                    'qty' => 'No hay stock suficiente para este producto.',
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
    public function removeItem(User $user, int $itemId): Cart
    {
        return DB::transaction(function () use ($user, $itemId) {

            $cart = $this->getOrCreateCartForUser($user);

            /** @var CartItem $item */
            $item = CartItem::where('cart_id', $cart->id)->findOrFail($itemId);

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

    /**
     * Marcar el carrito como "completed" (cuando se genera la orden).
     */
    public function markCompleted(Cart $cart): void
    {
        $cart->update([
            'status' => 'completed',
        ]);

        $this->logEvent($cart, 'completed');
    }

    /**
     * (Usado por el comando) Marcar carritos inactivos como "abandoned".
     */
    public function markAbandoned(Cart $cart): void
    {
        $cart->update([
            'status' => 'abandoned',
        ]);

        $this->logEvent($cart, 'abandoned');
    }

    /**
     * Reasignar carrito cuando el usuario se loguea (carrito invitado → usuario).
     * Por ahora trabajamos solo con carritos por usuario, pero dejamos el hook.
     */
    public function attachCartToUser(Cart $cart, User $user): void
    {
        $cart->update([
            'user_id' => $user->id,
        ]);

        $this->logEvent($cart, 'attach_user', [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Recalcular el total del carrito a partir de sus items.
     */
    private function recalculateTotals(Cart $cart): void
    {
        $total = $cart->items()->sum('total');

        $cart->update([
            'total' => $total,
        ]);
    }

    /**
     * Registrar un evento en el log de actividad del carrito.
     */
    private function logEvent(Cart $cart, string $event, array $payload = []): void
    {
        try {
            CartActivityLog::create([
                'cart_id' => $cart->id,
                'event'   => $event,
                'payload' => $payload,
            ]);
        } catch (\Throwable $e) {
            // No rompemos el flujo de carrito si falla el log
            \Log::warning('No se pudo registrar activity log del carrito', [
                'cart_id' => $cart->id,
                'event'   => $event,
                'error'   => $e->getMessage(),
            ]);
        }
    }
}
