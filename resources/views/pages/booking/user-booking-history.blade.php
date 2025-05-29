@extends('layouts.app')

@section('title', 'Riwayat Pemesanan')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <h1 class="text-2xl font-bold text-blue-600 mb-8">Riwayat Pemesanan</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($bookings as $booking)
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <!-- Card Image -->
            <div class="relative h-48 overflow-hidden">
                <img
                    src="{{ $booking->service->image_url ?? '/images/default-service.jpg' }}"
                    alt="{{ $booking->service->title_service }}"
                    class="w-full h-full object-cover"
                >
            </div>

            <!-- Card Content -->
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">
                    <a href="{{ route('booking.history.detail', $booking->id) }}">{{ $booking->service->title_service }}</a>
                </h3>

                <!-- Price with unit -->
                <div class="flex items-baseline mb-4">
                    <span class="text-orange-500 font-bold">Rp{{ number_format($booking->service->base_price, 0, ',', '.') }}</span>
                    @if($booking->service->label_unit)
                        <span class="text-gray-500 text-sm ml-1">/{{ $booking->service->label_unit }}</span>
                    @endif
                </div>

                <!-- Estimated time -->
                <p class="text-gray-600 text-sm mb-4">
                    1 - 2 hari pengerjaan
                </p>

                <!-- Status Badge -->
                @php
                    $statusClasses = [
                        'WAITING_DP' => 'color: white; background-color: #F59E0B; border-color: #F59E0B;',
                        'PENDING' => 'color: white; background-color: #F59E0B; border-color: #F59E0B;',
                        'ON_PROCESS' => 'color: white; background-color: #F59E0B; border-color: #F59E0B;',
                        'CONFIRMED' => 'color: white; background-color: #3B82F6; border-color: #3B82F6;',
                        'COMPLETED' => 'color: white; background-color: #10B981; border-color: #10B981;',
                        'CANCELLED' => 'color: white; background-color: #EF4444; border-color: #EF4444;',
                    ];
                    $statusLabels = [
                        'WAITING_DP' => 'Menunggu Pembayaran DP',
                        'PENDING' => 'Menunggu Konfirmasi',
                        'ON_PROCESS' => 'Sedang Diproses',
                        'CONFIRMED' => 'Dikonfirmasi',
                        'COMPLETED' => 'Selesai',
                        'CANCELLED' => 'Dibatalkan',
                    ];
                    $currentStatus = $booking->bookingStatus->status_code;
                @endphp

                <div class="w-full">
                    <button
                        class="w-full py-2 px-4 rounded-lg font-medium" style="{{ $statusClasses[$currentStatus] ?? 'bg-gray-200 text-gray-800' }}"
                        disabled
                    >
                        {{ $statusLabels[$currentStatus] ?? 'Status Tidak Diketahui' }}
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- See More Button -->
    @if($bookings->count() > 9)
    <div class="text-center mt-8">
        <button class="text-gray-500 hover:text-gray-700">
            See more
        </button>
    </div>
    @endif
</div>

<style>
    /* Card hover effect */
    .bg-white {
        transition: all 0.3s ease;
    }
    .bg-white:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
</style>
@endsection
