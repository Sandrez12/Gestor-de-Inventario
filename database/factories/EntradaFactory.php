<?php

namespace Database\Factories;

use App\Models\Entrada;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Entrada> */
class EntradaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'producto_id' => Producto::factory(),
            'proveedor_id' => Proveedor::factory(),
            'cantidad' => fake()->numberBetween(1, 30),
        ];
    }
}
