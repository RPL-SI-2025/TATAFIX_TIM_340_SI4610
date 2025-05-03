<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek user dengan role customer (misal role_id = 2)
        $user = User::where('email', $credentials['email'])->where('role_id', 2)->first();
        if ($user && \Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            return redirect()->intended(route('profile.show'));
        }

        return back()->withErrors(['email' => 'Email atau password salah, atau Anda bukan customer.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
