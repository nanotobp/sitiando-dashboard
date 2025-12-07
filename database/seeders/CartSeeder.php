<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (! $user) {
            $this->command->warn('⚠ No hay usuarios. Ejecutá primero AdminUserSeeder.');
            return;
        }

        // Aseguramos que existan productos
        if (Product::count() === 0) {
            $this->command->warn('⚠ No hay productos. Creando 5 productos de prueba...');

            for ($i = 1; $i <= 5; $i++) {
                Product::create([
                    'nombre' => "Producto demo $i",
                    'sku'    => "SKU-DEMO-$i",
                    'precio' => rand(20000, 120000),
                    'stock'  => rand(5, 50),
                    'activo' => true,
                ]);
            }
        }

        $products = Product::inRandomOrder()->take(10)->get();

        if ($products->isEmpty()) {
            $this->command->error('❌ No se pudieron obtener productos para los carritos.');
            return;
        }

        $this->command->info('🛒 Generando carritos de prueba...');

        // Vamos a crear 7 carritos con distintos estados
        $statuses = [
            'active',
            'active',
            'active',
            'abandoned',
            'abandoned',
            'completed',
            'completed',
        ];

        foreach ($statuses as $index => $status) {

            $cart = Cart::create([
                'user_id' => $user->id,
                'status'  => $status,
                'total'   => 0,
            ]);

            // Items aleatorios
            $itemsCount = rand(2, 4);
            $cartTotal  = 0;

            for ($i = 0; $i < $itemsCount; $i++) {
                $product = $products->random();
                $qty     = rand(1, 3);
                $price   = $product->precio;
                $lineTotal = $price * $qty;

                CartItem::create([
                    'cart_id'    => $cart->id,
                    'product_id' => $product->id,
                    'qty'        => $qty,
                    'price'      => $price,
                    'total'      => $lineTotal,
                ]);

                $cartTotal += $lineTotal;
            }

            // Actualizar total del carrito
            $cart->update(['total' => $cartTotal]);

            // Ajustar tiempos según estado (para que el dashboard tenga sentido)
            if ($status === 'active') {
                $cart->update([
                    'created_at' => now()->subMinutes(rand(5, 30)),
                    'updated_at' => now()->subMinutes(rand(1, 5)),
                ]);
            }

            if ($status === 'abandoned') {
                $cart->update([
                    'created_at' => now()->subHours(rand(2, 24)),
                    'updated_at' => now()->subMinutes(rand(60, 240)), // hace más de 1 hora
                ]);
            }

            if ($status === 'completed') {
                $cart->update([
                    'created_at' => now()->subDays(rand(1, 10)),
                    'updated_at' => now()->subDays(rand(1, 10)),
                ]);
            }
        }

        $this->command->info('✔ CartSeeder ejecutado: carritos + items de prueba creados 🎉');
    }
}
