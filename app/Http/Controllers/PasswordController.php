<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules;
use App\Models\User;

class PasswordController extends Controller
{
    // Form change password (untuk user yang sudah login)
    public function changePasswordForm()
    {
        return view('auth.change-password');
    }

    // Proses change password
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).+$/'
            ],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'current_password.current_password' => 'Password saat ini tidak sesuai',
            'password.required' => 'Password baru wajib diisi',
            'password.min' => 'Kata sandi minimal harus terdiri dari 6 karakter.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus',
        ]);

        $userId = Auth::id();
        User::where('id', $userId)->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profile')->with('success', 'Password berhasil diubah');
    }

    // Form forgot password (untuk user yang belum login)
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Proses forgot password
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
 
        $status = Password::sendResetLink(
            $request->only('email')
        );
 
        return $status === Password::RESET_LINK_SENT
            ? back()->with('message', 'Link reset password sudah dikirim ke email kamu!')
            : back()->withErrors(['email' => __($status)]);
    }

    // Form reset password
    public function showResetPasswordForm(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // Proses reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).+$/'
            ],
        ], 
        [
            'password.required' => 'Password Wajib Diisikan',
            'password.min' => 'Kata sandi minimal harus terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus',
        ]);
 
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
 
                $user->save();
 
                event(new PasswordReset($user));
            }
        );
 
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
