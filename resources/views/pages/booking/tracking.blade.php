@extends('layouts.app')

@section('title', 'Status Pesanan')

@section('styles')
<style>
    .tracking-container {
        margin: 30px 0;
    }
    
    .tracking-timeline {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 40px;
    }
    
    .tracking-timeline::before {
        content: '';
        position: absolute;
        top: 25px;
        left: 0;
        width: 100%;
        height: 4px;
        background-color: #e0e0e0;
        z-index: 1;
    }
    
    .tracking-step {
        position: relative;
        z-index: 2;
        text-align: center;
        width: 25%;
    }
    
    .tracking-step-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        color: white;
        font-size: 20px;
        transition: all 0.3s ease;
    }
    
    .tracking-step.active .tracking-step-icon,
    .tracking-step.completed .tracking-step-icon {
        background-color: #fd7e14;
    }
    
    .tracking-step.completed .tracking-step-icon {
        background-color: #28a745;
    }
    
    .tracking-step-label {
        font-weight: 600;
        margin-bottom: 5px;
        color: #495057;
    }
    
    .tracking-step-date {
        font-size: 14px;
        color: #6c757d;
    }
    
    .booking-details {
        margin-top: 30px;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .booking-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 5px;
    }
    
    .booking-service-title {
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .booking-service-category {
        font-size: 14px;
        color: #6c757d;
    }
    
    .booking-price {
        font-weight: 700;
        color: #fd7e14;
    }
    
    .customer-details {
        margin-top: 20px;
    }
    
    .customer-detail-item {
        margin-bottom: 15px;
    }
    
    .customer-detail-label {
        font-weight: 600;
        color: #495057;
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
                    <li class="breadcrumb-item active" aria-current="page">Status Pesanan</li>
                </ol>
            </nav>
            
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-primary text-white py-3 d-flex align-items-center">
                    <i class="fas fa-clipboard-list me-2"></i>
                    <h4 class="mb-0">Status Pesanan</h4>
                </div>
                <div class="card-body p-4">
                    <!-- Tracking Timeline -->
                    <div class="tracking-container">
                        <div class="tracking-timeline">
                            @php
                                // Definisikan tahapan utama tracking
                                $mainSteps = [
                                    [
                                        'label' => 'Pesanan Diterima',
                                        'icon' => 'fas fa-clipboard-check',
                                        'status_codes' => ['pending', 'waiting_validation_dp', 'dp_validated'],
                                        'date' => $booking->created_at
                                    ],
                                    [
                                        'label' => 'Sedang Pengerjaan',
                                        'icon' => 'fas fa-tools',
                                        'status_codes' => ['in_progress'],
                                        'date' => $booking->bookingLogs()
                                            ->whereHas('status', function($q) {
                                                $q->where('status_code', 'in_progress');
                                            })->first() ? $booking->bookingLogs()
                                            ->whereHas('status', function($q) {
                                                $q->where('status_code', 'in_progress');
                                            })->first()->created_at : null
                                    ],
                                    [
                                        'label' => 'Finishing',
                                        'icon' => 'fas fa-paint-roller',
                                        'status_codes' => ['done', 'waiting_pelunasan', 'waiting_validation_pelunasan'],
                                        'date' => $booking->bookingLogs()
                                            ->whereHas('status', function($q) {
                                                $q->whereIn('status_code', ['done', 'waiting_pelunasan', 'waiting_validation_pelunasan']);
                                            })->first() ? $booking->bookingLogs()
                                            ->whereHas('status', function($q) {
                                                $q->whereIn('status_code', ['done', 'waiting_pelunasan', 'waiting_validation_pelunasan']);
                                            })->first()->created_at : null
                                    ],
                                    [
                                        'label' => 'Selesai',
                                        'icon' => 'fas fa-check-circle',
                                        'status_codes' => ['completed'],
                                        'date' => $booking->bookingLogs()
                                            ->whereHas('status', function($q) {
                                                $q->where('status_code', 'completed');
                                            })->first() ? $booking->bookingLogs()
                                            ->whereHas('status', function($q) {
                                                $q->where('status_code', 'completed');
                                            })->first()->created_at : null
                                    ]
                                ];
                                
                                // Tentukan step aktif berdasarkan status booking saat ini
                                $currentStatusCode = $booking->status->status_code;
                                $activeStepIndex = 0;
                                
                                foreach ($mainSteps as $index => $step) {
                                    if (in_array($currentStatusCode, $step['status_codes'])) {
                                        $activeStepIndex = $index;
                                        break;
                                    }
                                }
                            @endphp
                            
                            @foreach ($mainSteps as $index => $step)
                                <div class="tracking-step {{ $index < $activeStepIndex ? 'completed' : ($index == $activeStepIndex ? 'active' : '') }}">
                                    <div class="tracking-step-icon">
                                        <i class="{{ $step['icon'] }}"></i>
                                    </div>
                                    <div class="tracking-step-label">{{ $step['label'] }}</div>
                                    <div class="tracking-step-date">
                                        @if ($step['date'])
                                            {{ \Carbon\Carbon::parse($step['date'])->format('d Agustus Y') }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Booking Details -->
                    <div class="booking-details">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="d-flex">
                                            <img src="{{ $booking->service->image_url ?? asset('images/default-service.jpg') }}" alt="{{ $booking->service->title_service }}" class="booking-image me-3">
                                            <div>
                                                <h5 class="booking-service-title">{{ $booking->service->title_service }}</h5>
                                                <p class="booking-service-category">{{ $booking->service->category->name ?? 'Kategori' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <h5 class="booking-price">Rp{{ number_format($booking->service->base_price, 0, ',', '.') }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Customer Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Nama Pemesan</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $booking->nama_pemesan }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Alamat</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $booking->user->address ?? 'Alamat tidak tersedia' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">No Handphone</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $booking->user->phone ?? 'Nomor tidak tersedia' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Catatan Perbaikan</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $booking->catatan_perbaikan ?? 'Tidak ada catatan' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
