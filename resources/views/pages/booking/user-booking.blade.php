@extends('layouts.app')

@section('title', 'Status Pesanan')

@section('content')
<style>
    .booking-timeline {
        padding: 20px 0;
        max-width: 1000px;
        margin: 0 auto;
    }

    .booking-timeline__wrapper {
        position: relative;
        display: flex;
        justify-content: space-between;
        padding: 0;
        margin-top: 40px;
        width: 100%;
    }

    /* Garis abu-abu background */
    .booking-timeline__line {
        position: absolute;
        top: 7px;
        width: calc(100% - 240px); /* Kurangi dengan lebar satu dot */
        left: 120px; /* Setengah dari lebar dot */
        height: 3px;
        background: #606060;
        z-index: 1;
    }

    /* Garis oranye progress */
    .booking-timeline__progress {
        position: absolute;
        top: 7px;
        left: 120px; /* Setengah dari lebar dot */
        height: 3px;
        background: #F97316;
        transition: width 0.3s ease;
        z-index: 2;
    }

    .booking-timeline__step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 3;
        flex: 1;
        min-width: 16px; /* Minimal selebar dot */
    }

    .booking-timeline__step:not(:last-child) {
        position: relative;
    }

    .booking-timeline__dot {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #F97316;
        margin-bottom: 12px;
        position: relative;
        z-index: 3;
    }

    .booking-timeline__dot--inactive {
        background: #5c5c5c;
    }

    .booking-timeline__label {
        font-size: 14px;
        color: #1F2937;
        margin-bottom: 8px;
        font-weight: 500;
        white-space: nowrap;
    }

    .booking-timeline__date {
        font-size: 12px;
        color: #6B7280;
        white-space: nowrap;
    }

    .booking-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .booking-title {
        font-size: 24px;
        font-weight: bold;
        color: #2563EB;
        margin-bottom: 32px;
    }

    /* Modal Styles */
    .confirmation-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .confirmation-modal.show {
        opacity: 1;
        visibility: visible;
    }

    .confirmation-modal__content {
        background: linear-gradient(135deg, #1E678D 0%, #1E678D 100%);
        border-radius: 16px;
        padding: 32px;
        max-width: 480px;
        width: 90%;
        margin: 0 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }

    .confirmation-modal.show .confirmation-modal__content {
        transform: scale(1);
    }

    .confirmation-modal__header {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 24px;
    }

    .confirmation-modal__icon {
        width: 48px;
        height: 48px;
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .confirmation-modal__icon svg {
        width: 24px;
        height: 24px;
        color: white;
    }

    .confirmation-modal__text {
        flex: 1;
    }

    .confirmation-modal__title {
        color: white;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .confirmation-modal__subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
        line-height: 1.4;
    }

    .confirmation-modal__buttons {
        display: flex;
        gap: 12px;
    }

    .confirmation-modal__button {
        flex: 1;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .confirmation-modal__button--primary {
        background-color: white;
        color: #1E678D;
    }

    .confirmation-modal__button--primary:hover {
        background-color: #F8FAFC;
        transform: translateY(-1px);
    }

    .confirmation-modal__button--secondary {
        background-color: transparent;
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.4);
    }

    .confirmation-modal__button--secondary:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.6);
    }
</style>

<div class="booking-container">
    <h1 class="booking-title">Status Pesanan</h1>

    <!-- Timeline -->
    <div class="booking-timeline">
        @php
            $statuses = [
                'PENDING' => 'Pesanan Diterima',
                'CONFIRMED' => 'Dikonfirmasi',
                'ON_PROCESS' => 'Sedang Diproses',
                'COMPLETED' => 'Selesai'
            ];

            // Get booking status IDs for each status code
            $statusIds = [];
            foreach(array_keys($statuses) as $statusCode) {
                $statusModel = \App\Models\BookingStatus::where('status_code', $statusCode)->first();
                if($statusModel) {
                    $statusIds[$statusCode] = $statusModel->id;
                }
            }

            // Get booking logs for this booking with status relationships
            $bookingLogs = $booking->bookingLogs()->with('bookingStatus')->get();

            // Determine which statuses have logs (active/completed statuses)
            $completedStatuses = [];
            $dates = [];

            foreach($bookingLogs as $log) {
                $statusCode = $log->bookingStatus->status_code;
                $completedStatuses[] = $statusCode;

                // Format tanggal dalam bahasa Indonesia
                $formattedDate = $log->created_at->locale('id')->isoFormat('D MMMM Y');
                $dates[$statusCode] = $formattedDate;
            }

            // Calculate progress based on completed statuses
            $statusKeys = array_keys($statuses);
            $progressWidth = 0;

            if(!empty($completedStatuses)) {
                // Count consecutive completed statuses from PENDING
                $consecutiveCompleted = 0;
                foreach($statusKeys as $index => $statusCode) {
                    if(in_array($statusCode, $completedStatuses)) {
                        $consecutiveCompleted = $index + 1;
                } else {
                        break; // Stop if we hit a gap
                    }
                }

                if($consecutiveCompleted > 0) {
                    $progressWidth = (($consecutiveCompleted - 1) / count($statusKeys)) * 100;
                }
            }
        @endphp

        <div class="booking-timeline__wrapper">
            <div class="booking-timeline__line"></div>
            <div class="booking-timeline__progress" style="width: {{ $progressWidth }}%"></div>

            @foreach($statuses as $code => $label)
                @php
                    // Status is active (orange) if it has a booking log
                    $isActive = in_array($code, $completedStatuses);
                    $statusDate = $dates[$code] ?? '-';
                @endphp

                <div class="booking-timeline__step">
                    <div class="booking-timeline__dot {{ !$isActive ? 'booking-timeline__dot--inactive' : '' }}"></div>
                    <div class="booking-timeline__label">{{ $label }}</div>
                    <div class="booking-timeline__date">{{ $statusDate }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Service Info -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
                <img src="{{ $booking->service->image_url }}"
                 alt="{{ $booking->service->title_service }}"
                     class="rounded-lg object-cover" style="width: 100px; height: 100px;">
            <div>
                <h2 class="text-xl font-semibold">{{ $booking->service->title_service }}</h2>
                <p class="text-gray-500">{{ $booking->service->description }}</p>
            </div>
            </div>
            <div>
                <p class="text-xl font-bold text-orange-500">Rp{{ number_format($booking->service->base_price, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Detail Pemesanan -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Detail Pemesanan</h2>
        <div class="grid grid-cols-2 gap-8">
            <div>
                <h3 class="font-medium mb-2">Nama Pemesan</h3>
                <p>{{ $booking->nama_pemesan }}</p>

                <h3 class="font-medium mb-2 mt-4">No Handphone</h3>
                <p>{{ $booking->no_handphone }}</p>
            </div>
            <div>
                <h3 class="font-medium mb-2">Alamat</h3>
                <p>{{ $booking->alamat }}</p>

                <h3 class="font-medium mb-2 mt-4">Catatan Perbaikan</h3>
                <p>{{ $booking->catatan_perbaikan ?: '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Pembayaran -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Pembayaran</h2>
        <div class="grid grid-cols-2 gap-8">
            <div>
                <div class="mb-4">
                    <h3 class="font-medium mb-2">Sudah Dibayar</h3>
                    <p>Rp{{ number_format($booking->service->base_price / 2, 0, ',', '.') }}</p>
                </div>
                <div>
                    <h3 class="font-medium mb-2">Belum Dibayar</h3>
                    <p>Rp{{ number_format($booking->service->base_price / 2, 0, ',', '.') }}</p>
                </div>
            </div>
            <div>
                <h3 class="font-medium mb-2">Metode Pembayaran</h3>
                <p>Transfer Bank BCA</p>
            </div>
        </div>
    </div>

    @php
        $isProjectDone = in_array($booking->bookingLogs->last()->booking_status_id, [
            \App\Models\BookingStatus::where('status_code', 'COMPLETED')->first()->id,
            \App\Models\BookingStatus::where('status_code', 'CANCELLED')->first()->id
        ]);
    @endphp

    <!-- Action Buttons -->
    <div class="flex gap-4">
        @if(!$isProjectDone)
            <button class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 flex-1">Pengaduan</button>
            @if($isProjectDone)
                <button class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-50 flex-1">Berikan Feedback</button>
            @endif
            @if(!$isProjectDone)
                <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 flex-1">Lengkapi Pembayaran</button>
            @endif
        @else
            <div class="w-full flex justify-end">
                <button class="text-white px-6 py-2 rounded-lg hover:bg-blue-700 confirmation-trigger w-[200px]" style="background-color: #1E678D">Konfirmasi Pesanan</button>
            </div>
        @endif
    </div>
</div>

<!-- Modal -->
<div class="confirmation-modal" id="confirmationModal">
    <div class="confirmation-modal__content">
        <div class="confirmation-modal__header">
            <div class="confirmation-modal__icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <div class="confirmation-modal__text">
                <h3 class="confirmation-modal__title">Konfirmasi penyelesaian pesanan</h3>
                <p class="confirmation-modal__subtitle">Apakah Anda yakin pesanan ini sudah selesai?</p>
            </div>
        </div>
        <div class="confirmation-modal__buttons">
            <button class="confirmation-modal__button confirmation-modal__button--primary" id="confirmButton">Ya, Saya yakin</button>
            <button class="confirmation-modal__button confirmation-modal__button--secondary" id="cancelButton">Tidak, Batalkan</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('confirmationModal');
        const triggerButton = document.querySelector('.confirmation-trigger');
        const confirmButton = document.getElementById('confirmButton');
        const cancelButton = document.getElementById('cancelButton');

        // Show modal when trigger button is clicked
        if (triggerButton) {
            triggerButton.addEventListener('click', function() {
                modal.classList.add('show');
            });
        }

        // Hide modal when confirm button is clicked
        confirmButton.addEventListener('click', function() {
            // Handle konfirmasi pesanan
            console.log('Pesanan dikonfirmasi');
            modal.classList.remove('show');
            // Di sini bisa ditambahkan AJAX request untuk update status
        });

        // Hide modal when cancel button is clicked
        cancelButton.addEventListener('click', function() {
            modal.classList.remove('show');
        });

        // Hide modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.remove('show');
            }
        });

        // Hide modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('show')) {
                modal.classList.remove('show');
            }
        });
    });
</script>
@endsection
