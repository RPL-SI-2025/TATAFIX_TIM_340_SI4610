@extends('Layout.app')

@section('title', 'Verifikasi Email | TATAFIX')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg">
        @if (session('success'))
            <div class="mb-4 p-4 rounded-lg bg-green-100 border border-green-300 text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">Verifikasi Email Anda</h2>
            <p class="mt-2 text-sm text-gray-600">
                Sebelum melanjutkan, silakan periksa email Anda untuk link verifikasi.
                @if (session('resent'))
                    <span class="text-green-600">Link verifikasi baru telah dikirim ke alamat email Anda.</span>
                @endif
            </p>
        </div>

        <div class="mt-8">
            <p class="text-sm text-gray-600 mb-4">
                Jika Anda tidak menerima email verifikasi, klik tombol di bawah untuk mengirim ulang.
            </p>

            <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
                @csrf
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>
        </div>

        <div class="mt-6 text-center">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection