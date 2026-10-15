<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Salida;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Salida> */
class SalidaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'producto_id' => Producto::factory(),
            'cliente_id' => Cliente::factory(),
            'cantidad' => fake()->numberBetween(1, 10),
        ];
    }
}
