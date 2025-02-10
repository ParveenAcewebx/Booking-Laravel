<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FormController;
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

// Route::get('/check-permission', [UserController::class, 'permissionCheck'])->middleware("permission:edit articles");


Route::middleware('auth')->group(function () {
    
    // Route::get('/section', [UserController::class, 'innersection']);
    // Route::post('/section', [UserController::class, 'userdeails'])->name('section');
    
    Route::get('/user', [UserController::class, 'index'])->name('user.list');
    Route::get('/user/add', [UserController::class, 'userAdd'])->name('user.add');
    Route::post('/user/save', [UserController::class, 'userSave'])->name('user.save');
    Route::delete('/user/delete/{userid}', [UserController::class, 'userDelete'])->name('user.delete');
    Route::get('/home', [UserController::class, 'home'])->name('home');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/todo', [UserController::class, 'todo'])->name('todo');
    
    Route::get('/form', [FormController::class, 'index'])->name('form.list');
    Route::get('/form/add', [FormController::class, 'formAdd'])->name('form.add');
    Route::post('/form/save', [FormController::class, 'formSave'])->name('form.save');
    Route::delete('/form/{formid}/delete', [FormController::class, 'formDelete'])->name('form.delete');
    Route::get('/form/{formid}/edit', [FormController::class, 'formEdit'])->name('form.edit');

});
// Route::any('{catchall}', [UserController::class, 'noFound'])->where('catchall', '.*');
// Route::get('logout', function () {
//     Auth::logout();});