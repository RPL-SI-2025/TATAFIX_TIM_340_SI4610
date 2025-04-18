<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\Auth\ResetPasswordController;

// Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLink']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);

// Route::view('/forgot-password-form', 'auth.forgot-password');
Route::view('/reset-password-form', 'auth.reset-password'); 

// Menampilkan form reset-password lengkap dengan token & email dari URL
Route::get('/reset-password-form', function (Illuminate\Http\Request $request) {
    return view('auth.reset-password', [
        'token' => $request->token,
        'email' => $request->email
    ]);
})->name('password.reset');


