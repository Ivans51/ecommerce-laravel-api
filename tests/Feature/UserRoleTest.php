<?php

namespace Tests\Feature;

use App\Helpers\Constants;
use App\Models\User;
use App\Models\UserRole;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserRoleTest extends TestCase
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

        $response = $this->getJson('/api/user-role/list');

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

        $response = $this->getJson('/api/user-role/' . $user->id);

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
            ->postJson('/api/user-role', [
                "type"        => "customer2",
                "permissions" => "test",
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

        $model = UserRole::query()
            ->inRandomOrder()
            ->first();

        $response = $this
            ->putJson('/api/user-role/' . $model->id, [
                "type"        => "customer3",
                "permissions" => "test2",
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

        $model = UserRole::query()
            ->inRandomOrder()
            ->first();

        $response = $this->deleteJson('/api/user-role/' . $model->id);

        $response->assertStatus(204);
    }
}
