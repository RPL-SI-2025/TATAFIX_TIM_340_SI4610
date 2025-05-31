@extends('layouts.admin')

@section('title', 'Manajemen Booking')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Booking</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Booking</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Filter Status:</div>
                    <a class="dropdown-item" href="{{ route('admin.bookings.index') }}">Semua</a>
                    <a class="dropdown-item" href="{{ route('admin.bookings.index', ['status' => 'pending']) }}">Menunggu Pembayaran DP</a>
                    <a class="dropdown-item" href="{{ route('admin.bookings.index', ['status' => 'waiting_validation_dp']) }}">Menunggu Validasi DP</a>
                    <a class="dropdown-item" href="{{ route('admin.bookings.index', ['status' => 'dp_validated']) }}">DP Divalidasi</a>
                    <a class="dropdown-item" href="{{ route('admin.bookings.index', ['status' => 'in_progress']) }}">Sedang Dikerjakan</a>
                    <a class="dropdown-item" href="{{ route('admin.bookings.index', ['status' => 'waiting_validation_pelunasan']) }}">Menunggu Validasi Pelunasan</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Tanggal & Waktu</th>
                            <th>Status</th>
                            <th>Tukang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->user->name }}</td>
                            <td>{{ $booking->service->title_service }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}<br>
                                <small>{{ $booking->waktu_booking }}</small>
                            </td>
                            <td>
                                @php
                                    $statusCode = $booking->status->status_code;
                                    $badgeClass = 'secondary';
                                    
                                    if (in_array($statusCode, ['pending', 'waiting_pelunasan'])) {
                                        $badgeClass = 'warning';
                                    } elseif (in_array($statusCode, ['waiting_validation_dp', 'waiting_validation_pelunasan'])) {
                                        $badgeClass = 'info';
                                    } elseif (in_array($statusCode, ['dp_validated', 'in_progress'])) {
                                        $badgeClass = 'primary';
                                    } elseif (in_array($statusCode, ['done', 'completed'])) {
                                        $badgeClass = 'success';
                                    } elseif (in_array($statusCode, ['rejected', 'canceled'])) {
                                        $badgeClass = 'danger';
                                    }
                                @endphp
                                <span class="badge badge-{{ $badgeClass }}">{{ $booking->status->display_name }}</span>
                            </td>
                            <td>
                                @if($booking->tukang)
                                    {{ $booking->tukang->name }}
                                @else
                                    <span class="text-muted">Belum ditugaskan</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    
                                    @if($statusCode == 'waiting_validation_dp' || $statusCode == 'waiting_validation_pelunasan')
                                        <a href="{{ route('admin.payments.show', $booking->payments()->latest()->first()->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-money-bill"></i> Validasi Pembayaran
                                        </a>
                                    @endif
                                    
                                    @if($statusCode == 'dp_validated')
                                        <a href="{{ route('admin.bookings.assign', $booking->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-user-cog"></i> Tugaskan Tukang
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data booking</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "searching": true
        });
    });
</script>
@endpush
