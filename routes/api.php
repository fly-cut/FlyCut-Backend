<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\BarberAuthController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\BarbershopOwnerAuthController;
use App\Http\Controllers\BarbershopOwnerController;
use App\Http\Controllers\ClientController;
use App\Models\BarbershopOwner;

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

Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function () {
    Route::post('admin/logout', [AdminAuthController::class, 'logout']);
});


Route::post('/barbershopOwner/register', [BarbershopOwnerAuthController::class, 'register']);
Route::post('/barbershopOwner/login', [BarbershopOwnerAuthController::class, 'login']);
Route::post('/resend/email/token', [BarbershopOwnerAuthController::class, 'resendPin']);

Route::get('/login/{provider}', [BarbershopOwnerAuthController::class, 'redirectToProvider']);
Route::get('/login/{provider}/callback', [BarbershopOwnerAuthController::class, 'handleProviderCallback']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('barbershopOwner/logout', [BarbershopOwnerAuthController::class, 'logout']);
});


Route::post('client/register', [ClientAuthController::class, 'register']);
Route::post('client/login', [ClientAuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'type.client'])->group(function () {
    Route::post('client/logout', [ClientAuthController::class, 'logout']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    // Route::post('email/verify',[BarbershopOwnerAuthController::class, 'verifyEmail']);

    // Route::middleware('verify.api')->group(function () {

    // });


});