<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->sentence(3),
            'sku' => strtoupper(Str::random(8)),
            'precio' => $this->faker->numberBetween(20000, 300000),
            'stock' => $this->faker->numberBetween(1, 50),
            'imagen' => null, // lo podés mejorar luego
            'activo' => true,
        ];
    }
}
