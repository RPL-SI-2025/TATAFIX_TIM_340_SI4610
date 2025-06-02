@extends('layouts.app')

@section('title', 'Riwayat Booking')

@section('styles')
<style>
    .booking-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .booking-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
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
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="text-2xl font-bold text-blue-600 mb-4">Riwayat Booking Saya</h1>
            
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            @if($bookings->isEmpty())
                <div class="alert alert-info">
                    Anda belum memiliki riwayat booking. <a href="{{ route('services.index') }}" class="alert-link">Lihat layanan</a> untuk membuat booking baru.
                </div>
            @else
                <div class="row">
                    @foreach($bookings as $booking)
                        @php
                            $statusCode = strtolower($booking->status->status_code);
                            $statusClass = 'status-' . $statusCode;
                        @endphp
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card booking-card h-100 shadow-sm">
                                <div class="card-img-top position-relative">
                                    <img src="{{ $booking->service->image_url ?? '/images/default-service.jpg' }}"
                                        alt="{{ $booking->service->title_service }}"
                                        class="w-100" style="height: 180px; object-fit: cover;">
                                    <div class="position-absolute" style="bottom: 10px; left: 10px;">
                                        <span class="status-badge {{ $statusClass }}">
                                            {{ $booking->status->display_name ?? ucwords(str_replace('_', ' ', $statusCode)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title font-weight-bold">{{ $booking->service->title_service }}</h5>
                                    <p class="card-text text-muted mb-1">
                                        <i class="fas fa-calendar-alt mr-2"></i> 
                                        {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}
                                    </p>
                                    <p class="card-text text-muted mb-3">
                                        <i class="fas fa-clock mr-2"></i> 
                                        {{ $booking->waktu_booking }}
                                    </p>
                                    <div class="d-flex justify-content-between mt-3">
                                        <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        <a href="{{ route('booking.tracking', $booking->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-map-marker-alt"></i> Tracking
                                        </a>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('invoices.generate', $booking->id) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-file-invoice"></i> Invoice
                                        </a>
                                        
                                        @if($statusCode == 'pending')
                                            <a href="{{ route('payment.dp.form', $booking->id) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-money-bill"></i> Bayar DP
                                            </a>
                                        @elseif($statusCode == 'waiting_final_payment')
                                            <a href="{{ route('payment.final.form', $booking->id) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-money-bill"></i> Pelunasan
                                            </a>
                                        @elseif(in_array($statusCode, ['waiting_dp_validation', 'waiting_final_validation']))
                                            <a href="{{ route('payment.status', $booking->id) }}" class="btn btn-sm btn-secondary">
                                                <i class="fas fa-info-circle"></i> Status Pembayaran
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Tampilan tabel untuk layar kecil atau jika dibutuhkan -->
                <div class="d-block d-md-none mt-4">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Daftar Booking</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Layanan</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookings as $booking)
                                            @php
                                                $statusCode = strtolower($booking->status->status_code);
                                                $badgeClass = 'secondary';
                                                
                                                if (in_array($statusCode, ['pending', 'waiting_dp_validation', 'waiting_final_validation'])) {
                                                    $badgeClass = 'warning';
                                                } elseif (in_array($statusCode, ['waiting_tukang_assignment', 'assigned'])) {
                                                    $badgeClass = 'info';
                                                } elseif (in_array($statusCode, ['in_process', 'waiting_final_payment'])) {
                                                    $badgeClass = 'primary';
                                                } elseif ($statusCode == 'completed') {
                                                    $badgeClass = 'success';
                                                } elseif (in_array($statusCode, ['cancelled', 'rejected'])) {
                                                    $badgeClass = 'danger';
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $booking->service->title_service }}</td>
                                                <td>{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d/m/Y') }}</td>
                                                <td><span class="badge badge-{{ $badgeClass }}">{{ $booking->status->display_name }}</span></td>
                                                <td>
                                                    <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable jika diperlukan
        if ($.fn.DataTable) {
            $('#bookingHistoryTable').DataTable({
                "order": [[4, "desc"]], // Sort by created_at column descending
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        }
    });
</script>
@endsection
