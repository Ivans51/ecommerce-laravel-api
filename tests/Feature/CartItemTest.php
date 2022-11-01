<?php

namespace Tests\Feature;

use App\Helpers\Constants;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ShoppingSession;
use App\Models\User;
use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartItemTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_list()
    {
        $user = User::query()->inRandomOrder()->first();

        Sanctum::actingAs(
            $user,
            [Constants::ROLE_ADMIN]
        );

        $response = $this->getJson('/api/cart-item/list');

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_show()
    {
        $user = User::query()->inRandomOrder()->first();

        Sanctum::actingAs(
            $user,
            [Constants::ROLE_ADMIN]
        );

        $id       = CartItem::query()->inRandomOrder()->first()->id;
        $response = $this->getJson('/api/cart-item/' . $id);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store()
    {
        $user = User::query()->inRandomOrder()->first();

        Sanctum::actingAs(
            $user,
            [Constants::ROLE_ADMIN]
        );

        $faker    = Factory::create();
        $response = $this
            ->postJson('/api/cart-item', [
                "quantity"   => $faker->randomDigit(),
                "session_id" => ShoppingSession::query()->inRandomOrder()->first()->id,
                "product_id" => Product::query()->inRandomOrder()->first()->id,
            ]);

        $response->assertStatus(201);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_update()
    {
        $user = User::query()->inRandomOrder()->first();

        Sanctum::actingAs(
            $user,
            [Constants::ROLE_ADMIN]
        );

        $faker    = Factory::create();
        $id       = CartItem::query()->inRandomOrder()->first()->id;
        $response = $this
            ->putJson('/api/cart-item/' . $id, [
                "quantity"   => $faker->randomDigit(),
                "session_id" => ShoppingSession::query()->inRandomOrder()->first()->id,
                "product_id" => Product::query()->inRandomOrder()->first()->id,
            ]);

        $response->assertStatus(204);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_destroy()
    {
        $user = User::query()->inRandomOrder()->first();

        Sanctum::actingAs(
            $user,
            [Constants::ROLE_ADMIN]
        );

        $id       = CartItem::query()->inRandomOrder()->first()->id;
        $response = $this->deleteJson('/api/cart-item/' . $id);

        $response->assertStatus(204);
    }
}
