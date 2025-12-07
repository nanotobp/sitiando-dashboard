<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\OrderStatusHistory;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->error("⚠ No hay usuarios. Creá uno antes de correr el seeder.");
            return;
        }

        for ($i = 1; $i <= 10; $i++) {

            // ========= GENERAR NÚMERO DE ORDEN ÚNICO =========
            $orderNumber = 'ORD-' . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT);

            // ========= CREAR ORDEN =========
            $order = Order::create([
                'user_id'        => $user->id,
                'order_number'   => $orderNumber,
                'customer_name'  => "Cliente $i",
                'customer_email' => "cliente$i@test.com",
                'customer_phone' => '0991' . rand(100000, 999999),
                'subtotal'       => 0,
                'discount'       => 0,
                'total'          => 0,
                'status'         => 'pending',
            ]);

            // ========= CREAR ITEMS =========
            for ($j = 1; $j <= 3; $j++) {
                $price = rand(20000, 120000);
                $qty   = rand(1, 3);

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => 1, // cambiar si tenés productos
                    'qty'        => $qty,
                    'price'      => $price,
                    'total'      => $price * $qty,
                ]);
            }

            // ========= ACTUALIZAR TOTALES =========
            $order->update([
                'subtotal' => $order->items()->sum('total'),
                'total'    => $order->items()->sum('total'),
            ]);

            // ========= CREAR PAGO =========
            OrderPayment::create([
                'order_id'        => $order->id,
                'status'          => 'pending',
                'amount'          => $order->total,
                'payment_method'  => 'bancard',
                'transaction_id'  => 'TX-' . uniqid(),
            ]);

            // ========= HISTORIAL DE ESTADO =========
            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'status'     => 'pending',
                'notes'      => 'Orden creada automáticamente',
                'changed_by' => $user->id,
            ]);
        }

        $this->command->info("✔ Seeder ejecutado con éxito sin errores 🎉");
    }
}
