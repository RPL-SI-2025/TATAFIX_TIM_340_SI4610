@extends('layouts.app')

@section('title', 'Booking Berhasil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="mb-3">Booking Berhasil Dibuat!</h2>
                    <p class="lead mb-4">Terima kasih telah menggunakan layanan TataFix. Booking Anda telah berhasil dibuat dan menunggu pembayaran DP.</p>
                    
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i> Silakan lakukan pembayaran DP untuk melanjutkan proses booking Anda.
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Informasi Booking</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6 text-md-end fw-bold">ID Booking:</div>
                                <div class="col-md-6 text-md-start">{{ $booking->id }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 text-md-end fw-bold">Layanan:</div>
                                <div class="col-md-6 text-md-start">{{ $booking->service->title_service }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 text-md-end fw-bold">Tanggal:</div>
                                <div class="col-md-6 text-md-start">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 text-md-end fw-bold">Waktu:</div>
                                <div class="col-md-6 text-md-start">{{ $booking->waktu_booking }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 text-md-end fw-bold">Status:</div>
                                <div class="col-md-6 text-md-start">
                                    <x-booking.status-badge :status="$booking->status->status_code" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-3 d-md-flex justify-content-md-center">
                        <a href="{{ route('payment.dp.form', $booking->id) }}" class="btn btn-primary">
                            <i class="fas fa-credit-card me-2"></i> Bayar DP Sekarang
                        </a>
                        <a href="{{ route('booking.history') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-history me-2"></i> Riwayat Booking
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
