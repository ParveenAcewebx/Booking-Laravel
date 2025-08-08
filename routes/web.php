<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\BookingTemplateController;
use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\admin\BookingController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\frontend\FormController;
use App\Http\Controllers\admin\ServiceController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\frontend\BookingListingController;
use App\Http\Controllers\admin\StaffController;
use App\Http\Controllers\admin\VendorController;
use App\Http\Controllers\admin\SettingsController;
use App\Http\Controllers\frontend\UserProfileController;
use App\Helpers\Shortcode;

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
    return view('frontend.landing');
})->name('home');

Route::get('/booking', [BookingListingController::class, 'listing'])->name('booking.listing');
Route::get('/admin', function () {
    return redirect()->route('dashboard');
});
Route::get('/admin/shortcodes/list', function () {
    return response()->json(Shortcode::getRegisteredShortcodes());
});
Route::get('/form/{slug}', [FormController::class, 'show'])->name('form.show');
Route::post('/form/{slug}', [FormController::class, 'store'])->name('form.store');
Route::get('/get/services/staff', [FormController::class, 'getservicesstaff'])->name('get.services.staff');
Route::get('/get/vendor/get_booking_calender', [FormController::class, 'getBookingCalender'])->name('service.vendor.calender');
Route::get('/get/slotbooked', [FormController::class, 'getBookingSlot'])->name('service.slotbooked');


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
Route::prefix('admin')->middleware(['auth', 'checkCustomerRole'])->group(function () {
    Route::post('/{id}/switch', [UserController::class, 'switchUser'])->name('user.switch');
    Route::post('/switch-back', [UserController::class, 'switchBack'])->name('user.switch.back');
    Route::get('/booking/load-template-html/{id}', [BookingController::class, 'loadTemplateHTML']);
    Route::get('/get/copytemplateid', [BookingTemplateController::class, 'copytemplate']);
    // Routes for editing (edit users, edit templates, etc.)
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
    Route::middleware('permission:view templates')->group(function () {
        Route::get('/template', [BookingTemplateController::class, 'index'])->name('template.list');
    });
    Route::middleware('permission:create templates')->group(function () {
        Route::get('/template/add', [BookingTemplateController::class, 'templateAdd'])->name('template.add');
        Route::post('/template/save', [BookingTemplateController::class, 'templateSave'])->name('template.save');
    });
    Route::middleware('permission:edit templates')->group(function () {
        Route::get('/template/{formid}/edit', [BookingTemplateController::class, 'templateEdit'])->name('template.edit');
    });
    Route::middleware('permission:delete templates')->group(function () {
        Route::delete('/template/{formid}/delete', [BookingTemplateController::class, 'templateDelete'])->name('template.delete');
    });
    // Routes for viewing data (view users, view templates, etc.)
    Route::middleware('permission:view bookings')->group(function () {
        Route::get('/bookings', [BookingController::class, 'index'])->name('booking.list');
    });
    Route::middleware('permission:create bookings')->group(function () {
        Route::get('/booking/add', [BookingController::class, 'bookingAdd'])->name('booking.add');
        Route::post('/booking/save', [BookingController::class, 'bookingSave'])->name('booking.save');
    });
    Route::middleware('permission:edit bookings')->group(function () {
        Route::get('/booking/{id}/edit', [BookingController::class, 'bookingEdit'])->name('booking.edit');
        Route::put('/booking/{id}/update', [BookingController::class, 'bookingUpdate'])->name('booking.update');
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
    Route::middleware('permission:edit services')->group(function () {
        Route::get('/service/{service}/edit', [ServiceController::class, 'serviceEdit'])->name('service.edit');
        Route::put('/service/{service}', [ServiceController::class, 'serviceUpdate'])->name('service.update');
    });
    Route::middleware('permission:view services')->group(function () {
        Route::get('/service', [ServiceController::class, 'index'])->name('service.list');
    });
    Route::middleware('permission:create services')->group(function () {
        Route::get('/service/add', [ServiceController::class, 'serviceAdd'])->name('service.add');
        Route::post('/service/store', [ServiceController::class, 'servicestore'])->name('service.store');
    });
    Route::middleware('permission:delete services')->group(function () {
        Route::delete('/service/{service}/delete', [ServiceController::class, 'destroy'])->name('service.delete');
    });

    Route::middleware('permission:view vendors')->group(function () {
        Route::get('/vendor', [VendorController::class, 'index'])->name('vendors.list');
    });
    Route::middleware('permission:create vendors')->group(function () {
        Route::get('/vendor/add', [VendorController::class, 'add'])->name('vendors.add');
        Route::post('/vendor/save', [VendorController::class, 'store'])->name('vendors.save');
    });
    Route::middleware('permission:edit vendors')->group(function () {
        Route::get('/vendor/{formid}/edit', [VendorController::class, 'edit'])->name('vendors.edit');
        Route::put('/vendor/{vendor}', [VendorController::class, 'update'])->name('vendors.update');
        Route::get('/vendors/{staffId}/services', [VendorController::class, 'getStaffServices'])
            ->name('vendors.services');
    });
    Route::middleware('permission:delete vendors')->group(function () {
        Route::delete('/vendor/{id}/delete', [VendorController::class, 'destroy'])->name('vendors.delete');
    });

    Route::middleware('permission:view categories')->group(function () {
        Route::get('/category', [CategoryController::class, 'index'])->name('category.list');
    });
    Route::middleware('permission:create categories')->group(function () {
        Route::get('/category/add', [CategoryController::class, 'create'])->name('category.create');
        Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
    });
    Route::middleware('permission:edit categories')->group(function () {
        Route::get('/category/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
        Route::put('/category/{category}', [CategoryController::class, 'update'])->name('category.update');
    });
    Route::middleware('permission:delete categories')->group(function () {
        Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');
    });

    Route::middleware('permission:view staffs')->group(function () {
        Route::get('/staff', [StaffController::class, 'index'])->name('staff.list');
    });
    Route::middleware('permission:create staffs')->group(function () {
        Route::get('/staff/add', [StaffController::class, 'add'])->name('staff.create');
        Route::post('/staff/store', [StaffController::class, 'store'])->name('staff.store');
    });
    Route::middleware('permission:edit staffs')->group(function () {
        Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit');
        Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
    });
    Route::middleware('permission:delete staffs')->group(function () {
        Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
    });
    Route::middleware('permission:access settings')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings/store', [SettingsController::class, 'store'])->name('settings.store');
    });

    Route::get('/profile', [UserController::class, 'userEdit'])->name('profile');
    Route::post('/user/{id}/update', [UserController::class, 'userUpdate'])->name('user.update');
    // General routes (dashboard, logout, etc.)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/todo', [UserController::class, 'todo'])->name('todo');
    Route::get('/welcome', [UserController::class, 'welcome']);
    Route::get('/userrole', [UserController::class, 'userrole']);
});
// Front profile 
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/profile', [UserProfileController::class, 'userEdit'])->name('Userprofile');
Route::post('/profile/update', [UserProfileController::class, 'UserUpdate'])->name('ProfileUpdate');
