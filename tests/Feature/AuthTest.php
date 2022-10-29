<?php

namespace Tests\Feature;

use App\Helpers\Constants;
use App\Models\User;
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
     * @group ignore
     * @return void
     */
    public function test_sign_up()
    {
        $response = $this->postJson('/api/auth/signup', [
            "first_name"            => "test name",
            "last_name"             => "test last",
            "address"               => "test address",
            "telephone"             => "0546456465456",
            "email"                 => Constants::TEST_USER,
            "type_role"             => Constants::ROLE_ADMIN,
            "password"              => "password",
            "password_confirmation" => "password"
        ]);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     * @group ignore
     * @return void
     */
    public function test_forgot_password()
    {
        $response = $this->postJson('/api/auth/forgot-password', [
            "email" => Constants::TEST_USER,
        ]);

        $response->assertStatus(204);
    }

    /**
     * A basic feature test example.
     * @depends test_forgot_password
     * @group ignore
     * @return void
     */
    public function test_update_forgot_password()
    {
        $email = Constants::TEST_USER;
        $user  = User::query()->where('email', '=', $email)->first();

        $response = $this->postJson('/api/auth/update-forgot-password', [
            "email"                 => $email,
            "token"                 => $user->token_password_forgot,
            "password"              => "12345678",
            "password_confirmation" => "12345678"
        ]);

        $response->assertStatus(204);
    }

    /**
     * A basic feature test example.
     * @group ignore
     * @return void
     */
    public function test_verify_email()
    {
        $response = $this->postJson('/api/auth/verify-email', [
            "email" => Constants::TEST_USER,
        ]);

        $response->assertStatus(204);
    }

    /**
     * A basic feature test example.
     * @depends test_verify_email
     * @group ignore
     * @return void
     */
    public function test_update_verify_email()
    {
        $email = Constants::TEST_USER;
        $user  = User::query()->where('email', '=', $email)->first();

        $response = $this->postJson('/api/auth/update-verify-email', [
            "email" => $email,
            "token" => $user->token_email_verify,
        ]);

        $response->assertStatus(204);
    }
}
