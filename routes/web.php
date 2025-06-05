<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingTemplateController;
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
        Route::post('/{id}/switch', [UserController::class, 'switchUser'])->name('user.switch');
        Route::post('/switch-back', [UserController::class, 'switchBack'])->name('user.switch.back');

    // Routes for editing (edit users, edit forms, etc.)
    Route::middleware('permission:view users')->group(function () {

        Route::get('/user', [UserController::class, 'index'])->name('user.list');
    });
    Route::middleware('permission:create users')->group(function () {
        Route::get('/user/add', [UserController::class, 'userAdd'])->name('user.add');
        Route::post('/user/save', [UserController::class, 'userSave'])->name('user.save');
    });
    Route::middleware('permission:edit users')->group(function () {
        Route::get('/user/{id}/edit', [UserController::class, 'userEdit'])->name('user.edit');
        Route::post('/user/{id}/update', [UserController::class, 'userUpdate'])->middleware('permission:view')->name('user.update');
    });
    Route::middleware('permission:delete users')->group(function () {
        Route::delete('/user/{userid}/delete', [UserController::class, 'userDelete'])->name('user.delete');
    });
    Route::middleware('permission:view forms')->group(function () {
        Route::get('/template', [BookingTemplateController::class, 'index'])->name('template.list');
    });
    Route::middleware('permission:create forms')->group(function () {
        Route::get('/template/add', [BookingTemplateController::class, 'templateAdd'])->name('template.add');
        Route::post('/template/save', [BookingTemplateController::class, 'templateSave'])->name('template.save');
    });
    Route::middleware('permission:edit forms')->group(function () {
        Route::get('/template/{formid}/edit', [BookingTemplateController::class, 'templateEdit'])->name('template.edit');
    });
    Route::middleware('permission:delete forms')->group(function () {
        Route::delete('/template/{formid}/delete', [BookingTemplateController::class, 'templateDelete'])->name('template.delete');
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
        Route::get('/booking/{id}/edit', [BookingController::class, 'bookingEdit'])->name('booking.edit');
        Route::post('/booking/{id}/update', [BookingController::class, 'bookingUpdate'])->name('booking.update');
    });
    Route::middleware('permission:delete bookings')->group(function () {
        Route::delete('/booking/{id}/delete', [BookingController::class, 'bookingDelete'])->name('booking.delete');
    });
    Route::middleware('permission:view roles')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.list');
    });
    Route::middleware('permission:create roles')->group(function () {
        Route::get('/roles/add', [RoleController::class, 'roleAdd'])->name('roles.add');
        Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
    });
    Route::middleware('permission:edit roles')->group(function () {
        Route::get('/roles/{id}/edit', [RoleController::class, 'roleEdit'])->name('roles.edit');
        Route::put('/roles/{id}/update', [RoleController::class, 'roleUpdate'])->name('roles.update');
    });
    Route::middleware('permission:delete roles')->group(function () {
        Route::delete('/roles/{id}/delete', [RoleController::class, 'roleDelete'])->name('roles.delete');
    });
    Route::get('/profile', [UserController::class, 'userEdit'])->name('profile');
    Route::post('/user/{id}/update', [UserController::class, 'userUpdate'])->name('user.update');
    // General routes (dashboard, logout, etc.)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/todo', [UserController::class, 'todo'])->name('todo');
    Route::get('/welcome', [UserController::class, 'welcome']);
    Route::get('/userrole', [UserController::class, 'userrole']);
});
