@extends('layouts.app')

@section('title', 'Status Pembayaran')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('booking.history') }}">Riwayat Booking</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('booking.show', $booking->id) }}">Booking #{{ $booking->id }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Status Pembayaran</li>
                </ol>
            </nav>
            
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-money-check-alt me-2"></i>
                        <h4 class="mb-0">Status Pembayaran Booking #{{ $booking->id }}</h4>
                    </div>
                    <a href="{{ route('booking.tracking', $booking->id) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-map-marker-alt me-1"></i> Lihat Tracking
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light d-flex align-items-center">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            <h5 class="mb-0">Informasi Booking</h5>
                        </div>
                        <div class="card-body">
                            @php
                                // Karena tidak ada kolom payment_type, kita menggunakan urutan pembayaran
                                // Pembayaran pertama adalah DP, pembayaran kedua adalah pelunasan
                                $payments = $booking->payments()->orderBy('created_at', 'asc')->get();
                                $dpPayment = $payments->first();
                                $finalPayment = $payments->count() > 1 ? $payments->get(1) : null;
                                
                                $dpAmount = $dpPayment ? $dpPayment->amount : 0;
                                $finalAmount = $finalPayment ? $finalPayment->amount : 0;
                                $totalPaid = $dpAmount + $finalAmount;
                                $remainingAmount = $booking->service->base_price - $totalPaid;
                                $paymentPercentage = ($totalPaid / $booking->service->base_price) * 100;
                            @endphp
                            
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
                                            <label class="text-muted small">Status Booking</label>
                                            <p class="fw-bold mb-0">
                                                @php
                                                    $statusColor = 'secondary';
                                                    if(in_array($booking->status->status_code, ['COMPLETED'])) {
                                                        $statusColor = 'success';
                                                    } elseif(in_array($booking->status->status_code, ['CANCELLED'])) {
                                                        $statusColor = 'danger';
                                                    } elseif(in_array($booking->status->status_code, ['PENDING', 'WAITING_DP_VALIDATION', 'WAITING_FINAL_PAYMENT', 'WAITING_FINAL_VALIDATION'])) {
                                                        $statusColor = 'warning';
                                                    } elseif(in_array($booking->status->status_code, ['WAITING_TUKANG_ASSIGNMENT', 'ASSIGNED', 'IN_PROCESS'])) {
                                                        $statusColor = 'info';
                                                    }
                                                @endphp
                                                <span class="badge bg-{{ $statusColor }}">{{ $booking->status->status_name }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-box bg-light rounded-circle p-3 me-3">
                                            <i class="fas fa-money-bill-wave text-primary"></i>
                                        </div>
                                        <div>
                                            <label class="text-muted small">Total Biaya</label>
                                            <p class="fw-bold fs-5 mb-0">Rp {{ number_format($booking->service->base_price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="payment-progress-container mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="text-muted small">Progress Pembayaran</label>
                                            <span class="badge bg-primary">{{ number_format($paymentPercentage, 0) }}%</span>
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $paymentPercentage }}%" 
                                                aria-valuenow="{{ $paymentPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <small>Dibayar: Rp {{ number_format($totalPaid, 0, ',', '.') }}</small>
                                            <small>Sisa: Rp {{ number_format($remainingAmount, 0, ',', '.') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light d-flex align-items-center">
                            <i class="fas fa-history text-primary me-2"></i>
                            <h5 class="mb-0">Riwayat Pembayaran</h5>
                        </div>
                        <div class="card-body">
                            @if($payments->isEmpty())
                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>
                                        Belum ada riwayat pembayaran untuk booking ini.
                                    </div>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Jenis</th>
                                                <th>Metode</th>
                                                <th>Jumlah</th>
                                                <th>Status</th>
                                                <th class="text-center">Bukti</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($payments as $payment)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="icon-box bg-light rounded-circle p-2 me-2">
                                                                <i class="fas fa-calendar-day text-primary"></i>
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold">{{ $payment->created_at->format('d M Y') }}</div>
                                                                <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @php
                                                            // Karena tidak ada kolom payment_type, kita tentukan jenis pembayaran berdasarkan urutan
                                                            $isDP = $payments->first() && $payments->first()->id == $payment->id;
                                                        @endphp
                                                        @if($isDP)
                                                            <span class="badge bg-info py-2 px-3">Down Payment</span>
                                                        @else
                                                            <span class="badge bg-success py-2 px-3">Pelunasan</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if($payment->payment_method == 'bank_transfer')
                                                                <i class="fas fa-university me-2 text-primary"></i>
                                                            @else
                                                                <i class="fas fa-wallet me-2 text-primary"></i>
                                                            @endif
                                                            {{ $payment->payment_method == 'bank_transfer' ? 'Transfer Bank' : 'E-Wallet' }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                                    </td>
                                                    <td>
                                                        @if($payment->status == 'pending')
                                                            <span class="badge bg-warning py-2 px-3">
                                                                <i class="fas fa-clock me-1"></i> Menunggu Validasi
                                                            </span>
                                                        @elseif($payment->status == 'approved')
                                                            <span class="badge bg-success py-2 px-3">
                                                                <i class="fas fa-check-circle me-1"></i> Disetujui
                                                            </span>
                                                        @elseif($payment->status == 'rejected')
                                                            <span class="badge bg-danger py-2 px-3">
                                                                <i class="fas fa-times-circle me-1"></i> Ditolak
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if($payment->proof_of_payment)
                                                            <a href="{{ asset('storage/' . $payment->proof_of_payment) }}" target="_blank" class="btn btn-sm btn-primary rounded-pill">
                                                                <i class="fas fa-eye me-1"></i> Lihat Bukti
                                                            </a>
                                                        @else
                                                            <span class="badge bg-secondary">Tidak ada</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light d-flex align-items-center">
                            <i class="fas fa-tasks text-primary me-2"></i>
                            <h5 class="mb-0">Langkah Selanjutnya</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $statusCode = $booking->status->status_code;
                            @endphp
                            
                            @if($statusCode == 'PENDING')
                                <div class="alert alert-warning d-flex align-items-start border-0 shadow-sm">
                                    <div class="alert-icon me-3">
                                        <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading">Pembayaran DP Diperlukan</h5>
                                        <p>Anda belum melakukan pembayaran DP. Silakan lakukan pembayaran DP untuk melanjutkan proses booking.</p>
                                        <a href="{{ route('payment.dp.form', $booking->id) }}" class="btn btn-warning px-4 py-2">
                                            <i class="fas fa-credit-card me-2"></i>Bayar DP Sekarang
                                        </a>
                                    </div>
                                </div>
                            @elseif($statusCode == 'WAITING_DP_VALIDATION')
                                <div class="alert alert-info d-flex align-items-start border-0 shadow-sm">
                                    <div class="alert-icon me-3">
                                        <i class="fas fa-hourglass-half fa-2x text-info"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading">Menunggu Validasi DP</h5>
                                        <p>Pembayaran DP Anda sedang dalam proses validasi oleh admin. Mohon tunggu konfirmasi selanjutnya.</p>
                                        <div class="d-flex align-items-center mt-2">
                                            <div class="spinner-border spinner-border-sm text-info me-2" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <span class="text-muted">Proses validasi biasanya membutuhkan waktu 1x24 jam</span>
                                        </div>
                                    </div>
                                </div>
                            @elseif($statusCode == 'WAITING_TUKANG_ASSIGNMENT')
                                <div class="alert alert-info d-flex align-items-start border-0 shadow-sm">
                                    <div class="alert-icon me-3">
                                        <i class="fas fa-hard-hat fa-2x text-info"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading">Mencari Tukang</h5>
                                        <p>Pembayaran DP Anda telah divalidasi. Tim kami sedang mencari tukang yang tersedia untuk layanan Anda.</p>
                                        <div class="d-flex align-items-center mt-2">
                                            <div class="spinner-border spinner-border-sm text-info me-2" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <span class="text-muted">Proses pencarian tukang biasanya membutuhkan waktu 1-2 hari</span>
                                        </div>
                                    </div>
                                </div>
                            @elseif($statusCode == 'ASSIGNED' || $statusCode == 'IN_PROCESS')
                                <div class="alert alert-info d-flex align-items-start border-0 shadow-sm">
                                    <div class="alert-icon me-3">
                                        <i class="fas fa-tools fa-2x text-info"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading">Layanan Sedang Dikerjakan</h5>
                                        <p>Layanan Anda sedang dalam proses pengerjaan oleh tukang kami. Anda akan mendapatkan notifikasi saat layanan selesai.</p>
                                        <div class="progress mt-3" style="height: 10px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($statusCode == 'WAITING_FINAL_PAYMENT')
                                <div class="alert alert-warning d-flex align-items-start border-0 shadow-sm">
                                    <div class="alert-icon me-3">
                                        <i class="fas fa-file-invoice-dollar fa-2x text-warning"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading">Pelunasan Diperlukan</h5>
                                        <p>Layanan telah selesai dikerjakan. Silakan lakukan pelunasan pembayaran.</p>
                                        <a href="{{ route('payment.final.form', $booking->id) }}" class="btn btn-warning px-4 py-2">
                                            <i class="fas fa-credit-card me-2"></i>Bayar Pelunasan Sekarang
                                        </a>
                                    </div>
                                </div>
                            @elseif($statusCode == 'WAITING_FINAL_VALIDATION')
                                <div class="alert alert-info d-flex align-items-start border-0 shadow-sm">
                                    <div class="alert-icon me-3">
                                        <i class="fas fa-hourglass-half fa-2x text-info"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading">Menunggu Validasi Pelunasan</h5>
                                        <p>Pembayaran pelunasan Anda sedang dalam proses validasi oleh admin. Mohon tunggu konfirmasi selanjutnya.</p>
                                        <div class="d-flex align-items-center mt-2">
                                            <div class="spinner-border spinner-border-sm text-info me-2" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <span class="text-muted">Proses validasi biasanya membutuhkan waktu 1x24 jam</span>
                                        </div>
                                    </div>
                                </div>
                            @elseif($statusCode == 'COMPLETED')
                                <div class="alert alert-success d-flex align-items-start border-0 shadow-sm">
                                    <div class="alert-icon me-3">
                                        <i class="fas fa-check-circle fa-2x text-success"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading">Booking Selesai!</h5>
                                        <p>Booking telah selesai! Terima kasih telah menggunakan layanan TataFix.</p>
                                        <a href="{{ route('services.index') }}" class="btn btn-success px-4 py-2">
                                            <i class="fas fa-plus-circle me-2"></i>Pesan Layanan Lainnya
                                        </a>
                                    </div>
                                </div>
                            @elseif($statusCode == 'CANCELLED')
                                <div class="alert alert-danger d-flex align-items-start border-0 shadow-sm">
                                    <div class="alert-icon me-3">
                                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading">Booking Dibatalkan</h5>
                                        <p>Booking ini telah dibatalkan.</p>
                                        <a href="{{ route('services.index') }}" class="btn btn-danger px-4 py-2">
                                            <i class="fas fa-plus-circle me-2"></i>Pesan Layanan Baru
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4 mb-2">
                        <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-outline-primary px-4 py-2">
                            <i class="fas fa-info-circle me-2"></i> Detail Booking
                        </a>
                        <a href="{{ route('booking.history') }}" class="btn btn-outline-secondary px-4 py-2">
                            <i class="fas fa-history me-2"></i> Riwayat Booking
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
    
    /* Alert Icon */
    .alert-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
</style>
@endpush

@endsection
