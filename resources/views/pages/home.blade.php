@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="bg-blue-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-wrap items-center">
            <div class="w-full md:w-1/2 mb-8 md:mb-0">
                <h1 class="text-3xl font-bold mb-4">TATAFIX</h1>
                <p class="mb-6">Aplikasi layanan perbaikan yang menghubungkan kebutuhan perbaikan rumah Anda dengan tukang profesional terpercaya. Dapatkan layanan berkualitas dengan harga terjangkau, proses booking mudah, dan pembayaran yang aman untuk perbaikan rumah Anda.</p>
                <a href="{{ route('booking.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-lg inline-block">Mulai Sekarang</a>
            </div>
            <div class="w-full md:w-1/2 flex justify-center">
                <img src="{{ asset('assets/foto_orang_dashboard.svg') }}" alt="Tukang Profesional" class="max-w-full h-auto">
            </div>
        </div>
    </div>

    <!-- Jasa Perbaikan Section -->
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-center mb-2">Jasa Perbaikan Rumah</h2>
            <p class="text-center text-gray-600 mb-8">Solusi cepat dan terpercaya untuk kebutuhan perbaikan rumah Anda</p>
            
            <!-- Search Bar -->
            <div class="max-w-md mx-auto mb-12">
                <div class="relative">
                    <input type="text" placeholder="Cari layanan..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <button class="absolute right-2 top-2 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Service Categories -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($services as $service)
                <!-- Service Item -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <img src="{{ $service->image_url }}" alt="{{ $service->title_service }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2">{{ $service->title_service }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($service->description, 100) }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-600 font-semibold">Rp {{ number_format($service->base_price, 0, ',', '.') }}</span>
                            <a href="{{ route('booking.index', ['service_id' => $service->service_id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-block">Pesan Sekarang</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- View All Services Button -->
            <div class="text-center mt-8">
                <a href="{{ route('booking.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg inline-block font-semibold">Lihat Semua Layanan</a>
            </div>
        </div>
    </div>
    
    <!-- FAQ Section -->
    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-blue-600 rounded-lg p-6 flex items-center">
                <div class="mr-6">
                    <img src="{{ asset('assets/foto_orang_dashboard.svg') }}" alt="FAQ" class="w-24 h-24">
                </div>
                <div class="text-white">
                    <h3 class="text-xl font-bold mb-2">Frequently Ask Question (FAQ)</h3>
                    <p class="mb-4">Temukan jawaban lengkap terkait TATAFIX</p>
                    <a href="#" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-gray-100 transition-colors">Lihat Selengkapnya</a>
                </div>
            </div>
        </div>
    </div>
@endsection
