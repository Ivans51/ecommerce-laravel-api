<?php

namespace Tests\Feature;

use App\Helpers\Constants;
use App\Models\Product;
use App\Models\User;
use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductTest extends TestCase
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

        $response = $this->getJson('/api/product/list');

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

        $id       = Product::query()->inRandomOrder()->first()->id;
        $response = $this->getJson('/api/product/' . $id);

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
            ->postJson('/api/product', [
                'name'         => $faker->word(),
                'desc'         => $faker->word(),
                'SKU'          => $faker->word(),
                'price'        => $faker->randomNumber(4),
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
        $id       = Product::query()->inRandomOrder()->first()->id;
        $response = $this
            ->putJson('/api/product/' . $id, [
                'name'         => $faker->word(),
                'desc'         => $faker->word(),
                'SKU'          => $faker->word(),
                'price'        => $faker->randomNumber(4),
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

        $id       = Product::query()->inRandomOrder()->first()->id;
        $response = $this->deleteJson('/api/product/' . $id);

        $response->assertStatus(204);
    }
}
