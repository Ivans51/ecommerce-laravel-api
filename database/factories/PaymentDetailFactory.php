<?php

namespace Database\Factories;

use App\Helpers\Enums\StatusPayment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentDetail>
 */
class PaymentDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'amount'   => fake()->randomFloat(2, 100, 100000),
            'provider' => ucfirst(fake()->word()),
            'status'   => fake()->randomElement([StatusPayment::PENDING, StatusPayment::COMPLETED, StatusPayment::CANCELED]),
        ];
    }
}
