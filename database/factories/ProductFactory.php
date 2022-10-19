<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'  => ucfirst(fake()->name()),
            'desc'  => ucfirst(fake()->text()),
            'SKU'   => fake()->unique()->uuid(),
            'price' => fake()->randomFloat(2, 200, 100000),
        ];
    }
}
