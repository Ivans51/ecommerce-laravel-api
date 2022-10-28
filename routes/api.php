<?php

use App\Helpers\Constants;
use App\Http\Controllers\api\CartItemController;
use App\Http\Controllers\api\DiscountController;
use App\Http\Controllers\api\MenuController;
use App\Http\Controllers\api\OrderDetailsController;
use App\Http\Controllers\api\ProductCategoryController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\ProductInventoryController;
use App\Http\Controllers\api\UserAddressController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\UserPaymentController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'loginCustomer']);
    Route::post('login-admin', [AuthController::class, 'loginAdmin']);
    Route::post('signup', [AuthController::class, 'signUp']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('update-forgot-password', [AuthController::class, 'updateForgotPassword']);
    Route::post('verify-email', [AuthController::class, 'verifyEmail']);
    Route::post('update-verify-email', [AuthController::class, 'updateVerifyEmail']);

    Route::group([
        'middleware' => 'auth:sanctum'
    ], function () {
        Route::get('logout', [AuthController::class, 'logout']);
    });
});

Route::group([
    'middleware' => 'auth:sanctum'
], function () {
    Route::get('users/profile', [UserController::class, 'profile']);
    Route::post('users/image-profile', [UserController::class, 'updateImageProfile']);
    Route::put('users/password', [UserController::class, 'updatePassword']);

    /* Admin */
    Route::group(array(
        'middleware' => 'checkUserApi:' . Constants::ADMIN
    ), function () {
        Route::get('users-list', [UserController::class, 'userList']);
        Route::get('cart-items-list', [CartItemController::class, 'cartItemList']);
        Route::get('order-details-list', [OrderDetailsController::class, 'orderDetailsList']);

        Route::get('cart-items', [CartItemController::class, 'index']);
        Route::get('cart-items/{id}', [CartItemController::class, 'show']);
        Route::delete('cart-items/{id}', [CartItemController::class, 'destroy']);

        Route::get('order-details', [OrderDetailsController::class, 'index']);
        Route::get('order-details/{id}', [OrderDetailsController::class, 'show']);
        Route::delete('order-details/{id}', [OrderDetailsController::class, 'destroy']);

        Route::resource('users', UserController::class);
        Route::resource('user-payments', UserPaymentController::class);
        Route::resource('user-addresses', UserAddressController::class);
        Route::resource('product-categories', ProductCategoryController::class);
        Route::resource('product-inventories', ProductInventoryController::class);
        Route::resource('discounts', DiscountController::class);
        Route::resource('products', ProductController::class);
        Route::resource('menus', MenuController::class);
        Route::get('menus-admin', [MenuController::class, 'admin']);
    });

    /* Customer */
    Route::group(array(
        'middleware' => 'checkUserApi:' . Constants::CUSTOMER
    ), function () {
        Route::get('menus-customer', [MenuController::class, 'customer']);

        Route::post('cart-items', [CartItemController::class, 'store']);
        Route::put('cart-items/{id}', [CartItemController::class, 'update']);

        Route::post('order-details', [OrderDetailsController::class, 'store']);
        Route::put('order-details/{id}', [OrderDetailsController::class, 'update']);
    });
});
