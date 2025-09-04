<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\APIControllerV1;

// Public route
Route::post('/login-user', [APIControllerV1::class, 'loginUserAPI']);

// Protected routes with Sanctum
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [APIControllerV1::class, 'logout']);
    Route::get('/formById/{id}', [APIControllerV1::class, 'getForm']);
    Route::get('/bookings', [APIControllerV1::class, 'index']);
    Route::get('/bookingsById/{id}', [APIControllerV1::class, 'show']);
    Route::get('/booking-templates', [APIControllerV1::class, 'bookingTemplates']);
    Route::get('/getBookingByVendorId/{id}', [APIControllerV1::class, 'searchBookingByVendorId']);
    Route::get('/getBookingByServiceId/{id}', [APIControllerV1::class, 'searchBookingByServiceId']);
    Route::get('/getBookingByStaffId/{id}', [APIControllerV1::class, 'searchBookingByStaffId']);
    Route::get('/getStaffById/{id}', [APIControllerV1::class, 'searchStaffById']);
});



/* ------ BookingTemplateAPI ---- */