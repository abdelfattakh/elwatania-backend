<?php

use App\Http\Controllers\Web\Address\AddressController;
use App\Http\Controllers\Web\Auth\AuthController;
use App\Http\Controllers\Web\Home\FilterController;
use App\Http\Controllers\Web\Home\HomeController;
use App\Http\Controllers\Web\Home\IndexController;
use App\Http\Controllers\Web\Order\CartController;
use App\Http\Controllers\web\OrderController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\web\Auth\SocialController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Routes to get all web
 */

Route::group(['prefix' => 'auth'], function () {
    Route::get('login', [IndexController::class, 'login'])->middleware('guest')->name('login');
    Route::post('login', [AuthController::class, 'login'])->middleware('guest')->name('login.submit');
    Route::get('register', [IndexController::class, 'register'])->middleware('guest')->name('register');
    Route::post('register', [AuthController::class, 'register'])->middleware('guest')->name('register.submit');
    Route::post('update_profile', [AuthController::class, 'update_profile'])->middleware('auth:web')->name('updateProfile.submit');
    Route::get('/update_profile', [IndexController::class, 'update_profile'])->middleware('auth:web')->name('updateProfile');
    Route::post('/change_password', [AuthController::class, 'change_password'])->middleware('auth:web')->name('auth.change_password');
    Route::get('/change_email', [AuthController::class, 'change_email'])->middleware('auth:web')->name('auth.change_email');
    Route::post('/change_email', [AuthController::class, 'change_email_submit'])->name('auth.changeEmailSubmit');
    Route::post('/reset_password_submit', [AuthController::class, 'reset_password'])->name('auth.reset_password');
    Route::get('/send_reset_code', [IndexController::class, 'send_reset_code'])->name('auth.sendResetCode');
    Route::get('/reset_otp', [IndexController::class, 'reset_otp'])->name('auth.ResetOTP');
    Route::get('/verify_reset_code', [IndexController::class, 'verify_reset_code'])->name('auth.verifyResetCode');
    Route::post('/reset_password', [AuthController::class, 'verifyResetPassword'])->name('auth.verifyResetCode.submit');
    Route::post('/change_password', [AuthController::class, 'change_password_forget'])->name('auth.changePassword');

    Route::any('logout', [AuthController::class, 'logout'])->middleware('auth:web')->name('logout');
});

Route::get('/navigation', fn() => view('welcome'))->name('navigation');
Route::get('/', [HomeController::class, 'get_category_with_products'])->name('home');
Route::get('products/{id}/{name?}', [HomeController::class, 'show'])->name('products.show');
Route::get('search', [HomeController::class, 'search'])->name('products.search');


Route::group(['prefix' => 'misc'], function () {
    Route::get('cities', [HomeController::class, 'get_cities'])->name('cities');
    Route::get('area', [HomeController::class, 'get_areas'])->name('areas');
    Route::post('toggle_favourite', [HomeController::class, 'toggle_favourite'])->name('products.favorite')->middleware('auth:web');
    Route::post('create_get_offers', [HomeController::class, 'create_get_offers'])->name('offers');
});

Route::group(['prefix' => 'order'], function () {
    Route::post('/add_to_cart', [CartController::class, 'AddToCart'])->name('addToCart')->middleware('auth:web');
    Route::post('/toggle_cart', [CartController::class, 'toggle_cart'])->name('toggleCart')->middleware('auth:web');
    Route::post('/delete_item', [CartController::class, 'deleteItem'])->name('deleteItem')->middleware('auth:web');
    Route::post('/update_cart', [CartController::class, 'updateCart'])->name('updateWebCart')->middleware('auth:web');
    Route::post('/coupon_check', [CartController::class, 'coupon_check'])->name('couponCheck')->middleware('auth:web');

});

Route::group([], function () {
    Route::post('/create_order', [OrderController::class, 'Create_order'])->name('order.create')->middleware('auth:web');
    Route::post('/create_review/{product_id}', [OrderController::class, 'create_review'])->name('order.review')->middleware('auth:web');
});

Route::group(['prefix' => 'address'], function () {
    Route::post('/create_Address', [AddressController::class, 'create_Address'])->name('create_Address')->middleware('auth:web');
    Route::post('/update_address/{id}', [AddressController::class, 'update_address'])->name('update_address')->middleware('auth:web');
    Route::post('/delete_address/{id}', [AddressController::class, 'delete_address'])->name('delete_address')->middleware('auth:web');
});

Route::group(['prefix' => 'general'], function () {
    Route::get('/contact_us', [IndexController::class, 'contact_us'])->name('contactUs');
    Route::post('/contact_us', [IndexController::class, 'submit_contact_us'])->name('general.contactUs.submit');
    Route::get('/send_verification_email', [IndexController::class, 'send_verification_email'])->name('general.sendVerificationEmail');
    Route::get('/download_file/{product_id}', [IndexController::class, 'download_product_guide'])->name('general.downloadProductGuide');

});
Route::group([ 'middleware'=>['web'],'prefix' => 'socialLogin'], function () {
    Route::get('auth/{provider_name}', [SocialController::class, 'socialAuth'])->name('loginWithFacbook');
    Route::get('auth/{provider_name}/callback', [SocialController::class, 'social_login'])->name('socialLoginCallback');

});

Route::get('/profile', [IndexController::class, 'profile'])->name('profile');
Route::get('/address', [IndexController::class, 'address'])->name('address');
Route::get('/address/{id}/edit', [IndexController::class, 'update_address'])->name('updateaddress');
Route::get('/forms', [IndexController::class, 'forms'])->name('forms');
Route::get('/change_pass', [IndexController::class, 'changePass'])->name('changePass')->middleware('auth:web');
Route::get('/request', [IndexController::class, 'request'])->name('request')->middleware('auth:web');
Route::get('/perfect', [IndexController::class, 'perfect'])->name('perfect')->middleware('auth:web');
Route::get('/basket', [IndexController::class, 'basket'])->name('basket')->middleware('auth:web');
Route::get('/pay/{address_id}', [IndexController::class, 'pay'])->name('pay')->middleware('auth:web');

Route::any('locale/{locale}', function ($locale) {
    session()->put('locale', $locale);
    return request()->header('Referer') ? redirect(request()->header('Referer')) : redirect()->back();
})->name('change_locale');


Route::get('/page/{page}', function (\App\Models\Page $page) {
    return view('pages.page', ['page' => $page]);
})->name('pages.show');
