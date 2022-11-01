<?php

namespace Database\Factories;

use App\Helpers\Enums\StatusPayment;
use App\Models\OrderDetail;
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
            'amount'           => fake()->randomFloat(2, 100, 100000),
            'provider'         => ucfirst(fake()->word()),
            'order_details_id' => OrderDetail::query()->inRandomOrder()->first()->id,
            'status'           => fake()->randomElement([
                StatusPayment::PENDING, StatusPayment::COMPLETED, StatusPayment::CANCELED
            ]),
        ];
    }
}
