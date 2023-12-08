<?php

use App\Http\Controllers\Api\Address\AddressController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Home\HomeController;
use App\Http\Controllers\Api\Order\CartController;
use App\Http\Controllers\Api\Order\OrderController;
use App\Http\Controllers\SocialController;
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

Route::group(['prefix' => 'home'], function () {
    Route::get('data', [HomeController::class, 'data'])->name('data');
    Route::get('products', [HomeController::class, 'get_products'])->name('products');
    Route::get('product/{id}', [HomeController::class, 'get_product'])->name('product');
    Route::get('categories', [HomeController::class, 'get_categories_with_children'])->name('categories');
    Route::post('add_favourite', [HomeController::class, 'add_favourite'])->name('add_favourite')->middleware('auth:users');
    Route::get('get_user_favourites', [HomeController::class, 'get_user_favourites'])->middleware('auth:users');
    Route::get('get_user_notifications', [HomeController::class, 'get_user_notifications'])->middleware('auth:users');
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register'])->name('userRegister');
    Route::post('verifyUser', [AuthController::class, 'verifyUserEmail'])->name('verifyUserEmail');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:users');
    Route::post('update_profile', [AuthController::class, 'updateProfile'])->name('Update_profile');
    Route::post('change_password', [AuthController::class, 'ChangePassword'])->name('changePassword');
    Route::get('user', [AuthController::class, 'user'])->name('user')->middleware('auth:users');
    Route::post('reset_password', [AuthController::class, 'resetPassword']);
    Route::post('verify_reset_password', [AuthController::class, 'verifyResetPassword']);
    Route::post('change_password', [AuthController::class, 'changePassword']);
    Route::post('social_auth', [SocialController::class, 'socialAuth']);
});

Route::group(['prefix' => 'order'], function () {
    Route::post('add_to_cart', [CartController::class, 'AddToCart'])->middleware('auth:users');
    Route::post('update_cart', [CartController::class, 'updateCart'])->middleware('auth:users');
    Route::post('delete_item', [CartController::class, 'deleteItem'])->middleware('auth:users');
    Route::post('clear_cart', [CartController::class, 'clearCart'])->middleware('auth:users');
    Route::get('get_cart', [CartController::class, 'get_cart'])->middleware('auth:users');
    Route::get('coupon_check', [OrderController::class, 'coupon_check'])->middleware('auth:users');
    Route::post('create_order', [OrderController::class, 'create_order'])->middleware('auth:users');
    Route::post('cancel_order', [OrderController::class, 'cancel_order'])->middleware('auth:users');
    Route::get('checkout_cart', [OrderController::class, 'checkout_cart'])->middleware('auth:users');
    Route::get('get_orders', [OrderController::class, 'get_orders'])->middleware('auth:users');
});

Route::group(['prefix' => 'address'], function () {
    Route::apiResource('address', AddressController::class)->middleware('auth:users');
});
