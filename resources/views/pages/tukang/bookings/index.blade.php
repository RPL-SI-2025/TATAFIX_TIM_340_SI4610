@extends('layouts.app')

@section('title', 'Daftar Penugasan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Daftar Penugasan Saya</h4>
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

                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle mr-2"></i> Berikut adalah daftar semua penugasan yang ditugaskan kepada Anda. Klik tombol "Detail" untuk melihat informasi lengkap dan melakukan tindakan pada penugasan.
                    </div>

                    @if($bookings->isEmpty())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Belum ada penugasan yang ditugaskan kepada Anda.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID Booking</th>
                                        <th>Layanan</th>
                                        <th>Pelanggan</th>
                                        <th>Tanggal & Waktu</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                        <tr>
                                            <td>{{ $booking->id }}</td>
                                            <td>{{ optional($booking->service)->title_service ?? 'Layanan tidak tersedia' }}</td>
                                            <td>{{ $booking->user->name }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}<br>
                                                <small>{{ $booking->waktu_booking }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    $statusCode = strtolower($booking->status_code ?: $booking->status->status_code);
                                                    $badgeClass = 'badge-secondary';
                                                    
                                                    if (in_array($statusCode, ['waiting_tukang_response'])) {
                                                        $badgeClass = 'badge-warning';
                                                    } elseif (in_array($statusCode, ['in_progress'])) {
                                                        $badgeClass = 'badge-primary';
                                                    } elseif (in_array($statusCode, ['done', 'completed'])) {
                                                        $badgeClass = 'badge-success';
                                                    } elseif (in_array($statusCode, ['waiting_final_payment', 'waiting_validation_pelunasan'])) {
                                                        $badgeClass = 'badge-info';
                                                    } elseif (in_array($statusCode, ['rejected', 'canceled'])) {
                                                        $badgeClass = 'badge-danger';
                                                    }
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">
                                                    {{ $booking->status->display_name }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('tukang.bookings.show', $booking->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
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
        // Log jumlah booking untuk debugging
        console.log('Total bookings: {{ $bookings->count() }}');
    });
</script>
@endpush
