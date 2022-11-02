<?php

namespace Tests\Feature;

use App\Helpers\Constants;
use App\Models\Menu;
use App\Models\User;
use App\Models\UserRole;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MenuTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin()
    {
        $userRole = UserRole::query()
            ->where('type', Constants::ROLE_ADMIN)
            ->first();

        Sanctum::actingAs(
            User::query()->where('role_id', $userRole->id)->inRandomOrder()->first(),
            [Constants::ROLE_ADMIN]
        );

        $response = $this->getJson('/api/menu-admin');

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer()
    {
        $userRole = UserRole::query()
            ->where('type', Constants::ROLE_CUSTOMER)
            ->first();

        Sanctum::actingAs(
            User::query()->where('role_id', $userRole->id)->inRandomOrder()->first(),
            [Constants::ROLE_CUSTOMER]
        );

        $response = $this->getJson('/api/menu-customer');

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

        $response = $this->getJson('/api/menu/' . $user->id);

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
            ->where('type', Constants::ROLE_ADMIN)
            ->first();

        Sanctum::actingAs(
            User::query()->where('role_id', $userRole->id)->inRandomOrder()->first(),
            [Constants::ROLE_ADMIN]
        );

        $response = $this
            ->postJson('/api/menu', [
                "title"   => "test",
                "role_id" => UserRole::query()->inRandomOrder()->first()->id,
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

        $model = Menu::query()
            ->inRandomOrder()
            ->first();

        $response = $this
            ->putJson('/api/menu/' . $model->id, [
                "title"   => "test2",
                "role_id" => UserRole::query()->inRandomOrder()->first()->id,
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

        $model = Menu::query()
            ->inRandomOrder()
            ->first();

        $response = $this->deleteJson('/api/menu/' . $model->id);

        $response->assertStatus(204);
    }
}
