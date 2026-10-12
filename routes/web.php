<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\ShopController;
use App\Support\RoleRedirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Language Switch

Route::get('lang/{locale}', function ($locale) {

    if (! in_array($locale, ['en', 'mm'])) {
        abort(400);
    }

    // Store in session (for guests & immediate use)
    session(['locale' => $locale]);
    app()->setLocale($locale);

    // Persist for logged-in users
    if (Auth::check()) {

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update([
            'locale' => $locale,
        ]);
    }

    return redirect()->back();

})->middleware('bypass')->name('lang.switch');

require __DIR__ .'/admin.php';
require __DIR__ .'/user.php';


Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'user' => redirect()->route('userHome'),
            'admin', 'superadmin' => redirect()->route('adminDashboard'),
            default => redirect()->route('login'),
        };
    }

    return app(ProductController::class)
        ->homeProducts(request());
})->name('home.products');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])
    ->name('newsletter.subscribe');

Route::post('/contact/send', [ContactController::class, 'store'])
    ->name('contact.send');

Route::view('/contact-us', 'user.pages.contact-us')->name('contact.us');

Route::get('/shop', [ShopController::class, 'index'])
    ->name('shop.index');

Route::get('/product/{product}', [ProductController::class, 'show'])
    ->name('product.show');


Route::get('/dashboard', function () {
    return RoleRedirect::handle();
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Customer OAuth sign-in; staff continue to use password and mandatory MFA.
Route::middleware(['guest:web', 'throttle:30,1'])->group(function () {
    Route::get('/auth/{provider}/redirect', [SocialLoginController::class, 'redirect'])
        ->whereIn('provider', ['google', 'github'])->name('socialLogin');
    Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'callback'])
        ->whereIn('provider', ['google', 'github'])->name('social.callback');
});

Route::post('/review/store', [ReviewController::class, 'store'])->middleware('auth');
Route::post('/review/{id}/helpful', [ReviewController::class, 'helpful'])->middleware('auth');
Route::get('/product/{product}/review-stats', [ProductController::class, 'reviewStats']);

