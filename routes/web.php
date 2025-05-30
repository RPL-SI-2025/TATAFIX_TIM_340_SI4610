<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Tukang\BookingController as TukangBookingController;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PasswordController;
use Spatie\Permission\Middleware\RoleMiddleware;

// Halaman utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Register
Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Login & Logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Service routes
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/category/{category}', [ServiceController::class, 'getServicesByCategory'])->name('services.by-category');
Route::get('/services/popular', [ServiceController::class, 'getPopularServices'])->name('services.popular');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

// Redirect old booking route to services
Route::redirect('/booking', '/services', 301);

Route::middleware(['auth', 'verified'])->group(function () {
    // Booking routes - removed redundant booking.index route
    Route::get('/booking/create/{service}', [BookingController::class, 'create'])->name('booking.create');
    Route::get('/booking/success/{booking}', [BookingController::class, 'success'])->name('booking.success');
    Route::get('/booking-history', [BookingController::class, 'history'])->name('booking.history');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
    
    // Payment routes
    Route::get('/payment/dp/{booking}', [PaymentController::class, 'showDpForm'])->name('payment.dp.form');
    Route::post('/payment/dp/{booking}', [PaymentController::class, 'processDp'])->name('payment.dp.process');
    Route::get('/payment/final/{booking}', [PaymentController::class, 'showFinalForm'])->name('payment.final.form');
    Route::post('/payment/final/{booking}', [PaymentController::class, 'processFinal'])->name('payment.final.process');
    Route::get('/payment/success/{booking}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/status/{booking}', [PaymentController::class, 'status'])->name('payment.status');
});

// Admin routes with admin role middleware
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');

    // User management
    Route::get('/users', [App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users');
    Route::post('/users', [App\Http\Controllers\Admin\AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\AdminController::class, 'deleteUser'])->name('users.delete');
    Route::put('/users/{user}/toggle-status', [App\Http\Controllers\Admin\AdminController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::put('/users/{id}/verify', [App\Http\Controllers\Admin\AdminController::class, 'verifyTukang'])->name('users.verify');

    // Complaint management
    Route::get('/complaints', [App\Http\Controllers\Admin\ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{complaint}', [App\Http\Controllers\Admin\ComplaintController::class, 'show'])->name('complaints.show');
    Route::post('/complaints/{complaint}/validate', [App\Http\Controllers\Admin\ComplaintController::class, 'validate'])->name('complaints.validate');

    // Booking Status
    // Booking management routes (replaced StatusBookingController with AdminBookingController)
    
    // Admin Booking Management
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/assign', [AdminBookingController::class, 'assignForm'])->name('bookings.assign');
    Route::post('/bookings/{booking}/assign', [AdminBookingController::class, 'assignStore'])->name('bookings.assign.store');
    Route::put('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::put('/bookings/{booking}/cancel', [AdminBookingController::class, 'cancelBooking'])->name('bookings.cancel');
    
    // Admin Service Management
    Route::get('/services', [App\Http\Controllers\Admin\ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [App\Http\Controllers\Admin\ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [App\Http\Controllers\Admin\ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit', [App\Http\Controllers\Admin\ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [App\Http\Controllers\Admin\ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [App\Http\Controllers\Admin\ServiceController::class, 'destroy'])->name('services.destroy');
    
    // Admin Category Management
    Route::get('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

    // Admin Payment Management
    Route::get('/payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/validate', [App\Http\Controllers\Admin\PaymentController::class, 'validate'])->name('payments.validate');

});

// Service routes are now defined above with the booking routes

// Profile routes with auth & verified middleware
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/change-password', [PasswordController::class, 'changePasswordForm'])->name('profile.change-password');
    Route::post('/change-password', [PasswordController::class, 'changePassword'])->name('profile.change-password.update');

    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/')->with('success', 'Anda telah berhasil logout');
    })->name('logout');
});

// Forgot password routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->name('password.update');
});

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('pages.auth.verify-email');
    })->name('verification.notice');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Link verifikasi telah dikirim ulang!');
    })->middleware('throttle:6,1')->name('verification.send');
});

Route::middleware(['auth', 'signed'])->get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/')->with('success', 'Email berhasil diverifikasi!');
})->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Link verifikasi telah dikirim ulang!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

//FAQ
Route::get('/faq', function () {
    return view('pages.faq');
})->name('faq');

// Customer Complaint routes with auth & verified, prefix & name group
Route::middleware(['auth', 'verified'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/complaints/create', [App\Http\Controllers\Customer\ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [App\Http\Controllers\Customer\ComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/complaints/success', [App\Http\Controllers\Customer\ComplaintController::class, 'success'])->name('complaints.success'); // Pastikan method success ada di controller
    Route::get('/complaints', [App\Http\Controllers\Customer\ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{id}', [App\Http\Controllers\Customer\ComplaintController::class, 'show'])->name('complaints.show');
});

// Tukang routes with auth & verified, prefix & name group, tukang role middleware
Route::middleware(['auth', 'verified', RoleMiddleware::class . ':tukang'])->prefix('tukang')->name('tukang.')->group(function () {
    // Tukang Booking Management
    Route::get('/bookings', [TukangBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [TukangBookingController::class, 'show'])->name('bookings.show');
    Route::put('/bookings/{booking}/accept', [TukangBookingController::class, 'accept'])->name('bookings.accept');
    Route::put('/bookings/{booking}/reject', [TukangBookingController::class, 'reject'])->name('bookings.reject');
    Route::put('/bookings/{booking}/complete', [TukangBookingController::class, 'complete'])->name('bookings.complete');
});
