<?php

namespace Tests\Feature;

use App\Helpers\Constants;
use App\Models\CartItem;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ShoppingSession;
use App\Models\User;
use App\Models\UserRole;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderDetailsTest extends TestCase
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

        $response = $this->getJson('/api/order-detail/list');

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

        $id       = OrderDetail::query()->inRandomOrder()->first()->id;
        $response = $this->getJson('/api/order-detail/' . $id);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store()
    {
        $userRole = UserRole::query()
            ->where('type', Constants::ROLE_CUSTOMER)
            ->first();

        $user = User::query()->inRandomOrder()
            ->where('role_id', $userRole->id)
            ->first();

        $shoppingSession = ShoppingSession::query()
            ->where('user_id', $user->id);

        if ($shoppingSession->exists()) {
            $shoppingId = $shoppingSession->first()->id;

            $cartItem = CartItem::query()
                ->where('session_id', $shoppingId);

            if (!$cartItem->exists()) {
                CartItem::query()->create([
                    'quantity'   => 5,
                    'session_id' => $shoppingId,
                    'product_id' => Product::query()->inRandomOrder()->first()->id,
                ]);
            }

        } else {
            $shoppingSession = ShoppingSession::query()->create([
                'total'   => 10,
                'user_id' => $user->id,
            ]);

            CartItem::query()->create([
                'quantity'   => 5,
                'session_id' => $shoppingSession->id,
                'product_id' => Product::query()->inRandomOrder()->first()->id,
            ]);
        }

        Sanctum::actingAs(
            $user,
            [Constants::ROLE_ADMIN]
        );

        $response = $this
            ->postJson('/api/order-detail', []);

        $response->assertStatus(201);
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

        $id       = OrderDetail::query()->inRandomOrder()->first()->id;
        $response = $this->deleteJson('/api/order-detail/' . $id);

        $response->assertStatus(204);
    }
}
