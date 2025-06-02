@extends('layouts.app')

@section('title', 'Status Pesanan')

@section('styles')
<style>
    /* Timeline styling */
    .timeline-container {
        margin: 30px 0;
    }
    
    .timeline-track {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 40px;
    }
    
    .timeline-track::before {
        content: '';
        position: absolute;
        top: 25px;
        left: 0;
        width: 100%;
        height: 4px;
        background-color: #e0e0e0;
        z-index: 1;
    }
    
    .timeline-step {
        position: relative;
        z-index: 2;
        text-align: center;
        width: 20%;
    }
    
    .timeline-step-icon {
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
    
    .timeline-step.active .timeline-step-icon {
        background-color: #fd7e14;
        box-shadow: 0 0 15px rgba(253, 126, 20, 0.5);
        transform: scale(1.1);
    }
    
    .timeline-step.completed .timeline-step-icon {
        background-color: #28a745;
        box-shadow: 0 0 10px rgba(40, 167, 69, 0.3);
    }
    
    .timeline-step-label {
        font-weight: 600;
        margin-bottom: 5px;
        color: #495057;
    }
    
    .timeline-step-date {
        font-size: 14px;
        color: #6c757d;
    }
    
    /* Status badge styling */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .status-pending, .status-waiting_dp_validation, .status-waiting_final_validation {
        background-color: #FEF3C7;
        color: #92400E;
    }
    
    .status-waiting_tukang_assignment, .status-assigned {
        background-color: #DBEAFE;
        color: #1E40AF;
    }
    
    .status-in_process, .status-waiting_final_payment {
        background-color: #E0E7FF;
        color: #3730A3;
    }
    
    .status-completed {
        background-color: #D1FAE5;
        color: #065F46;
    }
    
    .status-cancelled, .status-rejected {
        background-color: #FEE2E2;
        color: #B91C1C;
    }
    
    /* Card styling */
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
    
    .detail-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }
    
    .detail-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    /* Status log styling */
    .status-log {
        position: relative;
        padding-left: 30px;
        padding-bottom: 20px;
        border-left: 2px solid #e0e0e0;
    }
    
    .status-log:last-child {
        border-left: 2px solid transparent;
    }
    
    .status-log-dot {
        position: absolute;
        left: -10px;
        top: 0;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #fd7e14;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #fd7e14;
    }
    
    .status-log-content {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-left: 15px;
    }
    
    .status-log-date {
        font-size: 14px;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
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
                    <!-- Status Badge -->
                    <div class="text-center mb-4">
                        @php
                            $statusCode = strtolower($booking->status->status_code);
                            $statusClass = 'status-' . $statusCode;
                            $displayName = $booking->status->display_name ?? ucwords(str_replace('_', ' ', $statusCode));
                        @endphp
                        <span class="status-badge {{ $statusClass }} mb-3">
                            {{ $displayName }}
                        </span>
                        <h5 class="text-muted">ID Booking: #{{ $booking->id }}</h5>
                    </div>
                
                    <!-- Timeline Tracking -->
                    <div class="timeline-container">
                        <div class="timeline-track">
                            @php
                                // Definisikan tahapan utama tracking
                                $mainSteps = [
                                    [
                                        'label' => 'Pesanan Dibuat',
                                        'icon' => 'fas fa-clipboard-check',
                                        'status_codes' => ['pending', 'waiting_dp_validation', 'dp_validated'],
                                        'date' => $booking->created_at
                                    ],
                                    [
                                        'label' => 'Tukang Ditugaskan',
                                        'icon' => 'fas fa-user-hard-hat',
                                        'status_codes' => ['waiting_tukang_assignment', 'assigned'],
                                        'date' => $booking->bookingLogs()
                                            ->whereHas('status', function($q) {
                                                $q->whereIn('status_code', ['assigned']);
                                            })->first() ? $booking->bookingLogs()
                                            ->whereHas('status', function($q) {
                                                $q->whereIn('status_code', ['assigned']);
                                            })->first()->created_at : null
                                    ],
                                    [
                                        'label' => 'Pengerjaan',
                                        'icon' => 'fas fa-tools',
                                        'status_codes' => ['in_process'],
                                        'date' => $booking->bookingLogs()
                                            ->whereHas('status', function($q) {
                                                $q->where('status_code', 'in_process');
                                            })->first() ? $booking->bookingLogs()
                                            ->whereHas('status', function($q) {
                                                $q->where('status_code', 'in_process');
                                            })->first()->created_at : null
                                    ],
                                    [
                                        'label' => 'Pelunasan',
                                        'icon' => 'fas fa-money-bill-wave',
                                        'status_codes' => ['waiting_final_payment', 'waiting_final_validation'],
                                        'date' => $booking->bookingLogs()
                                            ->whereHas('status', function($q) {
                                                $q->whereIn('status_code', ['waiting_final_payment', 'waiting_final_validation']);
                                            })->first() ? $booking->bookingLogs()
                                            ->whereHas('status', function($q) {
                                                $q->whereIn('status_code', ['waiting_final_payment', 'waiting_final_validation']);
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
                                $currentStatusCode = strtolower($booking->status->status_code);
                                $activeStepIndex = 0;
                                
                                foreach ($mainSteps as $index => $step) {
                                    if (in_array($currentStatusCode, $step['status_codes'])) {
                                        $activeStepIndex = $index;
                                        break;
                                    }
                                }
                            @endphp
                            
                            @foreach ($mainSteps as $index => $step)
                                <div class="timeline-step {{ $index < $activeStepIndex ? 'completed' : ($index == $activeStepIndex ? 'active' : '') }}">
                                    <div class="timeline-step-icon">
                                        <i class="{{ $step['icon'] }}"></i>
                                    </div>
                                    <div class="timeline-step-label">{{ $step['label'] }}</div>
                                    <div class="timeline-step-date">
                                        @if ($step['date'])
                                            {{ \Carbon\Carbon::parse($step['date'])->format('d M Y') }}
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
                    
                    <!-- Detail Information -->
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm detail-card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Informasi Pemesanan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <p class="text-muted mb-1">Nama Pemesan</p>
                                        <p class="fw-bold mb-0">{{ $booking->nama_pemesan }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <p class="text-muted mb-1">Alamat</p>
                                        <p class="fw-bold mb-0">{{ $booking->alamat ?? 'Alamat tidak tersedia' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <p class="text-muted mb-1">Tanggal & Waktu Booking</p>
                                        <p class="fw-bold mb-0">
                                            {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}
                                            <span class="ms-2">{{ $booking->waktu_booking }}</span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1">Catatan Perbaikan</p>
                                        <p class="fw-bold mb-0">{{ $booking->catatan_perbaikan ?? 'Tidak ada catatan' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm detail-card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Informasi Tukang</h5>
                                </div>
                                <div class="card-body">
                                    @if($booking->assigned_worker_id)
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="me-3">
                                                <img src="{{ $booking->worker->profile_photo_url ?? asset('images/default-avatar.jpg') }}" 
                                                    alt="Tukang" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                                            </div>
                                            <div>
                                                <h5 class="mb-1">{{ $booking->worker->name ?? 'Nama Tukang' }}</h5>
                                                <p class="text-muted mb-0">{{ $booking->worker->phone ?? 'No. Telepon tidak tersedia' }}</p>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-muted mb-1">Keahlian</p>
                                            <p class="fw-bold mb-0">{{ $booking->worker->skills ?? 'Informasi keahlian tidak tersedia' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-1">Pengalaman</p>
                                            <p class="fw-bold mb-0">{{ $booking->worker->experience ?? 'Informasi pengalaman tidak tersedia' }}</p>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <div class="mb-3">
                                                <i class="fas fa-user-clock fa-3x text-muted"></i>
                                            </div>
                                            <h5>Tukang Belum Ditugaskan</h5>
                                            <p class="text-muted">Tukang akan segera ditugaskan untuk pesanan Anda</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Log -->
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Riwayat Status</h5>
                        </div>
                        <div class="card-body p-4">
                            @if($booking->bookingLogs && $booking->bookingLogs->count() > 0)
                                <div class="status-logs">
                                    @foreach($booking->bookingLogs->sortByDesc('created_at') as $log)
                                        <div class="status-log">
                                            <div class="status-log-dot"></div>
                                            <div class="status-log-content">
                                                <h6 class="mb-1">{{ $log->status->display_name ?? ucwords(str_replace('_', ' ', $log->status->status_code)) }}</h6>
                                                <p class="mb-0">{{ $log->notes ?? 'Tidak ada catatan' }}</p>
                                                <small class="status-log-date">{{ $log->created_at->format('d M Y H:i') }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-center text-muted py-3">Belum ada riwayat status</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('booking.history') }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Riwayat
                        </a>
                        
                        @php
                            $statusCode = strtolower($booking->status->status_code);
                        @endphp
                        
                        @if($statusCode == 'completed' && !is_null($booking->rating))
                            <div class="card border-0 shadow-sm mt-4">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i> Ulasan Anda</h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <div class="d-inline-block bg-light px-4 py-2 rounded-pill">
                                            <div class="d-flex align-items-center">
                                                <div class="me-2 fw-bold">Rating:</div>
                                                <div class="rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $booking->rating ? 'text-warning' : 'text-secondary' }} mx-1"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted">Feedback Anda:</h6>
                                            <p class="card-text">{{ $booking->feedback }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($statusCode == 'completed' && is_null($booking->rating))
                            <div class="card border-0 shadow-sm mt-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-star me-2"></i> Beri Ulasan untuk Layanan Ini</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted mb-4">Terima kasih telah menggunakan layanan kami. Mohon berikan penilaian dan ulasan Anda untuk membantu kami meningkatkan kualitas layanan.</p>
                                    
                                    <form action="{{ route('booking.review.store', $booking->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="rating" class="form-label fw-bold">Rating</label>
                                            <div class="star-rating mb-3">
                                                <div class="d-flex justify-content-center">
                                                    <div class="rating-group">
                                                        <input type="radio" id="rating-5" name="rating" value="5" class="d-none">
                                                        <label for="rating-5" class="star-label fs-3 mx-1"><i class="fas fa-star"></i></label>
                                                        
                                                        <input type="radio" id="rating-4" name="rating" value="4" class="d-none">
                                                        <label for="rating-4" class="star-label fs-3 mx-1"><i class="fas fa-star"></i></label>
                                                        
                                                        <input type="radio" id="rating-3" name="rating" value="3" class="d-none">
                                                        <label for="rating-3" class="star-label fs-3 mx-1"><i class="fas fa-star"></i></label>
                                                        
                                                        <input type="radio" id="rating-2" name="rating" value="2" class="d-none">
                                                        <label for="rating-2" class="star-label fs-3 mx-1"><i class="fas fa-star"></i></label>
                                                        
                                                        <input type="radio" id="rating-1" name="rating" value="1" class="d-none">
                                                        <label for="rating-1" class="star-label fs-3 mx-1"><i class="fas fa-star"></i></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label for="feedback" class="form-label fw-bold">Feedback</label>
                                            <textarea class="form-control" id="feedback" name="feedback" rows="4" placeholder="Bagikan pengalaman Anda menggunakan layanan ini..."></textarea>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary px-4 py-2">
                                                <i class="fas fa-paper-plane me-2"></i> Kirim Ulasan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <style>
                                .star-rating .rating-group {
                                    display: flex;
                                }
                                .star-label {
                                    color: #ccc;
                                    cursor: pointer;
                                    transition: color 0.2s;
                                }
                                .star-label:hover,
                                .star-label:hover ~ .star-label,
                                input[name="rating"]:checked ~ label {
                                    color: #ffc107;
                                }
                                /* Reverse the order to make hover work from right to left */
                                .rating-group {
                                    direction: rtl;
                                }
                                .rating-group .star-label:hover,
                                .rating-group .star-label:hover ~ .star-label,
                                .rating-group input[name="rating"]:checked ~ label {
                                    color: #ffc107;
                                }
                            </style>
                        @elseif(in_array($statusCode, ['waiting_dp_validation', 'waiting_final_validation']))
                            <div class="card border-0 shadow-sm mt-4">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Informasi Pembayaran</h5>
                                </div>
                                <div class="card-body text-center">
                                    <p class="text-muted mb-4">Pembayaran Anda sedang dalam proses validasi oleh admin. Silakan cek status pembayaran untuk informasi lebih lanjut.</p>
                                    <a href="{{ route('payment.status', $booking->id) }}" class="btn btn-info px-4 py-2">
                                        <i class="fas fa-money-check me-2"></i> Lihat Status Pembayaran
                                    </a>
                                </div>
                            </div>
                        @elseif($statusCode == 'pending')
                            <div class="card border-0 shadow-sm mt-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-money-bill me-2"></i> Pembayaran DP</h5>
                                </div>
                                <div class="card-body text-center">
                                    <p class="text-muted mb-4">Silakan lakukan pembayaran DP untuk melanjutkan proses booking Anda.</p>
                                    <a href="{{ route('payment.dp.form', $booking->id) }}" class="btn btn-success px-4 py-2">
                                        <i class="fas fa-money-bill me-2"></i> Bayar DP Sekarang
                                    </a>
                                </div>
                            </div>
                        @elseif($statusCode == 'waiting_final_payment' || $statusCode == 'done')
                            <div class="card border-0 shadow-sm mt-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-money-bill me-2"></i> Pembayaran Pelunasan</h5>
                                </div>
                                <div class="card-body text-center">
                                    <p class="text-muted mb-4">Pekerjaan telah selesai. Silakan lakukan pembayaran pelunasan untuk menyelesaikan proses booking Anda.</p>
                                    <a href="{{ route('payment.final.form', $booking->id) }}" class="btn btn-success px-4 py-2">
                                        <i class="fas fa-money-bill me-2"></i> Bayar Pelunasan Sekarang
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
