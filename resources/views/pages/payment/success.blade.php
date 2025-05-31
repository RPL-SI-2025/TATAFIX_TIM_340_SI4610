@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('booking.show', $booking->id) }}">Booking #{{ $booking->id }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pembayaran Berhasil</li>
                </ol>
            </nav>
            
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-success text-white py-3 d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <h4 class="mb-0">Pembayaran Berhasil Dikirim</h4>
                </div>
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <div class="success-animation">
                            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                            </svg>
                        </div>
                    </div>
                    
                    <h2 class="fw-bold mb-3">Terima Kasih!</h2>
                    <p class="lead fs-4 mb-4">Bukti pembayaran Anda telah berhasil dikirim.</p>
                    <div class="d-flex justify-content-center mb-4">
                        <div class="badge bg-success fs-6 py-2 px-3">
                            <i class="fas fa-clock me-1"></i> Menunggu Validasi Admin
                        </div>
                    </div>
                    
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light d-flex align-items-center">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            <h5 class="mb-0">Informasi Booking #{{ $booking->id }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-box bg-light rounded-circle p-3 me-3">
                                            <i class="fas fa-tools text-primary"></i>
                                        </div>
                                        <div>
                                            <label class="text-muted small">Layanan</label>
                                            <p class="fw-bold mb-0">{{ $booking->service->title_service }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-box bg-light rounded-circle p-3 me-3">
                                            <i class="fas fa-calendar-alt text-primary"></i>
                                        </div>
                                        <div>
                                            <label class="text-muted small">Tanggal</label>
                                            <p class="fw-bold mb-0">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-light rounded-circle p-3 me-3">
                                            <i class="fas fa-clock text-primary"></i>
                                        </div>
                                        <div>
                                            <label class="text-muted small">Waktu</label>
                                            <p class="fw-bold mb-0">{{ $booking->waktu_booking }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-box bg-light rounded-circle p-3 me-3">
                                            <i class="fas fa-tag text-primary"></i>
                                        </div>
                                        <div>
                                            <label class="text-muted small">Status</label>
                                            <p class="fw-bold mb-0">{{ $booking->status->status_name }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($payment)
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-box bg-light rounded-circle p-3 me-3">
                                            <i class="fas fa-money-bill-wave text-primary"></i>
                                        </div>
                                        <div>
                                            <label class="text-muted small">Jenis Pembayaran</label>
                                            <p class="fw-bold mb-0">{{ $payment->payment_type == 'dp' ? 'Down Payment (DP)' : 'Pelunasan' }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-box bg-light rounded-circle p-3 me-3">
                                            <i class="fas fa-coins text-primary"></i>
                                        </div>
                                        <div>
                                            <label class="text-muted small">Jumlah</label>
                                            <p class="fw-bold fs-5 mb-0">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-light rounded-circle p-3 me-3">
                                            <i class="fas fa-credit-card text-primary"></i>
                                        </div>
                                        <div>
                                            <label class="text-muted small">Metode</label>
                                            <p class="fw-bold mb-0">{{ $payment->payment_method == 'bank_transfer' ? 'Transfer Bank' : 'E-Wallet' }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-warning rounded-circle p-3 me-3">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                                <div class="text-start">
                                    <h5 class="mb-1">Langkah Selanjutnya</h5>
                                    <p class="mb-0">Tim admin kami akan melakukan validasi pembayaran Anda dalam waktu <strong>1x24 jam</strong>.</p>
                                </div>
                            </div>
                            <p class="text-start">Anda dapat memeriksa status pembayaran dan booking melalui halaman status booking.</p>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-3 col-lg-8 mx-auto mt-4">
                        <a href="{{ route('payment.status', $booking->id) }}" class="btn btn-primary btn-lg py-3">
                            <i class="fas fa-search me-2"></i>Lihat Status Pembayaran
                        </a>
                        <a href="{{ route('booking.history') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-history me-2"></i>Kembali ke Riwayat Booking
                        </a>
                    </div>
                </div>
                <div class="card-footer bg-light py-3 text-center">
                    <small class="text-muted">Jika Anda memiliki pertanyaan, silakan hubungi customer service kami di <strong>0812-3456-7890</strong></small>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Add FontAwesome if not already included
    if (!document.querySelector('link[href*="fontawesome"]')) {
        const fontAwesome = document.createElement('link');
        fontAwesome.rel = 'stylesheet';
        fontAwesome.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css';
        document.head.appendChild(fontAwesome);
    }
</script>
@endpush

@push('styles')
<style>
    /* Success Animation */
    .success-animation {
        margin: 0 auto;
        width: 150px;
        height: 150px;
    }
    
    .checkmark {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: block;
        stroke-width: 2;
        stroke: #4bb71b;
        stroke-miterlimit: 10;
        box-shadow: 0 0 20px #4bb71b;
        animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        margin: 0 auto;
    }
    
    .checkmark__circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 2;
        stroke-miterlimit: 10;
        stroke: #4bb71b;
        fill: none;
        animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }
    
    .checkmark__check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }
    
    @keyframes stroke {
        100% {
            stroke-dashoffset: 0;
        }
    }
    
    @keyframes scale {
        0%, 100% {
            transform: none;
        }
        50% {
            transform: scale3d(1.1, 1.1, 1);
        }
    }
    
    @keyframes fill {
        100% {
            box-shadow: inset 0px 0px 0px 30px rgba(75, 183, 27, 0.1);
        }
    }
    
    /* Icon Box */
    .icon-box {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .icon-box i {
        font-size: 1.25rem;
    }
</style>
@endpush

@endsection
