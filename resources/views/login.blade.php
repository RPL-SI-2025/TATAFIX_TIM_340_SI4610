@extends('Layout.app')
@section('title', 'Login')
@section('content')

    <!-- LOGIN CONTAINER -->
    <div class="login-container mx-auto max-w-md bg-white p-8 rounded-2xl shadow-lg mt-24 mb-24">
        <h1 class="text-2xl font-bold text-center mb-2">Selamat Datang</h1>
        <p class="text-center mb-6">Masuk untuk Mendapatkan Layanan Terbaik!</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold mb-1">Email</label>
                <input type="email" name="email" placeholder="Masukkan Email" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-400" required>
                @error('email')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Kata Sandi</label>
                <input type="password" name="password" placeholder="Masukkan Kata Sandi" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-400" required>
                @error('password')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">Login</button>
            <div class="text-center mt-4">
                <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline mb-2 block">Lupa Kata Sandi?</a>
                Belum punya akun? <a href="{{ route('register.form') }}" class="text-blue-600 hover:underline">Sign Up</a>
            </div>
        </form>
        
        @if($errors->has('login'))
            <div style="background-color: #FFB3B3; color: #B00020; padding: 12px; border-radius: 8px; margin-top: 20px; text-align: center;">
                {{ $errors->first('login') }}
            </div>
        @endif
    </div>

<<<<<<< HEAD
@endsection
=======

@endsection
>>>>>>> origin/main
