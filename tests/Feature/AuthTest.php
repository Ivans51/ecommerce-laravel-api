<?php

namespace Tests\Feature;

use App\Helpers\Constants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login_customer()
    {
        $response = $this->postJson('/api/auth/login', [
            "email"    => Constants::TEST_USER_CUSTOMER,
            "password" => "password"
        ]);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login_admin()
    {
        $response = $this->postJson('/api/auth/login-admin', [
            "email"    => Constants::TEST_USER_ADMIN,
            "password" => "password"
        ]);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /*public function test_sign_up()
    {
        $response = $this->postJson('/api/auth/signup', [
            "username"              => "test username2",
            "first_name"            => "test name",
            "last_name"             => "test last",
            "telephone"             => "0546456465456",
            "email"                 => "testing@ecommerce-laravel.com",
            "type_role"             => Constants::ADMIN,
            "password"              => "password",
            "password_confirmation" => "password"
        ]);

        $response->assertStatus(200);
    }*/

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /*public function test_forgot_password()
    {
        $response = $this->postJson('/api/auth/forgot-password', [
            "email" => "testing@ecommerce-laravel.com",
        ]);

        $response->assertStatus(204);
    }*/

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /*public function test_update_forgot_password()
    {
        $user = User::query()->where('email', '=', 'testing@ecommerce-laravel.com')->first();

        $response = $this->postJson('/api/auth/update-forgot-password', [
            "email"                 => "testing@ecommerce-laravel.com",
            "token"                 => $user->token_password_forgot,
            "password"              => "12345678",
            "password_confirmation" => "12345678"
        ]);

        $response->assertStatus(204);
    }*/

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /*public function test_verify_email()
    {
        $response = $this->postJson('/api/auth/verify-email', [
            "email" => "testing@ecommerce-laravel.com",
        ]);

        $response->assertStatus(204);
    }*/

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /*public function test_update_verify_email()
    {
        $user = User::query()->where('email', '=', 'testing@ecommerce-laravel.com')->first();

        $response = $this->postJson('/api/auth/update-verify-email', [
            "email" => "testing@ecommerce-laravel.com",
            "token" => $user->token_email_verify,
        ]);

        $response->assertStatus(204);
    }*/
}
