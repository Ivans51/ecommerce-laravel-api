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
    Route::get('user/profile', [UserController::class, 'profile']);
    Route::post('user/image-profile', [UserController::class, 'updateImageProfile']);
    Route::put('user/password', [UserController::class, 'updatePassword']);
    Route::resource('user', UserController::class);

    /* Admin */
    Route::group(array(
        'middleware' => 'checkUserApi:' . Constants::ROLE_ADMIN
    ), function () {
        Route::get('user-list', [UserController::class, 'userList']);
        Route::get('cart-item-list', [CartItemController::class, 'cartItemList']);
        Route::get('order-detail-list', [OrderDetailsController::class, 'orderDetailsList']);

        Route::get('cart-item', [CartItemController::class, 'index']);
        Route::get('cart-item/{id}', [CartItemController::class, 'show']);
        Route::delete('cart-item/{id}', [CartItemController::class, 'destroy']);

        Route::get('order-detail', [OrderDetailsController::class, 'index']);
        Route::get('order-detail/{id}', [OrderDetailsController::class, 'show']);
        Route::delete('order-detail/{id}', [OrderDetailsController::class, 'destroy']);

        Route::resource('user-payment', UserPaymentController::class);
        Route::resource('user-address', UserAddressController::class);
        Route::resource('product-category', ProductCategoryController::class);
        Route::resource('product-inventory', ProductInventoryController::class);
        Route::resource('discount', DiscountController::class);
        Route::resource('product', ProductController::class);
        Route::resource('menu', MenuController::class);
        Route::get('menus-admin', [MenuController::class, 'admin']);
    });

    /* Customer */
    Route::group(array(
        'middleware' => 'checkUserApi:' . Constants::ROLE_CUSTOMER
    ), function () {
        Route::get('menus-customer', [MenuController::class, 'customer']);

        Route::post('cart-items', [CartItemController::class, 'store']);
        Route::put('cart-items/{id}', [CartItemController::class, 'update']);

        Route::post('order-details', [OrderDetailsController::class, 'store']);
        Route::put('order-details/{id}', [OrderDetailsController::class, 'update']);
    });
});
