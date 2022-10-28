<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\CartItem;
use App\Models\OrderDetail;
use App\Models\OrderItem;
use App\Models\PaymentDetail;
use App\Models\Product;
use App\Models\ShoppingSession;
use App\Models\User;
use Database\Factories\CartItemFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                UserRoleSeeder::class,
            ]
        );

        User::factory(4)->create();
        PaymentDetail::factory(10)->create();
        OrderDetail::factory(10)->create();
        ShoppingSession::factory(10)->create();
        Product::factory(10)->create();
        CartItem::factory(10)->create();
        OrderItem::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
