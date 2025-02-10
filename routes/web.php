<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProfileController;
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

    Route::get('/register', [UserController::class, 'showRegistrationForm']);
    Route::post('/register', [UserController::class, 'register'])->name('register');
    Route::get('/login', [UserController::class, 'showLoginForm']);
    Route::post('/login', [UserController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function () {    
    Route::get('/user', [UserController::class, 'index'])->name('user.list');
    Route::get('/user/add', [UserController::class, 'userAdd'])->name('user.add');
    Route::post('/user/save', [UserController::class, 'userSave'])->name('user.save');
    Route::get('/user/edit/{id}', [UserController::class, 'userEdit'])->name('user.edit');
    Route::post('/user/update/{id}', [UserController::class, 'userUpdate'])->name('user.update');
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
