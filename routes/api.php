<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\BarberController;
use App\Http\Controllers\BarbershopController;
use App\Http\Controllers\BarbershopOwnerAuthController;
use App\Http\Controllers\BarbershopOwnerController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HairCutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReservationRatingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\VariationController;
use App\Models\BarbershopOwner;
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
        Route::post('assignToken', [BarbershopOwnerController::class, 'assignToken']);
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
        Route::get('get/reservations', [ClientController::class, 'getReservations']);
        Route::put('changePassword', [ClientController::class, 'changePassword']);
        Route::put('updateProfile', [ClientController::class, 'updateProfile']);
        Route::get('checkBarberAvailability', [ClientController::class, 'checkBarberAvailability']);
    });
});

Route::group(['prefix' => 'barbers/', 'middleware' => 'tri-guard'], function () {
    Route::post('', [BarberController::class, 'store']);
    Route::delete('{barber}', [BarberController::class, 'destroy']);
    Route::put('{barber}', [BarberController::class, 'update']);
});

Route::group(['prefix' => 'barbershops/', 'middleware' => 'auth:barbershopOwner-api'], function () {
    Route::post('', [BarbershopController::class, 'addBarbershop']);
    Route::put('{barbershop_id}', [BarbershopController::class, 'updateBarbershop']);
    Route::delete('{barbershop_id}', [BarbershopController::class, 'destroyBarbershop']);
    Route::post('add/services', [BarbershopController::class, 'addServicesToBarbershop']);
    Route::post('remove/services', [BarbershopController::class, 'removeServicesFromBarbershop']);
    Route::put('edit/services/price/slot', [BarbershopController::class, 'editServicePriceAndSlots']);
    Route::get('get/reservations', [BarbershopController::class, 'getReservations']);
});

Route::group(['prefix' => 'barbershops/', 'middleware' => 'tri-guard'], function () {
    Route::get('get/all', [BarbershopController::class, 'indexBarbershop']);
    Route::get('{barbershop_id}', [BarbershopController::class, 'showBarbershop']);
    Route::get('{barbershop_id}/services', [BarbershopController::class, 'getBarbershopServicesWithPriceAndSlots']);
    Route::get('/{barbershop_id}/barbers', [BarbershopController::class, 'getBarbersOfBarbershop']);
    Route::get('get/slots', [SlotController::class, 'getReservedSlots']);
    Route::post('search', [BarbershopController::class, 'search']);
    Route::post('nearby', [BarbershopController::class, 'getNearbyBarbershops']);
    Route::put('/slots/changeStausToFree', [SlotController::class, 'changeStatusToFree']);
    Route::put('/slots/changeStausToBusy', [SlotController::class, 'changeStatusToBusy']);
    Route::get('slots/get/all', [SlotController::class, 'index']);
});
Route::get('getallbarbers', [BarbershopOwnerController::class, 'index']);
Route::group(['prefix' => 'services/', 'middleware' => 'tri-guard'], function () {
    Route::get('', [ServiceController::class, 'index']);
    Route::get('{id}', [ServiceController::class, 'show']);
    Route::get('{service_id}/variations', [ServiceController::class, 'getServiceVariations']);
    Route::put('{id}', [ServiceController::class, 'update']);
});

Route::group(['prefix' => 'variations/', 'middleware' => 'tri-guard'], function () {
    Route::get('', [VariationController::class, 'index']);
    Route::get('{id}', [VariationController::class, 'show']);
});

Route::group(['prefix' => 'reservation/ratings/', 'middleware' => 'tri-guard'], function () {
    Route::post('', [ReservationRatingController::class, 'store']);
    Route::put('{id}', [ReservationRatingController::class, 'update']);
    Route::delete('{id}', [ReservationRatingController::class, 'destroy']);
    Route::get('barbershop/{id}', [ReservationRatingController::class, 'getBarbershopRatings']);
    Route::get('barber/{id}', [ReservationRatingController::class, 'getBarberRatings']);
    Route::get('get/by/reservation/{id}', [ReservationRatingController::class, 'getRatingByReservationId']);
});

Route::group(['prefix' => 'haircuts/', 'middleware' => 'tri-guard'], function () {
    Route::get('', [HairCutController::class, 'getAllHaircuts']);
    Route::post('haircut/search', [HairCutController::class, 'search']);
});

Route::group(['prefix' => 'payments/'], function () {
    Route::post('get/payment/token', [PaymentController::class, 'pay']);
    Route::get('callback', [PaymentController::class, 'callback']);
});
