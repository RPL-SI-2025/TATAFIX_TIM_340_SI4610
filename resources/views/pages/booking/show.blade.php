@extends('layouts.app')

@section('title', 'Detail Booking')

@section('styles')
<style>
    .detail-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }
    
    .detail-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .booking-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 5px;
    }
    .timeline-container {
        position: relative;
        padding-left: 2rem;
    }
    .timeline-container::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #E5E7EB;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -2rem;
        top: 0.25rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background-color: #E5E7EB;
        border: 2px solid #FFF;
    }
    .timeline-item.active::before {
        background-color: #3B82F6;
    }
    .timeline-item.completed::before {
        background-color: #10B981;
    }
    .card {
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        background-color: white;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    .card-header {
        padding: 1rem 1.5rem;
        background-color: #F9FAFB;
        border-bottom: 1px solid #E5E7EB;
        font-weight: 600;
    }
    .card-body {
        padding: 1.5rem;
    }
    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-primary {
        background-color: #EFF6FF;
        color: #2563EB;
    }
    .badge-success {
        background-color: #ECFDF5;
        color: #059669;
    }
    .badge-warning {
        background-color: #FFFBEB;
        color: #D97706;
    }
    .badge-danger {
        background-color: #FEF2F2;
        color: #DC2626;
    }
    .btn-primary {
        background-color: #2563EB;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        display: inline-block;
        text-decoration: none;
        transition: background-color 0.2s;
    }
    .btn-primary:hover {
        background-color: #1D4ED8;
    }
    .btn-success {
        background-color: #059669;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        display: inline-block;
        text-decoration: none;
        transition: background-color 0.2s;
    }
    .btn-success:hover {
        background-color: #047857;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('booking.history') }}">Riwayat Booking</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Booking</li>
                </ol>
            </nav>

            <!-- Booking Details -->
            <x-booking.info-card :booking="$booking" :showActions="false" />

            <!-- Booking Status Timeline -->
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0">Detail Booking #{{ $booking->id }}</h4>
                </div>
                <div class="card-body p-4">
                    <!-- Status Badge -->
                    <div class="text-center mb-4">
                        <x-booking.status-badge :status="$booking->status" class="px-4 py-2 text-lg" />
                    </div>

                    <!-- Timeline -->
                    <div class="timeline-container">
                        @php
                            // Definisikan pemetaan status untuk berbagai kemungkinan format status_code
                            $statusOrder = [
                                // Format lowercase
                                'pending' => 1,
                                'waiting_dp_validation' => 2,
                                'waiting_validation_dp' => 2,
                                'dp_validated' => 3,
                                'in_progress' => 4,
                                'done' => 5,
                                'waiting_final_validation' => 6,
                                'waiting_validation_pelunasan' => 6,
                                'completed' => 7,
                                'rejected' => 8,
                                'canceled' => 8,
                                'cancelled' => 8,
                                
                                // Format uppercase
                                'PENDING' => 1,
                                'WAITING_DP' => 1,
                                'PENDING_DP' => 1,
                                'WAITING_DP_VALIDATION' => 2,
                                'DP_VALIDATED' => 3,
                                'ASSIGNED' => 3,
                                'WAITING_TUKANG_ASSIGNMENT' => 3,
                                'IN_PROGRESS' => 4,
                                'DONE' => 5,
                                'WAITING_FINAL_PAYMENT' => 5,
                                'WAITING_FINAL_VALIDATION' => 6,
                                'VALIDATING_FINAL_PAYMENT' => 6,
                                'COMPLETED' => 7,
                                'REJECTED' => 8,
                                'CANCELLED' => 8,
                                'CANCELED' => 8
                            ];
                            
                            $currentStatusCode = strtolower($booking->status->status_code);
                            $currentStatusOrder = $statusOrder[$booking->status->status_code] ?? ($statusOrder[$currentStatusCode] ?? 0);
                        @endphp

                        <div class="timeline-item {{ $currentStatusOrder >= 1 ? 'completed' : '' }}">
                            <h3 class="font-semibold">Booking Dibuat</h3>
                            <p class="text-sm text-gray-600">Booking telah dibuat dan menunggu pembayaran DP</p>
                        </div>

                        <div class="timeline-item {{ $currentStatusOrder >= 2 ? ($currentStatusOrder > 2 ? 'completed' : 'active') : '' }}">
                            <h3 class="font-semibold">Menunggu Validasi DP</h3>
                            <p class="text-sm text-gray-600">Pembayaran DP sedang divalidasi oleh admin</p>
                        </div>

                        <div class="timeline-item {{ $currentStatusOrder >= 3 ? ($currentStatusOrder > 3 ? 'completed' : 'active') : '' }}">
                            <h3 class="font-semibold">DP Tervalidasi</h3>
                            <p class="text-sm text-gray-600">Pembayaran DP telah divalidasi, menunggu penugasan tukang</p>
                        </div>

                        <div class="timeline-item {{ $currentStatusOrder >= 4 ? ($currentStatusOrder > 4 ? 'completed' : 'active') : '' }}">
                            <h3 class="font-semibold">Dalam Pengerjaan</h3>
                            <p class="text-sm text-gray-600">Tukang sedang mengerjakan layanan</p>
                        </div>

                        <div class="timeline-item {{ $currentStatusOrder >= 5 ? ($currentStatusOrder > 5 ? 'completed' : 'active') : '' }}">
                            <h3 class="font-semibold">Pengerjaan Selesai</h3>
                            <p class="text-sm text-gray-600">Tukang telah menyelesaikan pekerjaan, menunggu pelunasan</p>
                        </div>

                        <div class="timeline-item {{ $currentStatusOrder >= 6 ? ($currentStatusOrder > 6 ? 'completed' : 'active') : '' }}">
                            <h3 class="font-semibold">Menunggu Validasi Pelunasan</h3>
                            <p class="text-sm text-gray-600">Pembayaran pelunasan sedang divalidasi oleh admin</p>
                        </div>

                        <div class="timeline-item {{ $currentStatusOrder >= 7 ? 'active' : '' }}">
                            <h3 class="font-semibold">Booking Selesai</h3>
                            <p class="text-sm text-gray-600">Booking telah selesai dan semua pembayaran telah divalidasi</p>
                        </div>

                        @if($currentStatusCode == 'rejected' || $currentStatusCode == 'canceled')
                        <div class="timeline-item active">
                            <h3 class="font-semibold">{{ $currentStatusCode == 'rejected' ? 'Booking Ditolak' : 'Booking Dibatalkan' }}</h3>
                            <p class="text-sm text-gray-600">
                                {{ $currentStatusCode == 'rejected' ? 'Booking telah ditolak oleh admin' : 'Booking telah dibatalkan' }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-bold">Aksi</h2>
                </div>
                <div class="card-body">
                    @php
                        $statusCode = strtolower($booking->status->status_code);
                        $pendingStatuses = ['pending', 'waiting_dp', 'pending_dp'];
                        $doneStatuses = ['done', 'waiting_final_payment'];
                        $cancelableStatuses = ['pending', 'waiting_dp', 'pending_dp', 'waiting_validation_dp', 'waiting_dp_validation'];
                    @endphp
                    
                    @if(in_array($statusCode, $pendingStatuses) || in_array($booking->status->status_code, $pendingStatuses))
                    <a href="{{ route('payment.dp.form', $booking->id) }}" class="btn-primary w-full block text-center mb-3">
                        <i class="fas fa-credit-card mr-2"></i> Bayar DP
                    </a>
                    @endif

                    @if(in_array($statusCode, $doneStatuses) || in_array($booking->status->status_code, $doneStatuses))
                    <a href="{{ route('payment.final.form', $booking->id) }}" class="btn-success w-full block text-center mb-3">
                        <i class="fas fa-credit-card mr-2"></i> Bayar Pelunasan
                    </a>
                    @endif

                    @if(in_array($statusCode, $cancelableStatuses) || in_array($booking->status->status_code, $cancelableStatuses))
                    <form action="{{ route('booking.cancel', $booking->id) }}" method="POST" class="mt-3">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500" onclick="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                            <i class="fas fa-times-circle mr-2"></i> Batalkan Booking
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('booking.tracking', $booking->id) }}" class="btn-primary w-full block text-center mt-3">
                        <i class="fas fa-map-marker-alt mr-2"></i> Lacak Status Booking
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
