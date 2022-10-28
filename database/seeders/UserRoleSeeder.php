<?php

namespace Database\Seeders;

use App\Helpers\Constants;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('user_roles')->insert(
            [
                [
                    'id'          => '35fa5ed2-ae1b-4684-956d-887d58d62ec5',
                    'type'        => Constants::ROLE_ADMIN,
                    'permissions' => 'all',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'id'          => '71a3ba7b-a82a-46c4-9ec1-19da2e60b1ef',
                    'type'        => Constants::ROLE_CUSTOMER,
                    'permissions' => 'home',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
            ]
        );
    }
}
