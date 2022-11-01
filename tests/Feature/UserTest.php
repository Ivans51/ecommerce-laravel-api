<?php

namespace Tests\Feature;

use App\Helpers\Constants;
use App\Models\User;
use App\Models\UserRole;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_profile()
    {
        Sanctum::actingAs(
            User::query()->inRandomOrder()->first(),
            [Constants::ROLE_ADMIN]
        );

        $response = $this->getJson('/api/user/profile');

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_list()
    {
        Sanctum::actingAs(
            User::query()->inRandomOrder()->first(),
            [Constants::ROLE_ADMIN]
        );

        $response = $this->getJson('/api/user/list');

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_list_filter()
    {
        Sanctum::actingAs(
            User::query()->inRandomOrder()->first(),
            [Constants::ROLE_ADMIN]
        );

        $roleId = 'role_id=' . UserRole::query()->inRandomOrder()->first()->id;
        $email  = 'email=' . User::query()->inRandomOrder()->first()->email;

        $response = $this->getJson('/api/user/list?' . $roleId . '&' . $email);

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

        $response = $this->getJson('/api/user/' . $user->id);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /*public function test_store()
    {
        $userRole = UserRole::query()
            ->where('type', Constants::ROLE_ADMIN)
            ->first();

        Sanctum::actingAs(
            User::query()->where('role_id', $userRole->id)->inRandomOrder()->first(),
            [Constants::ROLE_ADMIN]
        );

        $response = $this
            ->postJson('/api/user', [
                "first_name" => "test name",
                "last_name"  => "test last",
                "telephone"  => "0546456465456",
                "email"      => "testing-customer@ecommerce-laravel.com",
                "type"       => Constants::ROLE_ADMIN,
            ]);

        $response->assertStatus(201);
    }*/

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /*public function test_update_image_profile()
    {
        $user = User::query()->inRandomOrder()->first();

        Sanctum::actingAs(
            $user,
            [Constants::ROLE_ADMIN]
        );

        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this
            ->postJson('/api/user/image-profile', [
                "id"            => $user->id,
                "image_profile" => $file,
            ]);

        $response->assertStatus(204);
    }*/

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

        $response = $this
            ->putJson('/api/user/' . $user->id, [
                "first_name" => "test name2",
                "last_name"  => "test last2",
                "telephone"  => "05464564654562",
                "email"      => "admin3@ecommerce-laravel.com",
                "type"       => Constants::ROLE_ADMIN
            ]);

        $response->assertStatus(204);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_password()
    {
        $user = User::query()->inRandomOrder()->first();

        Sanctum::actingAs(
            $user,
            [Constants::ROLE_ADMIN]
        );

        $response = $this
            ->putJson('api/user/password', [
                "id"                    => $user->id,
                "password"              => "12345678",
                "password_confirmation" => "12345678"
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

        $user = User::query()->where('id', '!=', $user->id)->inRandomOrder()->first();

        $response = $this->deleteJson('/api/user/' . $user->id);

        $response->assertStatus(204);
    }
}
