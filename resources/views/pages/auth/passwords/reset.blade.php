@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    <div class="reset-form mx-auto max-w-md bg-white p-8 rounded-2xl shadow-lg mt-24 mb-24">
        <h1 class="text-2xl font-bold text-center mb-2">Ubah Kata Sandi</h1>
        <p class="text-center mb-6">Masukkan kata sandi baru Anda</p>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="email" value="{{ old('email', request('email')) }}">
            <input type="hidden" name="token" value="{{ old('token', request('token')) }}">

            <div class="mb-4">
                <label class="block font-semibold mb-1">Kata Sandi Baru</label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                           placeholder="Masukkan kata sandi baru"
                           class="w-full border rounded-lg p-3 pr-10 focus:ring-2 focus:ring-blue-400" required>
                    <i class="fa-solid fa-eye absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-500"
                       onclick="togglePassword('password', this)"></i>
                </div>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Konfirmasi Kata Sandi</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           placeholder="Konfirmasi kata sandi"
                           class="w-full border rounded-lg p-3 pr-10 focus:ring-2 focus:ring-blue-400" required>
                    <i class="fa-solid fa-eye absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-500"
                       onclick="togglePassword('password_confirmation', this)"></i>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
                Atur Kata Sandi
            </button>
        </form>
    </div>

    <script>
        function togglePassword(fieldId, icon) {
            const field = document.getElementById(fieldId);
            const isPassword = field.type === 'password';
            field.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    </script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

@endsection
