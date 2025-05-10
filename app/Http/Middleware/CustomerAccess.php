<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class CustomerAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response)  $next
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Cek apakah email sudah terverifikasi
        if (!Auth::user()->email_verified_at) {
            return redirect()->route('verification.notice')->with('error', 'Silakan verifikasi email Anda terlebih dahulu');
        }

        // Cek apakah role adalah customer
        if (Auth::user()->role !== 'customer') {
            return redirect()->route('home')->with('error', 'Hanya customer yang dapat mengakses halaman ini');
        }

        return $next($request);
    }
}
