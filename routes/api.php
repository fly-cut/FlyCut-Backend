<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\BarberController;
use App\Http\Controllers\BarberRatingController;
use App\Http\Controllers\BarbershopController;
use App\Http\Controllers\BarbershopOwnerAuthController;
use App\Http\Controllers\BarbershopOwnerController;
use App\Http\Controllers\BarbershopRatingController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\VariationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('admins/register', [AdminAuthController::class, 'register']);
Route::post('admins/login', [AdminAuthController::class, 'login']);

Route::middleware(['auth:admin-api'])->group(function () {
    Route::post('admins/logout', [AdminAuthController::class, 'logout']);
    Route::post('services', [ServiceController::class, 'store']);
    Route::put('services/{id}', [ServiceController::class, 'update']);
    Route::delete('services/{id}', [ServiceController::class, 'destroy']);

    Route::post('variations', [VariationController::class, 'store']);
    Route::put('variations/{id}', [VariationController::class, 'update']);
    Route::delete('variations/{id}', [VariationController::class, 'destroy']);
});

Route::group(['prefix' => 'barbershopOwners/'], function () {
    Route::post('register', [BarbershopOwnerAuthController::class, 'register']);
    Route::post('login', [BarbershopOwnerAuthController::class, 'login']);
    Route::post('resend/email/pin', [BarbershopOwnerAuthController::class, 'resendPin']);
    Route::post('forgot/password', [BarbershopOwnerAuthController::class, 'forgotPassword']);
    Route::post('verify/pin', [BarbershopOwnerAuthController::class, 'verifyPin']);
    Route::post('reset/password', [BarbershopOwnerAuthController::class, 'resetPassword']);

    Route::middleware(['auth:barbershopOwner-api'])->group(function () {
        Route::post('email/verify', [BarbershopOwnerAuthController::class, 'verifyEmail']);
        Route::post('logout', [BarbershopOwnerAuthController::class, 'logout']);
        Route::put('changePassword', [BarbershopOwnerController::class, 'changePassword']);
        Route::put('updateProfile', [BarbershopOwnerController::class, 'updateProfile']);
        Route::get('get/barbershop', [BarbershopOwnerAuthController::class, 'getBarbershopOfBarbershopOwner']);
    });
});

Route::group(['prefix' => 'clients/'], function () {
    Route::post('register', [ClientAuthController::class, 'register']);
    Route::post('login', [ClientAuthController::class, 'login']);
    Route::get('login/{provider}', [ClientAuthController::class, 'redirectToProvider']);
    Route::get('login/{provider}/callback', [ClientAuthController::class, 'handleProviderCallback']);
    Route::post('resend/email/pin', [ClientAuthController::class, 'resendPin']);
    Route::post('forgot/password', [ClientAuthController::class, 'forgotPassword']);
    Route::post('verify/pin', [ClientAuthController::class, 'verifyPin']);
    Route::post('reset/password', [ClientAuthController::class, 'resetPassword']);

    Route::middleware(['auth:client-api'])->group(function () {
        Route::post('reserve', [ReservationController::class, 'store']);
        Route::post('email/verify', [ClientAuthController::class, 'verifyEmail']);
        Route::post('logout', [ClientAuthController::class, 'logout']);
        Route::put('changePassword', [ClientAuthController::class, 'changePassword']);
        Route::put('updateProfile', [ClientAuthController::class, 'updateProfile']);
    });
});

Route::group(['prefix' => 'barbers/'], function () {
    Route::post('', [BarberController::class, 'store']);
    Route::delete('{barber}', [BarberController::class, 'destroy']);
    Route::put('{barber}', [BarberController::class, 'update']);
});

Route::group(['prefix' => 'barbershops/', 'middleware' => 'auth:barbershopOwner-api'], function () {

    Route::post('', [BarbershopController::class, 'addBarbershop']);
    Route::put('{barbershop_id}', [BarbershopController::class, 'updateBarbershop']);
    Route::delete('{barbershop_id}', [BarbershopController::class, 'destroyBarbershop']);

    Route::post('{barbershop_id}/services', [BarbershopController::class, 'addServicesToBarbershop']);
    Route::delete('{barbershop_id}/services/{service_id}', [BarbershopController::class, 'removeServiceFromBarbershop']);
    Route::put('{barbershop_id}/services/{service_id}', [BarbershopController::class, 'editServicePriceAndSlots']);
});

Route::group(['prefix' => 'barbershops/', 'middleware' => 'tri-guard'], function () {
    Route::get('get/all', [BarbershopController::class, 'indexBarbershop']);
    Route::get('{barbershop_id}', [BarbershopController::class, 'showBarbershop']);
    Route::get('{barbershop_id}/services', [BarbershopController::class, 'getBarbershopServicesWithPriceAndSlots']);
    Route::get('/{barbershop_id}/barbers', [BarbershopController::class, 'getBarbersOfBarbershop']);
    Route::get('get/slots', [SlotController::class, 'getSlots']);
    Route::post('search', [BarbershopController::class, 'search']);
    Route::post('nearby', [BarbershopController::class, 'getNearbyBarbershops']);
});

Route::group(['prefix' => 'services/', 'middleware' => 'tri-guard'], function () {
    Route::get('', [ServiceController::class, 'index']);
    Route::get('{id}', [ServiceController::class, 'show']);
    Route::get('{service_id}/variations', [ServiceController::class, 'getServiceVariations']);
});

Route::group(['prefix' => 'variations/', 'middleware' => 'tri-guard'], function () {
    Route::get('', [VariationController::class, 'index']);
    Route::get('{id}', [VariationController::class, 'show']);
});

Route::group(['prefix' => 'barber/ratings/', 'middleware' => 'tri-guard'], function () {
    Route::post('', [BarberRatingController::class, 'store']);
    Route::put('{id}', [BarberRatingController::class, 'update']);
    Route::delete('{id}', [BarberRatingController::class, 'destroy']);
    Route::get('{id}', [BarberRatingController::class, 'getRatings']);
});

Route::group(['prefix' => 'barbershop/ratings/', 'middleware' => 'tri-guard'], function () {
    Route::post('', [BarbershopRatingController::class, 'store']);
    Route::put('{id}', [BarbershopRatingController::class, 'update']);
    Route::delete('{id}', [BarbershopRatingController::class, 'destroy']);
    Route::get('{id}', [BarbershopRatingController::class, 'getRatings']);
});
