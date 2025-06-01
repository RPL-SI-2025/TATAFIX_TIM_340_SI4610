@extends('layouts.app')

@section('title', 'Pembayaran DP')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('booking.show', $booking->id) }}">Booking #{{ $booking->id }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pembayaran DP</li>
                </ol>
            </nav>
            
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Pembayaran DP Booking #{{ $booking->id }}</h4>
                </div>
                <div class="card-body p-4">
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light d-flex align-items-center">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            <h5 class="mb-0">Informasi Booking</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">Layanan</label>
                                        <p class="fw-bold mb-0">{{ $booking->service->title_service }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small">Tanggal</label>
                                        <p class="fw-bold mb-0">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small">Waktu</label>
                                        <p class="fw-bold mb-0">{{ $booking->waktu_booking }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">Total Biaya</label>
                                        <p class="fw-bold fs-5 mb-0">Rp {{ number_format($booking->service->base_price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small">Jumlah DP (50%)</label>
                                        <p class="fw-bold fs-5 text-primary mb-0">Rp {{ number_format($booking->service->base_price * 0.5, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('payment.dp.process', $booking->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-light d-flex align-items-center">
                                <i class="fas fa-credit-card text-primary me-2"></i>
                                <h5 class="mb-0">Detail Pembayaran</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="payment_method" class="form-label fw-bold">Metode Pembayaran</label>
                                        <div class="input-group mb-1">
                                            <span class="input-group-text"><i class="fas fa-wallet"></i></span>
                                            <select class="form-select form-select-lg @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                                <option value="bank_transfer" selected>Transfer Bank</option>
                                            </select>
                                            @error('payment_method')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="amount" class="form-label fw-bold">Jumlah Pembayaran</label>
                                        <div class="input-group mb-1">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control form-control-lg @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ round($booking->service->base_price * 0.5) }}" readonly required>
                                            @error('amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-text"><i class="fas fa-info-circle"></i> Pembayaran DP ditetapkan sebesar 50% dari total biaya.</div>
                                    </div>

                                    <div class="col-12 mt-3">
                                        <label for="proof_of_payment" class="form-label fw-bold">Bukti Pembayaran</label>
                                        <div class="input-group mb-1">
                                            <span class="input-group-text"><i class="fas fa-file-image"></i></span>
                                            <input type="file" class="form-control @error('proof_of_payment') is-invalid @enderror" id="proof_of_payment" name="proof_of_payment" accept="image/jpeg,image/png,image/jpg" required>
                                            @error('proof_of_payment')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-text"><i class="fas fa-exclamation-triangle"></i> Upload bukti pembayaran dalam format JPG, PNG, atau JPEG (max 2MB).</div>
                                    </div>

                                    <div class="col-12 mt-3">
                                        <label for="payment_notes" class="form-label fw-bold">Catatan Pembayaran (Opsional)</label>
                                        <div class="input-group mb-1">
                                            <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
                                            <textarea class="form-control @error('payment_notes') is-invalid @enderror" id="payment_notes" name="payment_notes" rows="3" placeholder="Tambahkan catatan jika diperlukan">{{ old('payment_notes') }}</textarea>
                                            @error('payment_notes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-light d-flex align-items-center">
                                <i class="fas fa-university text-primary me-2"></i>
                                <h5 class="mb-0">Informasi Rekening</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-3">Silakan transfer pembayaran DP ke salah satu rekening berikut:</p>
                                
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="card h-100 border-primary">
                                            <div class="card-body text-center">
                                                <img src="https://upload.wikimedia.org/wikipedia/id/thumb/5/5c/Bank_Central_Asia.svg/200px-Bank_Central_Asia.svg.png" alt="BCA" class="img-fluid mb-2" style="height: 40px;">
                                                <p class="fw-bold mb-1">1234567890</p>
                                                <p class="small text-muted mb-0">a/n PT TataFix Indonesia</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card h-100 border-primary">
                                            <div class="card-body text-center">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/200px-Bank_Mandiri_logo_2016.svg.png" alt="Mandiri" class="img-fluid mb-2" style="height: 40px;">
                                                <p class="fw-bold mb-1">0987654321</p>
                                                <p class="small text-muted mb-0">a/n PT TataFix Indonesia</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card h-100 border-primary">
                                            <div class="card-body text-center">
                                                <img src="https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/200px-BNI_logo.svg.png" alt="BNI" class="img-fluid mb-2" style="height: 40px;">
                                                <p class="fw-bold mb-1">1122334455</p>
                                                <p class="small text-muted mb-0">a/n PT TataFix Indonesia</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg py-3"><i class="fas fa-paper-plane me-2"></i>Kirim Bukti Pembayaran</button>
                            <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light py-3 text-center">
                    <small class="text-muted">Jika Anda mengalami kesulitan dalam proses pembayaran, silakan hubungi customer service kami di <strong>0812-3456-7890</strong></small>
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
    
    // Form validation
    (function() {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    // Tambahkan loading state pada tombol submit
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengirim...';
                        submitBtn.disabled = true;
                    }
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
@endpush

@endsection
