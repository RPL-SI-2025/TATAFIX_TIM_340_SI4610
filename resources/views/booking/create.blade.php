@extends('Layout.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-2xl mx-auto">
        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Booking Layanan</h1>
                <p class="text-gray-600 mb-6">Isi form berikut untuk memesan layanan perbaikan rumah</p>

                <form method="POST" action="{{ route('booking.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    
                    <div>
                        <label for="nama_pemesan" class="block text-sm font-medium text-gray-700 mb-1">Nama Pemesan</label>
                        <input type="text" 
                            id="nama_pemesan" 
                            name="nama_pemesan" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan nama lengkap Anda" 
                            required>
                    </div>
                    
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea 
                            id="alamat" 
                            name="alamat" 
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan alamat lengkap Anda" 
                            required></textarea>
                    </div>
                    
                    <div>
                        <label for="no_handphone" class="block text-sm font-medium text-gray-700 mb-1">No. Handphone</label>
                        <input type="text" 
                            id="no_handphone" 
                            name="no_handphone" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Contoh: 081234567890" 
                            required>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tanggal_booking" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Booking</label>
                            <input type="date" 
                                id="tanggal_booking" 
                                name="tanggal_booking" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                                required>
                        </div>
                        <div>
                            <label for="waktu_booking" class="block text-sm font-medium text-gray-700 mb-1">Waktu Booking</label>
                            <input type="time" 
                                id="waktu_booking" 
                                name="waktu_booking" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                    </div>
                    
                    <div>
                        <label for="catatan_perbaikan" class="block text-sm font-medium text-gray-700 mb-1">Catatan Perbaikan</label>
                        <textarea 
                            id="catatan_perbaikan" 
                            name="catatan_perbaikan" 
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Jelaskan masalah yang perlu diperbaiki" 
                            required></textarea>
                    </div>
                    
                    <button type="submit" 
                        class="w-full bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        Lanjut Bayar DP
                    </button>
                </form>

                @if (session('success'))
                    <div class="mt-6 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection