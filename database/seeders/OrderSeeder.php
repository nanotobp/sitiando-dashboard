<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str; // <--- IMPORTANTE

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        for ($i = 1; $i <= 10; $i++) {
            Order::create([
                'id' => Str::uuid(),
                'order_number' => 'ORD-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'customer_id' => $user->id,

                // Campos reales según estructura de tu tabla:
                'customer_email' => "cliente{$i}@test.com",
                'customer_phone' => "09918801{$i}",

                'shipping_address' => [
                    'full_name' => "Cliente {$i}",
                    'address_line_1' => "Calle Falsa {$i}",
                    'city' => "Asunción",
                    'country' => "PY",
                ],

                'billing_address' => [
                    'full_name' => "Cliente {$i}",
                    'ruc' => "1234567-{$i}",
                    'address' => "Barrio Centro",
                ],

                'subtotal' => 0,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'shipping_amount' => 0,
                'total' => 0,

                'status' => 'pending',
            ]);
        }
    }
}
