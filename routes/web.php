<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Http\Middleware\CustomerAccess;

use App\Http\Controllers\BookingController;
use App\Http\Controllers\TukangController;
use App\Http\Controllers\AdminController;
use Spatie\Permission\Middleware\RoleMiddleware;

Route::get('/', [HomeController::class, 'index']) -> name('home');

// Booking Routes
Route::get('/booking', [BookingController::class, 'index']) -> name('booking');
Route::post('/booking', [BookingController::class, 'store']) -> name('booking.store');
Route::get('/booking/success/{booking}', [BookingController::class, 'success']) -> name('booking.success');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ROUTES UNTUK REGISTER
Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

// Booking Routes
Route::get('/booking', [BookingController::class, 'index']) -> name('booking');
Route::post('/booking', [BookingController::class, 'store']) -> name('booking.store');
Route::get('/booking/success/{booking}', [BookingController::class, 'success']) -> name('booking.success');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
   
    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    // Admin User Management
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
    Route::post('/users', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.users.delete');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/create/{service?}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
})->middleware(CustomerAccess::class);


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
   
    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    // Admin User Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
});

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    
    return redirect('/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/login', function() {
    return 'ini login';
}) -> name('login');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');


// Profile routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/')->with('success', 'Anda telah berhasil logout');
    })->name('logout');

});

