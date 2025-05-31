@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <!-- Status Pesanan Timeline -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Status Pesanan</h2>
                <div class="flex justify-between items-center relative">
                    <!-- Timeline Line -->
                    <div class="absolute left-0 right-0 top-1/2 h-1 bg-gray-200 -z-10"></div>
                    
                    <!-- Timeline Points -->
                    <div class="flex flex-col items-center">
                        <div class="w-4 h-4 rounded-full bg-green-500"></div>
                        <p class="text-sm mt-2">Pesanan Diterima</p>
                        <p class="text-xs text-gray-500">19 Agustus 2024</p>
                    </div>
                    
                    <div class="flex flex-col items-center">
                        <div class="w-4 h-4 rounded-full bg-green-500"></div>
                        <p class="text-sm mt-2">Sedang Pengerjaan</p>
                        <p class="text-xs text-gray-500">22 Agustus 2024</p>
                    </div>
                    
                    <div class="flex flex-col items-center">
                        <div class="w-4 h-4 rounded-full bg-green-500"></div>
                        <p class="text-sm mt-2">Finishing</p>
                        <p class="text-xs text-gray-500">24 Agustus 2024</p>
                    </div>
                    
                    <div class="flex flex-col items-center">
                        <div class="w-4 h-4 rounded-full bg-green-500"></div>
                        <p class="text-sm mt-2">Selesai</p>
                        <p class="text-xs text-gray-500">25 Agustus 2024</p>
                    </div>
                </div>
            </div>

            <!-- Detail Pesanan -->
            <div class="flex items-start space-x-4 mb-6 p-4 bg-gray-50 rounded-lg">
                <img src="/assets/cat_rumah.jpg" alt="Bersih Rumah" class="w-20 h-20 object-cover rounded">
                <div>
                    <h3 class="font-semibold">Bersih Rumah</h3>
                    <p class="text-sm text-gray-600">1 - 2 hari pengerjaan</p>
                    <p class="text-orange-500 font-semibold mt-2">Rp350.000</p>
                </div>
            </div>

            <!-- Informasi Pemesan -->
            <div class="grid grid-cols-2 gap-6 mb-8">
                <div>
                    <h3 class="font-semibold mb-2">Nama Pemesan</h3>
                    <p class="text-gray-600">Keyra Renatha</p>
                </div>
                <div>
                    <h3 class="font-semibold mb-2">No Handphone</h3>
                    <p class="text-gray-600">082371920123</p>
                </div>
                <div class="col-span-2">
                    <h3 class="font-semibold mb-2">Alamat</h3>
                    <p class="text-gray-600">Jalan Buah Batu No. 123, Buah Batu Kota Bandung, Jawa Barat, 40265 Indonesia</p>
                </div>
                <div class="col-span-2">
                    <h3 class="font-semibold mb-2">Catatan Perbaikan</h3>
                    <p class="text-gray-600">-</p>
                </div>
            </div>

            <!-- Review Section -->
            <div class="border-t pt-6">
                <h3 class="text-center font-semibold mb-4">Terima Kasih Telah Menggunakan Layanan Kami</h3>
                <p class="text-center text-sm text-gray-600 mb-4">Rating layanan kami</p>
                <div class="flex justify-center space-x-2 mb-6">
                    <!-- Star Rating -->
                    <button class="text-2xl text-yellow-400">★</button>
                    <button class="text-2xl text-yellow-400">★</button>
                    <button class="text-2xl text-yellow-400">★</button>
                    <button class="text-2xl text-yellow-400">★</button>
                    <button class="text-2xl text-gray-300">★</button>
                </div>
                <div class="mb-6">
                    <textarea 
                        class="w-full p-3 border rounded-lg resize-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        rows="4"
                        placeholder="Tulis feedback Anda di kolom ini untuk membantu kami menjadi lebih baik"
                    ></textarea>
                </div>
                <div class="text-center">
                    <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-300">
                        Kirim Feedback
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection