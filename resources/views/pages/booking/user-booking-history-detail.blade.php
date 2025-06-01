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
            </div>
        </div>

        @if($currentStatus === 'COMPLETED' && (empty($booking->rating) || empty($booking->feedback)))
            <!-- Review Form Section -->
            <div class="border-t p-6">
                <h3 class="text-center font-semibold mb-4">Terima Kasih Telah Menggunakan Layanan Kami</h3>
                <form action="{{ route('booking.review.store', $booking) }}" method="POST" class="max-w-xl mx-auto" id="reviewForm">
                    @csrf
                    <p class="text-center text-sm text-gray-600 mb-4">Berikan penilaian untuk layanan kami</p>
                    <div class="flex justify-center space-x-2 mb-6">
                        <div class="star-rating">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" id="star-{{ $i }}" value="{{ $i }}" class="hidden" required>
                                <label for="star-{{ $i }}" class="star text-4xl cursor-pointer text-gray-300">★</label>
                            @endfor
                        </div>
                    </div>
                    <div class="mb-6">
                        <textarea 
                            name="feedback"
                            class="w-full p-3 border rounded-lg resize-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            rows="4"
                            placeholder="Berikan feedback Anda untuk membantu kami meningkatkan layanan"
                            required
                        ></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-300">
                            Kirim Review
                        </button>
                    </div>
                </form>
            </div>
        
            <!-- Success Modal -->
            <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-lg p-8 max-w-sm w-full mx-4 text-center">
                    <div class="mb-4 text-5xl text-blue-600">✓</div>
                    <h3 class="text-xl font-semibold mb-2">Terima kasih!</h3>
                    <p class="text-gray-600 mb-4">Feedback Anda telah berhasil dikirim.</p>
                    <button onclick="window.location.reload()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-300">
                        OK
                    </button>
                </div>
            </div>
        
            <style>
                .star-rating {
                    display: flex;
                    flex-direction: row-reverse;
                    justify-content: center;
                }
        
                .star-rating input[type="radio"]:checked ~ label,
                .star-rating label:hover,
                .star-rating label:hover ~ label {
                    color: #FCD34D;
                }
        
                .star {
                    padding: 0 2px;
                }
            </style>
        
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('reviewForm');
                    const successModal = document.getElementById('successModal');
        
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        const formData = new FormData(form);
                        
                        fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(() => {
                            successModal.classList.remove('hidden');
                            successModal.classList.add('flex');
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat mengirim review. Silakan coba lagi.');
                        });
                    });
                });
            </script>
        @endif
        @else
            <!-- Display Existing Review -->
            <div class="border-t p-6">
                <h3 class="text-center font-semibold mb-4">Review Anda</h3>
                <div class="max-w-xl mx-auto">
                    <div class="flex justify-center space-x-2 mb-6">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="text-3xl {{ $i <= $booking->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                        @endfor
                    </div>
                    <div class="text-center text-gray-600 bg-gray-50 p-4 rounded-lg">
                        <p>{{ $booking->feedback }}</p>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

<style>
    /* Card hover effect */
    .bg-white {
        transition: all 0.3s ease;
    }

    /* Star rating hover effect */
    input[name="rating"] + label:hover,
    input[name="rating"] + label:hover ~ label {
        color: #FBBF24;
    }
    input[name="rating"]:checked + label,
    input[name="rating"]:checked + label ~ label {
        color: #FBBF24;
    }
</style>
@endsection
