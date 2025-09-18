<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\BookingTemplateController;
use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\admin\BookingController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\frontend\FormController;
use App\Http\Controllers\frontend\SubscriptionsController;
use App\Http\Controllers\admin\ServiceController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\EnquiryController;
use App\Http\Controllers\frontend\BookingListingController;
use App\Http\Controllers\frontend\VendorListingController;
use App\Http\Controllers\admin\StaffController;
use App\Http\Controllers\admin\VendorController;
use App\Http\Controllers\admin\SettingsController;
use App\Http\Controllers\admin\EmailTemplateController;
use App\Http\Controllers\frontend\Vendor\VendorInformationController;
use App\Http\Controllers\frontend\Vendor\VendorProfileController;
use App\Http\Controllers\frontend\Vendor\VendorBookingController;
use App\Http\Controllers\frontend\Vendor\VendorServiceController;
use App\Http\Controllers\frontend\Vendor\VendorStaffController;
use App\Http\Controllers\export\ExportBookingController;
use App\Helpers\Shortcode;
use App\Http\Controllers\admin\SubscriptionController;
use App\Http\Controllers\export\ExportStaffController;
use App\Http\Controllers\export\ExportUserController;
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
Route::get('/vendor/{id}', [VendorListingController::class, 'listing'])->name('vendor.show');
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
        Route::get('/users', [UserController::class, 'index'])->name('user.list');
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
        Route::post('/user/bulk-delete', [UserController::class, 'bulkDelete'])->name('user.bulk-delete');
    });
    Route::middleware('permission:view templates')->group(function () {
        Route::get('/templates', [BookingTemplateController::class, 'index'])->name('template.list');
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
        Route::post('/template/bulk-delete', [BookingTemplateController::class, 'bulkDelete'])->name('template.bulk-delete'); // Bulk Delete

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
        Route::get('/booking/{id}/view', [BookingController::class, 'bookingview'])->name('booking.view');
        Route::put('/booking/{id}/update', [BookingController::class, 'bookingUpdate'])->name('booking.update');
    });
    Route::middleware('permission:delete bookings')->group(function () {
        Route::delete('/booking/{id}/delete', [BookingController::class, 'bookingDelete'])->name('booking.delete');
        Route::post('/booking/bulk-delete', [BookingController::class, 'bulkDelete'])->name('booking.bulk-delete');
    });
    Route::middleware('permission:view roles')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.list');
    });
    Route::middleware('permission:create roles')->group(function () {
        Route::get('/role/add', [RoleController::class, 'roleAdd'])->name('roles.add');
        Route::post('/role/store', [RoleController::class, 'store'])->name('roles.store');
    });
    Route::middleware('permission:edit roles')->group(function () {
        Route::get('/role/{id}/edit', [RoleController::class, 'roleEdit'])->name('roles.edit');
        Route::put('/role/{id}/update', [RoleController::class, 'roleUpdate'])->name('roles.update');
    });
    Route::middleware('permission:delete roles')->group(function () {
        Route::delete('/roles/{id}/delete', [RoleController::class, 'roleDelete'])->name('roles.delete');
        Route::post('/roles/bulk-delete', [RoleController::class, 'bulkDelete'])->name('roles.bulk-delete');
    });
    Route::middleware('permission:edit services')->group(function () {
        Route::get('/service/{service}/edit', [ServiceController::class, 'serviceEdit'])->name('service.edit');
        Route::put('/service/{service}', [ServiceController::class, 'serviceUpdate'])->name('service.update');
    });
    Route::middleware('permission:view services')->group(function () {
        Route::get('/services', [ServiceController::class, 'index'])->name('service.list');
    });
    Route::middleware('permission:create services')->group(function () {
        Route::get('/service/add', [ServiceController::class, 'serviceAdd'])->name('service.add');
        Route::post('/service/store', [ServiceController::class, 'servicestore'])->name('service.store');
    });
    Route::middleware('permission:delete services')->group(function () {
        Route::delete('/service/{service}/delete', [ServiceController::class, 'destroy'])->name('service.delete');
        Route::post('/service/bulk-delete', [ServiceController::class, 'bulkDelete'])->name('service.bulk-delete'); // Bulk Delete
    });

    Route::middleware('permission:view vendors')->group(function () {
        Route::get('/vendors', [VendorController::class, 'index'])->name('vendors.list');
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
        Route::post('/vendor/bulk-delete', [VendorController::class, 'bulkDelete'])->name('vendors.bulk-delete');
    });

    Route::middleware('permission:view categories')->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('category.list');
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
        Route::post('/category/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('category.bulk-delete');
    });

    Route::middleware('permission:view emails')->group(function () {
        Route::get('/emails', [EmailTemplateController::class, 'index'])->name('emails.list');
    });

    Route::middleware('permission:create emails')->group(function () {
        Route::get('/email/add', [EmailTemplateController::class, 'create'])->name('emails.create');
        Route::post('/email/store', [EmailTemplateController::class, 'store'])->name('emails.store');
    });

    Route::middleware('permission:edit emails')->group(function () {
        Route::get('/email/{email}/edit', [EmailTemplateController::class, 'edit'])->name('emails.edit');
        Route::put('/email/{email}', [EmailTemplateController::class, 'update'])->name('emails.update');
    });
    Route::middleware('permission:delete emails')->group(function () {
        Route::delete('/emails/{emails}', [EmailTemplateController::class, 'destroy'])->name('emails.destroy');
        Route::post('/emails/bulk-delete', [EmailTemplateController::class, 'bulkDelete'])->name('emails.bulk-delete');
    });
    Route::middleware('permission:view staffs')->group(function () {
        Route::get('/staffs', [StaffController::class, 'index'])->name('staff.list');
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
        Route::post('/staff/bulk-delete', [StaffController::class, 'bulkDelete'])->name('staff.bulk-delete');
    });
    Route::middleware('permission:access settings')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings/store', [SettingsController::class, 'store'])->name('settings.store');
    });

    Route::middleware('permission:view subscriptions')->group(function () {
        Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscription.list');
    });

    Route::middleware('permission:delete subscriptions')->group(function () {
        Route::delete('/subscription/{id}', [SubscriptionController::class, 'destroy'])->name('subscription.destroy');
        Route::post('/subscriptions/bulk-delete', [SubscriptionController::class, 'bulkDelete'])->name('subscription.bulk-delete');
    });

    Route::get('/enquires', [EnquiryController::class, 'index'])->name('enquiry.list');
    Route::delete('/enquires/{id}', [EnquiryController::class, 'destroy'])->name('enquiry.destroy');
    Route::post('/enquires/bulk-delete', [EnquiryController::class, 'bulkDelete'])->name('enquiry.bulk-delete');


    Route::get('/profile', [UserController::class, 'userEdit'])->name('profile');
    Route::post('/subscribe', [UserController::class, 'subscribe'])->name('subscribe.send');
    Route::post('/user/{id}/update', [UserController::class, 'userUpdate'])->name('user.update');
    // General routes (dashboard, logout, etc.)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/todo', [UserController::class, 'todo'])->name('todo');
    Route::get('/welcome', [UserController::class, 'welcome']);
    Route::get('/userrole', [UserController::class, 'userrole']);
});
Route::post('/subscription', [SubscriptionsController::class, 'index'])->name('subscriptions.index');

// Front profile 
Route::get('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/user/changepassword', [UserController::class, 'changePassword'])->name('changepassword');
Route::post('/user/changepassword', [UserController::class, 'updatePassword'])->name('changepassword.update');

Route::post('/store/session', [FormController::class, 'storeSession'])->name('session.store');
Route::get('/get/session', [FormController::class, 'getSession'])->name('session.get');
Route::post('/form/session/destroyed', [FormController::class, 'sessiondestroy'])->name('session.destryoed');

// Route::get('/profile', [UserProfileController::class, 'userEdit'])->name('Userprofile');
Route::post('/profile/update', [VendorProfileController::class, 'UserprofileUpdate'])->name('ProfileUpdate');
Route::middleware(['VendorRoleCheck'])->group(function () {

    Route::get('/dashboard/profile', [VendorInformationController::class, 'view'])->middleware('VendorRoleCheck')->name('vendor.dashboard.view');

    // Bookings
    Route::get('/dashboard/bookings', [VendorBookingController::class, 'view'])->name('vendor.bookings.view');
    Route::get('/bookings/view/{id}', [VendorBookingController::class, 'bookingview'])->name('bookings.view');
    Route::delete('/bookings/{id}', [VendorBookingController::class, 'bookingdestroy'])->name('vendor.booking.destroy');
    //  Services 
    Route::get('/dashboard/services', [VendorServiceController::class, 'view'])->name('vendor.services.view');
    Route::get('/dashboard/services/add', [VendorServiceController::class, 'add'])->name('vendor.services.add');
    Route::get('/dashboard/services/{id}/edit', [VendorServiceController::class, 'edit'])->name('vendor.services.edit');

    Route::post('/services', [VendorServiceController::class, 'ServiceCreate'])->name('vendor.services.store');

    Route::put('/services/{id}', [VendorServiceController::class, 'ServiceUpdate'])->name('vendor.services.update');
    Route::delete('/services/{id}', [VendorServiceController::class, 'Servicedestroy'])->name('vendor.services.destroy');

    //  Staff
    Route::get('/dashboard/staff', [VendorStaffController::class, 'view'])->name('vendor.staff.view');
    Route::get('/dashboard/staff/add', [VendorStaffController::class, 'add'])->name('vendor.staff.add');
    Route::post('/dashboard/staff', [VendorStaffController::class, 'staffCreate'])->name('vendor.staff.store');
    Route::get('/dashboard/staff/edit/{id}', [VendorStaffController::class, 'edit'])->name('vendor.staff.edit');
    Route::put('/staff/{id}', [VendorStaffController::class, 'staffUpdate'])->name('vendor.staff.update');
    Route::delete('/staff/{id}', [VendorStaffController::class, 'staffDestroy'])->name('vendor.staff.destroy');
});
Route::get('/export/bookings', [ExportBookingController::class, 'exportBookings'])->name('export.booking.excel');
Route::get('/export/staff', [ExportStaffcontroller::class, 'exportstaff'])->name('export.staff.excel');
Route::get('/export/user', [ExportUserController::class, 'exportuser'])->name('export.user.excel');


Route::get('/email/logs', function () {
    $logPath = storage_path('logs/laravel.log');
    if (!File::exists($logPath)) {
        return response('Log file not found.', 404);
    }
    $logContents = File::get($logPath);
    return response("<pre>$logContents</pre>");
});
Route::get('/check-smtp', function () {
    $smtp = config('mail.mailers.smtp');

    if ($smtp['host'] && $smtp['username'] && $smtp['password']) {
        return $smtp;
    }

    return 'SMTP is NOT properly configured.';
});
