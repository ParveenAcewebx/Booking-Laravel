<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('registration.form');
    Route::post('/register', [UserController::class, 'register'])->name('register');
    Route::get('/login', [UserController::class, 'showLoginForm'])->name("login.form");
    Route::post('/login', [UserController::class, 'login'])->name('login');
    Route::get('/forgot-password', [UserController::class, 'forgotPassword'])->name('password.request');
    Route::post('password/email', [UserController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}',[UserController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [UserController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {

    // Routes requiring 'edit' permission
    Route::middleware('permission:edit')->group(function () {
        Route::get('/user', [UserController::class, 'index'])->name('user.list');
        Route::get('/user/add', [UserController::class, 'userAdd'])->name('user.add');
        Route::post('/user/save', [UserController::class, 'userSave'])->name('user.save');
        Route::get('/user/edit/{id}', [UserController::class, 'userEdit'])->name('user.edit');       
        Route::get('/profile/{id}', [UserController::class, 'userEdit'])->name('profile.edit');
        Route::get('/form', [FormController::class, 'index'])->name('form.list');
        Route::get('/form/add', [FormController::class, 'formAdd'])->name('form.add');
        Route::post('/form/save', [FormController::class, 'formSave'])->name('form.save');
        Route::get('/form/{formid}/edit', [FormController::class, 'formEdit'])->name('form.edit');
        Route::get('/bookings', [BookingController::class, 'index'])->name('booking.list');
        Route::get('/booking/add', [BookingController::class, 'bookingAdd'])->name('booking.add');
        Route::post('/booking/save', [BookingController::class, 'bookingSave'])->name('booking.save');
        Route::get('/booking/edit/{id}', [BookingController::class, 'bookingEdit'])->name('booking.edit');
        Route::post('/booking/update/{id}', [BookingController::class, 'bookingUpdate'])->name('booking.update');
    });

    // Routes requiring 'view' permission (example for profile view)
    Route::middleware('permission:view')->group(function () {
        Route::get('/profile', [UserController::class, 'userEdit'])->name('profile');
        Route::post('/user/update/{id}', [UserController::class, 'userUpdate'])->middleware('permission:view')->name('user.update');
    });

    // Routes requiring 'manage' permission
    Route::middleware('permission:manage')->group(function () {
        Route::delete('/user/delete/{userid}', [UserController::class, 'userDelete'])->name('user.delete');
        Route::delete('/form/{formid}/delete', [FormController::class, 'formDelete'])->name('form.delete');
        Route::delete('/booking/delete/{id}', [BookingController::class, 'bookingDelete'])->name('booking.delete');
    });

    // Other general routes (no specific permissions required)
    Route::get('/home', [UserController::class, 'home'])->middleware('permission:edit|manage|view')->name('home');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/todo', [UserController::class, 'todo'])->name('todo');
    Route::get('/welcome', [UserController::class, 'welcome']);
    Route::get('/userrole', [UserController::class, 'userrole']);
});

