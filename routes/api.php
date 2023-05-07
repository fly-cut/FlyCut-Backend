<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\BarberController;
use App\Http\Controllers\BarbershopController;
use App\Http\Controllers\BarbershopOwnerAuthController;
use App\Http\Controllers\BarbershopOwnerController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\VariationController;
use App\Http\Controllers\BarberRatingController;
use App\Http\Controllers\BarbershopRatingController;
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

Route::post('admin/register', [AdminAuthController::class, 'register']);
Route::post('admin/login', [AdminAuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('admin/logout', [AdminAuthController::class, 'logout']);
    Route::post('services', [ServiceController::class, 'store']);
    Route::put('services/{id}', [ServiceController::class, 'update']);
    Route::delete('services/{id}', [ServiceController::class, 'destroy']);

    Route::post('variations', [VariationController::class, 'store']);
    Route::put('variations/{id}', [VariationController::class, 'update']);
    Route::delete('variations/{id}', [VariationController::class, 'destroy']);
});

Route::group(['prefix' => 'barbershopOwner/'], function () {
    Route::post('register', [BarbershopOwnerAuthController::class, 'register']);
    Route::post('login', [BarbershopOwnerAuthController::class, 'login']);
    Route::post('/resend/email/token', [BarbershopOwnerAuthController::class, 'resendPin']);

    Route::get('login/{provider}', [BarbershopOwnerAuthController::class, 'redirectToProvider']);
    Route::get('login/{provider}/callback', [BarbershopOwnerAuthController::class, 'handleProviderCallback']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', [BarbershopOwnerAuthController::class, 'logout']);
        Route::put('changePassword', [BarbershopOwnerController::class, 'changePassword']);
        Route::put('updateProfile', [BarbershopOwnerController::class, 'updateProfile']);
    });
});

Route::group(['prefix' => 'client/'], function () {
    Route::post('register', [ClientAuthController::class, 'register']);
    Route::post('login', [ClientAuthController::class, 'login']);
    Route::get('login/{provider}', [ClientAuthController::class, 'redirectToProvider']);
    Route::get('login/{provider}/callback', [ClientAuthController::class, 'handleProviderCallback']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::put('changePassword', [ClientController::class, 'changePassword']);
        Route::put('updateProfile', [ClientController::class, 'updateProfile']);
    });
});
Route::group(['prefix' => 'barbers/'], function () {
    Route::get('{id}', [BarberController::class, 'getBarbersOfBarbershop']);
    Route::post('', [BarberController::class, 'store']);
    Route::delete('{barber}', [BarberController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'type.client'])->group(function () {
    Route::post('client/logout', [ClientAuthController::class, 'logout']);
});

Route::group(['prefix' => 'barbershops/', 'middleware' => ['auth:sanctum']], function () {
    Route::get('', [BarbershopController::class, 'indexBarbershop']);
    Route::post('', [BarbershopController::class, 'addBarbershop']);
    Route::get('{barbershop_id}', [BarbershopController::class, 'showBarbershop']);
    Route::put('{barbershop_id}', [BarbershopController::class, 'updateBarbershop']);
    Route::delete('{barbershop_id}', [BarbershopController::class, 'destroyBarbershop']);

    Route::post('{barbershop_id}/services', [BarbershopController::class, 'addServicesToBarbershop']);
    Route::delete('{barbershop_id}/services/{service_id}', [BarbershopController::class, 'removeServiceFromBarbershop']);
    Route::put('{barbershop_id}/services/{service_id}', [BarbershopController::class, 'editServicePriceAndSlots']);
    Route::get('{barbershop_id}/services', [BarbershopController::class, 'getBarbershopServicesWithPriceAndSlots']);

});

Route::group(['prefix' => 'services/', 'middleware' => ['auth:sanctum']], function () {
    Route::get('', [ServiceController::class, 'index']);
    Route::get('{id}', [ServiceController::class, 'show']);
    Route::get('{service_id}/variations', [ServiceController::class, 'getServiceVariations']);
});

Route::group(['prefix' => 'variations/', 'middleware' => ['auth:sanctum']], function () {
    Route::get('', [VariationController::class, 'index']);
    Route::get('{id}', [VariationController::class, 'show']);
});

Route::group(['prefix' => 'barber-ratings/', 'middleware' => ['auth:sanctum']], function () {
    Route::post('', [BarberRatingController::class, 'store']);
    Route::put('{id}', [BarberRatingController::class, 'update']);
    Route::delete('{id}', [BarberRatingController::class, 'destroy']);
    Route::get('{id}', [BarberRatingController::class, 'getRatings']);
});

Route::group(['prefix' => 'barbershop-ratings/', 'middleware' => ['auth:sanctum']], function () {
    Route::post('', [BarbershopRatingController::class, 'store']);
    Route::put('{id}', [BarbershopRatingController::class, 'update']);
    Route::delete('{id}', [BarbershopRatingController::class, 'destroy']);
    Route::get('{id}', [BarbershopRatingController::class, 'getRatings']);
});


Route::group(['middleware' => ['auth:sanctum']], function () {
    // Route::post('email/verify',[BarbershopOwnerAuthController::class, 'verifyEmail']);

    // Route::middleware('verify.api')->group(function () {

    // });
});
