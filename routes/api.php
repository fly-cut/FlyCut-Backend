<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarberAuthController;
use App\Http\Controllers\BarbershopOwnerAuthController;

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

Route::post('/barber/register', [BarberAuthController::class, 'register']);
Route::post('/barber/login', [BarberAuthController::class, 'login']);

Route::post('/barbershopOwner/register', [BarbershopOwnerAuthController::class, 'register']);
Route::post('/barbershopOwner/login', [BarbershopOwnerAuthController::class, 'login']);

Route::middleware('auth:admin-api')->group(function () {
    Route::post('admin/logout', [AdminAuthController::class, 'logout']);
});

Route::post('admin/login', [AdminAuthController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/barber/logout', [BarberAuthController::class, 'logout']);
    Route::post('/barbershopOwner/logout', [BarbershopOwnerAuthController::class, 'logout']);
});
