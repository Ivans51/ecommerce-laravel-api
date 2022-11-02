<?php

namespace Database\Seeders;

use App\Helpers\Constants;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userRoleAdmin    = UserRole::query()->where('type', Constants::ROLE_ADMIN)->first()->id;
        $userRoleCustomer = UserRole::query()->where('type', Constants::ROLE_CUSTOMER)->first()->id;

        $customerMenu = [
            [
                'id'         => 'bccfeba7-b902-4a9d-a562-fb0a181b313c',
                'role_id'    => $userRoleCustomer,
                'title'      => 'Home',
                'url'        => 'customer-home',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 'aa928ea8-0321-4b31-a83b-026844e47051',
                'role_id'    => $userRoleCustomer,
                'title'      => 'Products',
                'url'        => 'customer-products',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '6281ac21-3ddb-4ef4-99c3-aa772bc3091d',
                'role_id'    => $userRoleCustomer,
                'title'      => 'Contacts',
                'url'        => 'customer-contacts',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $adminMenu = [
            [
                'id'         => 'a35c1599-2f54-4b0b-98a4-21dbba343e5a',
                'role_id'    => $userRoleAdmin,
                'title'      => 'Dashboard',
                'url'        => 'admin-home',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 'd6260d83-ce3b-460f-b30d-a0e488870cbc',
                'role_id'    => $userRoleAdmin,
                'title'      => 'Admins',
                'url'        => 'admin-admins',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 'b9f37251-1cad-4b20-bf68-72aa2d4a7354',
                'role_id'    => $userRoleAdmin,
                'title'      => 'Customers',
                'url'        => 'admin-customers',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '6de0f261-4023-4e4b-b1ed-99588b101c19',
                'role_id'    => $userRoleAdmin,
                'title'      => 'Store',
                'url'        => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '500a821f-cbfc-4838-ac98-c62359447098',
                'role_id'    => $userRoleAdmin,
                'title'      => 'Sales',
                'url'        => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \DB::table('menus')->insert(array_merge($customerMenu, $adminMenu));
    }
}
