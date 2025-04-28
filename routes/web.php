<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']) -> name('home');

// Booking Routes
Route::get('/booking', [BookingController::class, 'index']) -> name('booking');
Route::post('/booking', [BookingController::class, 'store']) -> name('booking.store');
Route::get('/booking/success/{booking}', [BookingController::class, 'success']) -> name('booking.success');

Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
