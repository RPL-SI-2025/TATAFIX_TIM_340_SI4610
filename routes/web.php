<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');

// Rute untuk halaman utama
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rute untuk menampilkan form booking
Route::get('/booking', [BookingController::class, 'index'])->name('booking');

// Rute untuk menyimpan data booking
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

// // Tambahkan rute lain
// // Contoh rute tambahan:
// Route::get('/chat', function () {
//     return view('chat');
// })->name('chat');

// Route::get('/terms', function () {
//     return view('terms');
// })->name('terms');

// Route::get('/privacy', function () {
//     return view('privacy');
// })->name('privacy');