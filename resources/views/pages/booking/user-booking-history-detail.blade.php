@extends('layouts.app')

@section('title', 'Detail Riwayat Pemesanan')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <h1 class="text-2xl font-bold text-blue-600 mb-8">Riwayat Pemesanan</h1>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
            <!-- Left Column - Image -->
            <div class="rounded-xl p-4" style="border: 1px solid #E5E7EB;">
                <div class="flex flex-col gap-2">
                    <div class="relative h-[300px] overflow-hidden rounded-xl mb-4">
                        <img
                        src="{{ $booking->service->image_url ?? '/images/default-service.jpg' }}"
                        alt="{{ $booking->service->title_service }}"
                        class="w-full h-full object-cover"
                        >
                    </div>
                    
                    <div class="w-full">
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
                            $lastBookingLog = $booking->bookingLogs->last();
                            $currentStatus = $lastBookingLog ? $lastBookingLog->bookingStatus->status_code : 'PENDING';
                        @endphp
                        <span class="px-4 py-2 rounded-lg text-white text-center font-medium" style="{{ $statusClasses[$currentStatus] ?? 'bg-gray-500' }}; width: 100%; display: block;">
                            {{ $statusLabels[$currentStatus] ?? 'Status Tidak Diketahui' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right Column - Details -->
            <div class="space-y-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $booking->service->title_service }}</h2>
                    <p class="text-gray-600">Penyedia Layanan: {{ $booking->service->provider->name }}</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-gray-600">Tanggal Pemesanan: {{ \Carbon\Carbon::parse($booking->created_at)->format('d F Y') }} WIB</p>
                    </div>

                    <div>
                        <p class="text-gray-600">Jadwal Pelaksanaan: {{ \Carbon\Carbon::parse($booking->schedule_date)->format('d F Y, H:i') }} WIB</p>
                    </div>

                    <div>
                        <p class="text-gray-600">Biaya:</p>
                        <p class="text-2xl font-bold text-orange-500">
                            Rp{{ number_format($booking->service->base_price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="mt-8 flex gap-4">
                    <a href="{{ route('booking.status', $booking->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        Cek Status
                    </a>
                    @if(($currentStatus === 'COMPLETED') && (empty($booking->rating) || empty($booking->feedback)))
                        <a href="{{ route('booking.review', $booking->id) }}" class="text-green-600 hover:text-green-800 font-medium">
                            Beri Review
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Card hover effect */
    .bg-white {
        transition: all 0.3s ease;
    }
</style>
@endsection
