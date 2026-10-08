<?php

use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserPasswordController;
use App\Http\Controllers\User\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\StripeController;

Route::prefix('user')
    ->middleware(['auth','verified','user'])
    ->group(function () {

        Route::get('home', [UserController::class,'userHome'])->name('userHome');

        // User Profile
        Route::get('/profile', [ProfileController::class, 'index'])->name('user.profile');

        Route::post('/profile/update', [ProfileController::class, 'update'])
            ->name('user.profile.update');

        Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])
            ->name('user.profile.avatar');

        Route::post('/profile/avatar/delete', [ProfileController::class, 'deleteAvatar'])
            ->name('user.profile.avatar.delete');

        // Wishlist
        Route::get('/wishlist', [WishlistController::class,'index'])
            ->name('user.wishlist');

        Route::delete('/wishlist/clear', [WishlistController::class,'clear'])
            ->name('wishlist.clear');

        Route::get('/wishlist/share-link', [WishlistController::class,'shareLink'])
            ->name('wishlist.share');

        Route::get('/wishlist/grid', [WishlistController::class,'grid'])
            ->name('wishlist.grid');

        // Cart Routes
        Route::get('/cart', [CartController::class,'index'])->name('user.cart');
        Route::post('/cart/add', [CartController::class,'add'])->name('cart.add');
        Route::post('/cart/update', [CartController::class,'update'])->name('cart.update');
        Route::post('/cart/remove', [CartController::class,'remove'])->name('cart.remove');
        Route::post('/cart/clear', [CartController::class,'clear'])->name('cart.clear');

        Route::get('/checkout', [CheckoutController::class, 'index'])
            ->name('user.checkout');

        Route::post('/checkout', [CheckoutController::class, 'placeOrder'])
            ->name('checkout.place');

        Route::get('/orders', [OrderController::class, 'index'])->name('user.orders');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('user.orders.show');
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])
            ->name('user.orders.cancel');

});

Route::post('/wishlist/toggle', [WishlistController::class,'toggle'])
    ->name('wishlist.toggle');

Route::get('/wishlist/public/{user}', [WishlistController::class,'public'])
    ->name('wishlist.public')
    ->middleware('signed');

Route::prefix('user/password')
    ->middleware(['auth','verified','user'])
    ->group(function () {

    Route::post('update', [UserPasswordController::class, 'updatePassword'])
    ->name('user.password.change');

    Route::get('forgot', [UserPasswordController::class, 'showOtpRequestForm'])
        ->name('user.password.otp.request');

    Route::post('send-otp', [UserPasswordController::class, 'sendOtp'])
        ->name('user.password.sendOtp');

    Route::get('verify-otp', [UserPasswordController::class, 'showOtpVerifyForm'])
        ->name('user.password.otp.verify.form');

    Route::post('verify-otp', [UserPasswordController::class, 'verifyOtp'])
        ->name('user.password.otp.verify');

    Route::get('reset', [UserPasswordController::class, 'showResetWithOtpForm'])
        ->name('user.password.otp.reset.form');

    Route::post('reset', [UserPasswordController::class, 'resetWithOtp'])
        ->name('user.password.resetOtp');

    Route::post('resend-otp', [UserPasswordController::class, 'resendOtp'])
        ->name('user.password.otp.resend');
});

Route::prefix('pages')->group(function () {

    Route::view('/', 'user.pages.index')->name('pages.index');

    Route::view('/privacy-policy', 'user.pages.privacy-policy')->name('privacy.policy');
    Route::view('/terms-of-use', 'user.pages.terms-of-use')->name('terms.use');
    Route::view('/sales-refunds', 'user.pages.sales-refunds')->name('sales.refunds');
    Route::view('/why-people-like-us', 'user.pages.why-us')->name('why.us');
    Route::view('/about-us', 'user.pages.about-us')->name('about.us');
    Route::view('/faqs', 'user.pages.faqs')->name('faqs');

});

Route::get('/stripe/success', [StripeController::class, 'success'])
    ->name('stripe.success');

Route::get('/stripe/cancel/{order}', [StripeController::class, 'cancel'])
    ->name('stripe.cancel');

Route::get('/stripe/checkout/{order}', [StripeController::class, 'checkout'])->name('stripe.checkout');
