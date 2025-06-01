@extends('layouts.admin')

@section('title', 'Penugasan Tukang')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Penugasan Tukang untuk Booking #{{ $booking->id }}</h1>
        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

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

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Booking</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-1 font-weight-bold">ID Booking</p>
                        <p>{{ $booking->id }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1 font-weight-bold">Layanan</p>
                        <p>{{ $booking->service->title_service }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1 font-weight-bold">Kategori</p>
                        <p>{{ $booking->service->category->name }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1 font-weight-bold">Tanggal & Waktu</p>
                        <p>{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }} - {{ $booking->waktu_booking }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1 font-weight-bold">Alamat</p>
                        <p>{{ $booking->alamat }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1 font-weight-bold">Pelanggan</p>
                        <p>{{ $booking->user->name }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1 font-weight-bold">Status</p>
                        <p><span class="badge badge-info">{{ $booking->status->status_name }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Tukang Tersedia</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Filter:</div>
                            <a class="dropdown-item" href="{{ route('admin.bookings.assign.id', $booking->id) }}">Semua Tukang</a>
                            <a class="dropdown-item" href="{{ route('admin.bookings.assign.id', $booking->id) }}?specialization={{ $booking->service->category->name }}">Spesialisasi {{ $booking->service->category->name }}</a>
                            <a class="dropdown-item" href="{{ route('admin.bookings.assign.id', $booking->id) }}?sort=rating">Rating Tertinggi</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($tukangs->isEmpty())
                        <div class="alert alert-info">
                            Tidak ada tukang yang tersedia untuk saat ini.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Spesialisasi</th>
                                        <th>Rating</th>
                                        <th>Status</th>
                                        <th>Jumlah Pekerjaan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tukangs as $tukang)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img class="img-profile rounded-circle mr-2" src="{{ $tukang->profile_picture ? asset('storage/' . $tukang->profile_picture) : asset('img/undraw_profile.svg') }}" width="40">
                                                <div>
                                                    <div>{{ $tukang->name }}</div>
                                                    <small class="text-muted">{{ $tukang->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $tukang->specialization ?? 'Umum' }}</td>
                                        <td>
                                            @php
                                                $rating = $tukang->rating_avg ?? 0;
                                            @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @elseif($i <= $rating + 0.5)
                                                    <i class="fas fa-star-half-alt text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                            <span class="ml-1">{{ number_format($rating, 1) }}</span>
                                        </td>
                                        <td>
                                            @if($tukang->is_available)
                                                <span class="badge badge-success">Tersedia</span>
                                            @else
                                                <span class="badge badge-danger">Tidak Tersedia</span>
                                            @endif
                                        </td>
                                        <td>{{ $tukang->completed_bookings_count ?? 0 }} selesai</td>
                                        <td>
                                            <form action="{{ route('admin.bookings.assign.store', $booking->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="tukang_id" value="{{ $tukang->id }}">
                                                <button type="submit" class="btn btn-sm btn-primary" {{ !$tukang->is_available ? 'disabled' : '' }}>
                                                    <i class="fas fa-user-check"></i> Pilih
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination sudah ditangani oleh DataTables -->
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
        $('#dataTable').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "searching": true
        });
    });
</script>
@endpush
