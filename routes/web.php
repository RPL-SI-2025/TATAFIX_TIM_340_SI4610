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

// Booking routes
Route::get('/booking', [BookingController::class, 'index'])->name('booking.index'); // Bebas akses

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/success/{booking}', [BookingController::class, 'success'])->name('booking.success');
});

// Admin routes with admin role middleware
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.dashboard');

    // User management
    Route::get('/users', [App\Http\Controllers\Admin\AdminController::class, 'users'])->name('admin.users');
    Route::post('/users', [App\Http\Controllers\Admin\AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::put('/users/{user}/toggle-status', [App\Http\Controllers\Admin\AdminController::class, 'toggleStatus'])->name('admin.users.toggle-status');

    // Complaint management
    Route::get('/admin/complaints', [App\Http\Controllers\Admin\ComplaintController::class, 'index'])->name('admin.complaints.index');
    Route::get('/admin/complaints/{complaint}', [App\Http\Controllers\Admin\ComplaintController::class, 'show'])->name('admin.complaints.show');
    Route::post('/admin/complaints/{complaint}/validate', [App\Http\Controllers\Admin\ComplaintController::class, 'validate'])->name('admin.complaints.validate');

    // Booking Status
    Route::get('/admin/status-booking', [App\Http\Controllers\Admin\StatusBookingController::class, 'index'])->name('admin.status-booking');
    Route::get('/admin/status-booking/{id}/edit', [App\Http\Controllers\Admin\StatusBookingController::class, 'edit'])->name('admin.status-booking.edit');
    Route::put('/admin/status-booking/{id}', [App\Http\Controllers\Admin\StatusBookingController::class, 'update'])->name('admin.status-booking.update');
    
    // Admin Service Management
    Route::get('/admin/services', [App\Http\Controllers\Admin\ServiceController::class, 'index'])->name('admin.services.index');
    Route::get('/admin/services/create', [App\Http\Controllers\Admin\ServiceController::class, 'create'])->name('admin.services.create');
    Route::post('/admin/services', [App\Http\Controllers\Admin\ServiceController::class, 'store'])->name('admin.services.store');
    Route::get('/admin/services/{service}/edit', [App\Http\Controllers\Admin\ServiceController::class, 'edit'])->name('admin.services.edit');
    Route::put('/admin/services/{service}', [App\Http\Controllers\Admin\ServiceController::class, 'update'])->name('admin.services.update');
    Route::delete('/admin/services/{service}', [App\Http\Controllers\Admin\ServiceController::class, 'destroy'])->name('admin.services.destroy');
});

// Service routes
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');

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

// Customer Complaint routes with auth & verified, prefix & name group
Route::middleware(['auth', 'verified'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/complaints/create', [App\Http\Controllers\Customer\ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [App\Http\Controllers\Customer\ComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/complaints/success', [App\Http\Controllers\Customer\ComplaintController::class, 'success'])->name('complaints.success'); // Pastikan method success ada di controller
    Route::get('/complaints', [App\Http\Controllers\Customer\ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{id}', [App\Http\Controllers\Customer\ComplaintController::class, 'show'])->name('complaints.show');
});
