@extends('layouts.app')

@section('title', 'Register | TATAFIX')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Decorative elements -->
    <div class="absolute top-[180px] -left-[100px] w-[260px] h-[260px] rounded-full opacity-50 bg-gradient-to-b from-blue-200 to-blue-300"></div>
    <div class="absolute top-[80px] -right-[80px] w-[200px] h-[200px] rounded-full opacity-60 bg-gradient-to-b from-blue-400 to-blue-500"></div>

    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg relative z-10">
        @if ($errors->any())
            <div class="mb-4 bg-red-50 text-red-500 p-4 rounded-lg">
                <ul class="list-none">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <h2 class="text-3xl font-bold text-gray-900 text-center">Bergabunglah Bersama Kami</h2>
            <p class="mt-2 text-center text-sm text-gray-600">Mulai Daftar Sekarang!</p>
        </div>

        <form class="mt-8 space-y-6" method="POST" action="/register">
            @csrf
            <div class="space-y-4">
                <div>
                    <input type="text" name="name" required 
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           placeholder="Nama Lengkap"
                           value="{{ old('name') }}">
                </div>
                <div>
                    <input type="email" name="email" required 
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           placeholder="Alamat Email"
                           value="{{ old('email') }}">
                </div>
                <div>
                    <input type="text" name="phone" required 
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           placeholder="No. Telepon"
                           value="{{ old('phone') }}">
                </div>
                <div>
                    <input type="text" name="address" required 
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           placeholder="Alamat Rumah"
                           value="{{ old('address') }}">
                </div>
                <div>
                    <input type="password" name="password" required 
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           placeholder="Kata Sandi">
                </div>
                <div>
                    <input type="password" name="password_confirmation" required 
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           placeholder="Konfirmasi Kata Sandi">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Daftar
                </button>
            </div>
        </form>

        @if (session('success'))
            <div class="mt-4 p-4 rounded-lg bg-green-100 border border-green-300 text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif
    </div>
</div>
@endsection

</html>
