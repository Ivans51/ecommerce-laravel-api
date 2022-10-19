<?php

namespace Database\Factories;

use App\Models\PaymentDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id'    => User::query()->inRandomOrder()->first()->id,
            'payment_id' => PaymentDetail::query()->inRandomOrder()->first()->id,
            'total'      => fake()->randomDigit(),
        ];
    }
}