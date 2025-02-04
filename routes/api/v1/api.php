<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\API\V1\APIControllerV1;
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


// Route::get('/create-user', [UserController::class, 'createUser']);

Route::post('/login-user', [APIControllerV1::class, 'loginUserAPI']);
Route::middleware('auth:sanctum')->get('/form/{id}', [APIControllerV1::class, 'getForm']);