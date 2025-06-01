@extends('layouts.app')

@section('title', 'Riwayat Booking')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Riwayat Booking Saya</h4>
                </div>
                <div class="card-body">
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
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="bookingHistoryTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID Booking</th>
                                        <th>Layanan</th>
                                        <th>Tanggal & Waktu</th>
                                        <th>Status</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                        <tr>
                                            <td>{{ $booking->id }}</td>
                                            <td>{{ $booking->service->title_service }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}<br>
                                                <small>{{ $booking->waktu_booking }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    $statusCode = $booking->status->status_code;
                                                    $badgeClass = 'secondary';
                                                    
                                                    if (in_array($statusCode, ['PENDING', 'WAITING_DP_VALIDATION', 'WAITING_FINAL_VALIDATION'])) {
                                                        $badgeClass = 'warning';
                                                    } elseif (in_array($statusCode, ['WAITING_TUKANG_ASSIGNMENT', 'ASSIGNED'])) {
                                                        $badgeClass = 'info';
                                                    } elseif (in_array($statusCode, ['IN_PROCESS', 'WAITING_FINAL_PAYMENT'])) {
                                                        $badgeClass = 'primary';
                                                    } elseif ($statusCode == 'COMPLETED') {
                                                        $badgeClass = 'success';
                                                    } elseif ($statusCode == 'CANCELLED') {
                                                        $badgeClass = 'danger';
                                                    }
                                                @endphp
                                                <span class="badge badge-{{ $badgeClass }}">{{ $booking->status->status_name }}</span>
                                            </td>
                                            <td>{{ $booking->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
                                                    
                                                    <a href="{{ route('booking.tracking', $booking->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-map-marker-alt"></i> Tracking
                                                    </a>
                                                    
                                                    @if($booking->status->status_code == 'PENDING')
                                                        <a href="{{ route('payment.dp.form', $booking->id) }}" class="btn btn-sm btn-success">
                                                            <i class="fas fa-money-bill"></i> Bayar DP
                                                        </a>
                                                    @elseif($booking->status->status_code == 'WAITING_FINAL_PAYMENT')
                                                        <a href="{{ route('payment.final.form', $booking->id) }}" class="btn btn-sm btn-success">
                                                            <i class="fas fa-money-bill"></i> Pelunasan
                                                        </a>
                                                    @elseif(in_array($booking->status->status_code, ['WAITING_DP_VALIDATION', 'WAITING_FINAL_VALIDATION', 'WAITING_TUKANG_ASSIGNMENT', 'ASSIGNED', 'IN_PROCESS']))
                                                        <a href="{{ route('payment.status', $booking->id) }}" class="btn btn-sm btn-secondary">
                                                            <i class="fas fa-info-circle"></i> Status Pembayaran
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#bookingHistoryTable').DataTable({
            "order": [[4, "desc"]], // Sort by created_at column (index 4) in descending order
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data yang tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "search": "Cari:",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    });
</script>
@endpush
