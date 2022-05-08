<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FavouriteController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\SizeController;
use App\Http\Controllers\Api\Auth\AuthController;
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

Route::apiResource('countries', CountryController::class);
Route::apiResource('cities', CityController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('stores', StoreController::class);
Route::apiResource('items', ItemController::class);
Route::apiResource('brands', BrandController::class);
Route::apiResource('colors', ColorController::class);
Route::apiResource('sizes', SizeController::class);
Route::apiResource('reviews', ReviewController::class);
Route::apiResource('favourites', FavouriteController::class);
Route::apiResource('settings', SettingController::class);

