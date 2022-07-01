<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VatController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\SizeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\FavouriteController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\ShippingMethodController;
use App\Http\Controllers\Api\Auth\VerifyEmailController;
use App\Http\Controllers\Api\Auth\ForgetPasswordController;

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

//Auth routes
Route::prefix('auth')->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login')->name('login');
        Route::post('/register', 'register')->name('register');
        Route::post('/logout', 'logout')->name('logout')->middleware('auth:api');
    });

    Route::get('email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])->name('verification.verify');
    Route::post('email/verify/resend', [VerifyEmailController::class, 'resend'])->name('verification.send');
    Route::post('forgot-password', [ForgetPasswordController::class, 'forget_password'])->middleware('guest');
    Route::post('reset-password', [ForgetPasswordController::class, 'reset_password'])->middleware('guest')->name('reset');
});

// User profile routes
Route::middleware(['auth:api', 'verified'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/edit-profile', [UserController::class, 'editProfile'])->name('edit-profile');
});

//Home Routes
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('search', [HomeController::class, 'search'])->name('search');

Route::apiResource('countries', CountryController::class);
Route::apiResource('cities', CityController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('stores', StoreController::class);
Route::apiResource('items', ItemController::class);
Route::delete('delete-item/{item}/{store}', [ItemController::class, 'deleteItemStore'])->name('delete-item-store');
Route::apiResource('brands', BrandController::class);
Route::apiResource('colors', ColorController::class);
Route::apiResource('sizes', SizeController::class);
Route::apiResource('reviews', ReviewController::class);
Route::apiResource('favourites', FavouriteController::class);
Route::apiResource('settings', SettingController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('payment-methods', PaymentMethodController::class);
Route::apiResource('shipping-methods', ShippingMethodController::class);
Route::apiResource('vats', VatController::class);

// Notifications
Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
Route::post('notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
Route::post('notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
Route::delete('notifications/{notification}/delete', [NotificationController::class, 'destroy'])->name('notifications.delete');
