<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
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
    return redirect()->route('dashboard');
});

// Guest routes (not logged in)
Route::middleware('guest')->group(function () {
    Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('registration.form');
    Route::post('/register', [UserController::class, 'register'])->name('register');
    Route::get('/login', [UserController::class, 'showLoginForm'])->name("login.form");
    Route::post('/login', [UserController::class, 'login'])->name('login');
    Route::get('/forgot-password', [UserController::class, 'forgotPassword'])->name('password.request');
    Route::post('password/email', [UserController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [UserController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [UserController::class, 'reset'])->name('password.update');
});

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Routes for editing (edit users, edit forms, etc.)
    Route::middleware('permission:view users')->group(function () {
        Route::get('/user', [UserController::class, 'index'])->name('user.list');
    });
    Route::middleware('permission:create users')->group(function () {
        Route::get('/user/add', [UserController::class, 'userAdd'])->name('user.add');
        Route::post('/user/save', [UserController::class, 'userSave'])->name('user.save');
    });

    Route::middleware('permission:edit users')->group(function () {
        Route::get('/user/edit/{id}', [UserController::class, 'userEdit'])->name('user.edit');
        Route::post('/user/update/{id}', [UserController::class, 'userUpdate'])->middleware('permission:view')->name('user.update');
    });

    Route::middleware('permission:delete users')->group(function () {
        Route::delete('/user/delete/{userid}', [UserController::class, 'userDelete'])->name('user.delete');
    });
  


    Route::middleware('permission:view forms')->group(function () {
        Route::get('/form', [FormController::class, 'index'])->name('form.list');
    });

    Route::middleware('permission:create forms')->group(function () {
        Route::get('/form/add', [FormController::class, 'formAdd'])->name('form.add');
        Route::post('/form/save', [FormController::class, 'formSave'])->name('form.save');
    });

    Route::middleware('permission:edit forms')->group(function () {
        Route::get('/form/{formid}/edit', [FormController::class, 'formEdit'])->name('form.edit');
    });
    Route::middleware('permission:delete forms')->group(function () {
        Route::delete('/form/{formid}/delete', [FormController::class, 'formDelete'])->name('form.delete');
    });

    // Routes for viewing data (view users, view forms, etc.)
    Route::middleware('permission:view bookings')->group(function () {
        Route::get('/bookings', [BookingController::class, 'index'])->name('booking.list');
    });

    Route::middleware('permission:create bookings')->group(function () {
        Route::get('/booking/add', [BookingController::class, 'bookingAdd'])->name('booking.add');
        Route::post('/booking/save', [BookingController::class, 'bookingSave'])->name('booking.save');
    });


    Route::middleware('permission:edit bookings')->group(function () {
        Route::get('/booking/edit/{id}', [BookingController::class, 'bookingEdit'])->name('booking.edit');
        Route::post('/booking/update/{id}', [BookingController::class, 'bookingUpdate'])->name('booking.update');
    });


    Route::middleware('permission:delete bookings')->group(function () {
        Route::delete('/booking/delete/{id}', [BookingController::class, 'bookingDelete'])->name('booking.delete');
    });


    Route::middleware('permission:view roles')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.list');
    });

    Route::middleware('permission:create roles')->group(function () {
        Route::get('/roles/add', [RoleController::class, 'roleAdd'])->name('roles.add');
        Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
    });

    Route::middleware('permission:edit roles')->group(function () {
        Route::get('/roles/edit/{id}', [RoleController::class, 'roleEdit'])->name('roles.edit');
        Route::put('/roles/update/{id}', [RoleController::class, 'roleUpdate'])->name('roles.update');
    });
    Route::middleware('permission:delete roles')->group(function () {
        Route::delete('/roles/delete/{id}', [RoleController::class, 'roleDelete'])->name('roles.delete');
    });

    Route::get('/profile', [UserController::class, 'userEdit'])->name('profile');
    Route::post('/user/update/{id}', [UserController::class, 'userUpdate'])->name('user.update');

    // General routes (dashboard, logout, etc.)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/todo', [UserController::class, 'todo'])->name('todo');
    Route::get('/welcome', [UserController::class, 'welcome']);
    Route::get('/userrole', [UserController::class, 'userrole']);
});
