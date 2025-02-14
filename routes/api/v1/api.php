<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\API\V1\APIControllerV1;
use App\Http\Controllers\BookingController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login-user', [APIControllerV1::class, 'loginUserAPI']);
Route::middleware('auth:sanctum')->get('/form/{id}', [APIControllerV1::class, 'getForm']);
Route::middleware('auth:sanctum')->get('/bookings', [APIControllerV1::class, 'index']);
Route::middleware('auth:sanctum')->get('/bookings/{id}', [APIControllerV1::class, 'bookingEdit']);
