<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminPasswordController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ShippingZoneController;
use App\Http\Controllers\Admin\TaxSettingController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('guest')->group(function () {

    Route::get('login', function () {
        return view('admin.auth.login');
    })->name('admin.login');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('role.login')
    ->name('admin.login.store');

});


Route::prefix('admin')->middleware('guest')->group(function () {

    Route::get('forgot-password', [AdminPasswordController::class, 'forgotPassword'])
        ->name('admin.password.request');

    Route::post('forgot-password', [AdminPasswordController::class, 'sendResetLink'])
        ->name('admin.password.email');

    Route::get('reset-password/{token}', [AdminPasswordController::class, 'showResetForm'])
        ->name('admin.password.reset');

    Route::post('reset-password', [AdminPasswordController::class, 'resetWithToken'])
        ->name('admin.password.update');
});


Route::prefix('admin/password')
    ->middleware('admin')
    ->group(function () {

    Route::get('forgot', [AdminPasswordController::class, 'showOtpRequestForm'])
        ->name('admin.password.otp.request');

    Route::post('send-otp', [AdminPasswordController::class, 'sendOtp'])
        ->name('admin.password.sendOtp');

    Route::get('verify-otp', [AdminPasswordController::class, 'showOtpVerifyForm'])
        ->name('admin.password.otp.verify.form');

    Route::post('verify-otp', [AdminPasswordController::class, 'verifyOtp'])
        ->name('admin.password.otp.verify');

    Route::get('reset', [AdminPasswordController::class, 'showResetWithOtpForm'])
        ->name('admin.password.otp.reset.form');

    Route::post('reset', [AdminPasswordController::class, 'resetWithOtp'])
        ->name('admin.password.resetOtp');

    Route::post('otp/resend', [AdminPasswordController::class, 'resendOtp'])
        ->name('admin.password.otp.resend');
});


Route::post(
    'admin/profile/password',
    [AdminPasswordController::class, 'updatePassword']
)->middleware('admin')->name('admin.password.change');


Route::prefix('admin')->middleware('admin')->group(function () {

    // ===== ADMIN DASHBOARD =====
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('adminDashboard');

    Route::get('dashboard/report', [DashboardController::class, 'generateReport'])
        ->name('admin.dashboard.report');

    // ===== PROFILE =====
    Route::group(['prefix' => 'profile'], function () {

        Route::get('/', [ProfileController::class, 'profile'])
            ->name('admin#profile');

        Route::post('update', [ProfileController::class, 'updateProfile'])
            ->name('admin#profile.update');

        Route::post('avatar', [ProfileController::class, 'updateAvatar'])
            ->name('admin#profile.avatar');

        Route::post('avatar/delete', [ProfileController::class, 'deleteAvatar'])
            ->name('admin#profile.avatar.delete');

        Route::get('sales', [SalesController::class, 'index'])
            ->name('admin.sales');

        // SALES REPORT DOWNLOAD
        Route::get('sales/report', [SalesController::class, 'generateReport'])
            ->name('admin.sales.report');

    });

    // ===== CATEGORY =====
    Route::group(['prefix' => 'category'], function () {

        // VIEW ONLY (ALL ADMINS)
        Route::get('list', [CategoryController::class, 'categoryList'])
            ->name('category#list');

        // WRITE ACTIONS (SUPERADMIN ONLY)
        Route::middleware('superadmin')->group(function () {

            Route::post('create', [CategoryController::class, 'categoryCreate'])
                ->name('category#create');

            Route::put('update/{id}', [CategoryController::class, 'categoryUpdate'])
                ->name('category#update');

            Route::delete('delete/{id}', [CategoryController::class, 'categoryDelete'])
                ->name('category#delete');

        });

    });

    // ===== PRODUCT =====
    Route::prefix('product')->group(function () {

        Route::get('list', [ProductController::class, 'index'])
            ->name('product#list');

        Route::get('create', [ProductController::class, 'create'])
            ->name('product#create.page');

        Route::post('create', [ProductController::class, 'store'])
            ->name('product#create');

        Route::get('edit/{id}', [ProductController::class, 'edit'])
            ->name('product#edit.page');

        Route::put('update/{id}', [ProductController::class, 'update'])
            ->name('product#update');

        Route::delete('delete/{id}', [ProductController::class, 'destroy'])
            ->name('product#delete');

        Route::delete('image/{id}', [ProductController::class, 'deleteImage'])
            ->name('product#image.delete');
    });

    // ===== ORDERS =====
    Route::prefix('orders')->group(function () {

        Route::get('/', [OrderController::class, 'index'])
            ->name('admin.orders');

        Route::get('/{order}', [OrderController::class, 'show'])
            ->name('admin.orders.show');

        Route::post('/{order}/ship', [OrderController::class, 'ship'])
            ->name('admin.orders.ship');

        Route::post('/{order}/approve-payment', [OrderController::class, 'approvePayment'])
            ->name('admin.orders.approvePayment');

        Route::post('/{order}/reject-payment', [OrderController::class, 'rejectPayment'])
            ->name('admin.orders.rejectPayment');

    });

    // ===== MESSAGES =====
    Route::get('/messages', [MessageController::class, 'index'])
        ->name('admin.messages');

    Route::get('/messages/{id}', [MessageController::class, 'show'])
        ->name('admin.messages.show');

    // ===== VIEW USERS =====
    Route::get('/users', [SettingsController::class, 'users'])
            ->name('admin.users');

    // ===== SETTINGS (SUPERADMIN ONLY) =====
    Route::group(['prefix' => 'settings', 'middleware' => 'superadmin'], function () {

        Route::get('/', [SettingsController::class, 'settings'])
            ->name('admin.settings');

        // ===== ADMINS =====
        Route::get('/admins', [SettingsController::class, 'admins'])
        ->name('admin.settings.admins');

        Route::get('/admins/create', [SettingsController::class, 'createAdminPage'])
            ->name('admin.settings.admins.create.page');

        Route::post('/admins/create', [SettingsController::class, 'createAdmin'])
            ->name('admin.settings.admins.create');

        Route::delete('/admins/{id}', [SettingsController::class, 'removeAdmin'])
            ->name('admin.settings.admins.remove');

        // ===== USERS =====
        Route::delete('/users/{id}', [SettingsController::class, 'removeUser'])
            ->name('admin.settings.users.remove');

        Route::get('/users', [SettingsController::class, 'users'])
            ->name('admin.settings.users');

        // ===== SYSTEM SETTINGS =====
        Route::get('/system', [SettingsController::class, 'systemSettingsPage'])
            ->name('admin.settings.system.page');

        Route::post('/system/update', [SettingsController::class, 'updateSystemSettings'])
            ->name('admin.settings.system.update');

        // ===== CURRENCY =====
        Route::get('/currencies', [CurrencyController::class, 'index'])
            ->name('admin.settings.currencies');

        Route::put('/currencies/update/{id}', [CurrencyController::class, 'update'])
            ->name('admin.settings.currencies.update');


        // ===== PAYMENT METHODS =====
        Route::get('/payment-methods', [PaymentMethodController::class, 'index'])
            ->name('admin.settings.payment_methods');

        Route::post('/payment-methods/create', [PaymentMethodController::class, 'store'])
            ->name('admin.settings.payment_methods.create');

        Route::put('/payment-methods/update/{id}', [PaymentMethodController::class, 'update'])
            ->name('admin.settings.payment_methods.update');

        Route::delete('/payment-methods/delete/{id}', [PaymentMethodController::class, 'destroy'])
            ->name('admin.settings.payment_methods.delete');

        Route::get('/shipping', [ShippingZoneController::class,'index'])
            ->name('admin.settings.shipping');

        Route::post('/shipping/create', [ShippingZoneController::class,'store'])
            ->name('admin.settings.shipping.store');

        Route::put('/shipping/{id}', [ShippingZoneController::class,'update'])
            ->name('admin.settings.shipping.update');

        Route::get('/tax', [TaxSettingController::class,'index'])
            ->name('admin.settings.tax');

        Route::post('/tax/update', [TaxSettingController::class,'update'])
            ->name('admin.settings.tax.update');

    });

});
