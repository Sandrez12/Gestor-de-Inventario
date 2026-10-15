<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Producto> */
class ProductoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->words(3, true),
            'descripcion' => fake()->sentence(),
            'stock' => fake()->numberBetween(0, 100),
        ];
    }

    public function conStock(int $stock): static
    {
        return $this->state(fn () => ['stock' => $stock]);
    }
}
