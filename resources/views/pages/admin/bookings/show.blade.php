@extends('layouts.admin')

@section('title', 'Detail Booking')

@section('styles')
<style>
    /* Icon Circle Styling */
    .icon-circle {
        height: 40px;
        width: 40px;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Badge Styling */
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    
    /* Info Item Styling */
    .info-item .small {
        font-size: 0.8rem;
        margin-bottom: 2px;
    }
    
    /* Table Styling */
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.075);
    }
    
    /* Card Border Left Enhancement */
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    
    /* Avatar Styling */
    .avatar {
        height: 40px;
        width: 40px;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Print Styling */
    @media print {
        .btn, .card-header button, .no-print {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-body {
            padding: 0 !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Detail Booking</h1>
            <nav aria-label="breadcrumb" class="mt-1">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}">Booking</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail #{{ $booking->id }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-secondary mr-2">
                <i class="fas fa-arrow-left fa-sm"></i> Kembali
            </a>
            <a href="#" class="btn btn-sm btn-primary" onclick="window.print()">
                <i class="fas fa-print fa-sm"></i> Cetak
            </a>
        </div>
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

    <!-- Status Timeline Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-left-primary">
                <div class="card-body p-4">
                    @php
                        $statusCode = $booking->status->status_code;
                        $badgeClass = 'secondary';
                        $statusIcon = 'fa-info-circle';
                        
                        if (in_array($statusCode, ['pending', 'waiting_pelunasan'])) {
                            $badgeClass = 'warning';
                            $statusIcon = 'fa-clock';
                        } elseif (in_array($statusCode, ['waiting_validation_dp', 'waiting_validation_pelunasan'])) {
                            $badgeClass = 'info';
                            $statusIcon = 'fa-money-bill-wave';
                        } elseif (in_array($statusCode, ['dp_validated'])) {
                            $badgeClass = 'info';
                            $statusIcon = 'fa-check-circle';
                        } elseif (in_array($statusCode, ['in_progress'])) {
                            $badgeClass = 'primary';
                            $statusIcon = 'fa-tools';
                        } elseif ($statusCode == 'done') {
                            $badgeClass = 'primary';
                            $statusIcon = 'fa-clipboard-check';
                        } elseif ($statusCode == 'completed') {
                            $badgeClass = 'success';
                            $statusIcon = 'fa-check-double';
                        } elseif (in_array($statusCode, ['rejected', 'canceled'])) {
                            $badgeClass = 'danger';
                            $statusIcon = 'fa-times-circle';
                        }
                    @endphp
                    
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <div class="icon-circle bg-{{ $badgeClass }}">
                                <i class="fas {{ $statusIcon }} text-white"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">Status Saat Ini</div>
                            <span class="font-weight-bold">{{ $booking->status->display_name }}</span>
                        </div>
                        <div class="ml-auto">
                            <span class="badge badge-{{ $badgeClass }} py-2 px-3">
                                <i class="fas {{ $statusIcon }} mr-1"></i> {{ $booking->status->status_code }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Booking</h6>
                    <span class="badge badge-light">ID: #{{ $booking->id }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <div class="small text-gray-500">Tanggal Booking</div>
                                <div class="font-weight-bold">
                                    <i class="far fa-calendar-alt mr-1 text-primary"></i>
                                    {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}
                                </div>
                            </div>
                            
                            <div class="info-item mb-3">
                                <div class="small text-gray-500">Waktu Booking</div>
                                <div class="font-weight-bold">
                                    <i class="far fa-clock mr-1 text-primary"></i>
                                    {{ $booking->waktu_booking }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <div class="small text-gray-500">Layanan</div>
                                <div class="font-weight-bold">{{ $booking->service->title_service }}</div>
                            </div>
                            
                            <div class="info-item mb-3">
                                <div class="small text-gray-500">Kategori</div>
                                <div class="font-weight-bold">
                                    <i class="fas fa-tag mr-1 text-primary"></i>
                                    {{ $booking->service->category->name ?? 'Tidak ada kategori' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="mb-1 font-weight-bold">Alamat</p>
                            <p>{{ $booking->alamat }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="mb-1 font-weight-bold">Catatan</p>
                            <p>{{ $booking->notes ?? 'Tidak ada catatan' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Layanan</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 font-weight-bold">Nama Layanan</p>
                            <p>{{ $booking->service->title_service }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 font-weight-bold">Kategori</p>
                            <p>{{ $booking->service->category->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 font-weight-bold">Harga Dasar</p>
                            <p>Rp {{ number_format($booking->service->base_price, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 font-weight-bold">Penyedia Layanan</p>
                            <p>{{ $booking->service->provider->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="mb-1 font-weight-bold">Deskripsi Layanan</p>
                            <p>{{ $booking->service->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4 border-left-success">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Pembayaran</h6>
                    <span class="badge badge-light">{{ $booking->payments->count() }} Transaksi</span>
                </div>
                <div class="card-body">
                    @if($booking->payments->isEmpty())
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-money-bill-wave fa-4x text-success opacity-50"></i>
                            </div>
                            <p class="mb-0 text-muted">Belum ada riwayat pembayaran untuk booking ini.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tanggal</th>
                                        <th>Jenis</th>
                                        <th>Metode</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($booking->payments as $payment)
                                        <tr>
                                            <td><span class="font-weight-bold">#{{ $payment->id }}</span></td>
                                            <td>
                                                <div class="small text-muted">{{ $payment->created_at->format('d M Y') }}</div>
                                                <div>{{ $payment->created_at->format('H:i') }}</div>
                                            </td>
                                            <td>
                                                @php
                                                    // Tentukan jenis pembayaran berdasarkan urutan
                                                    $paymentIndex = $booking->payments->sortBy('created_at')->search(function($item) use ($payment) {
                                                        return $item->id === $payment->id;
                                                    });
                                                    $isDP = $paymentIndex === 0;
                                                @endphp
                                                @if($isDP)
                                                    <span class="badge badge-info">Down Payment</span>
                                                @else
                                                    <span class="badge badge-success">Pelunasan</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($payment->payment_method == 'bank_transfer')
                                                    <span><i class="fas fa-university mr-1"></i> Transfer Bank</span>
                                                @else
                                                    <span><i class="fas fa-wallet mr-1"></i> E-Wallet</span>
                                                @endif
                                            </td>
                                            <td class="font-weight-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                            <td>
                                                @if($payment->status == 'pending')
                                                    <span class="badge badge-warning py-2 px-3">
                                                        <i class="fas fa-clock mr-1"></i> Menunggu Validasi
                                                    </span>
                                                @elseif($payment->status == 'approved')
                                                    <span class="badge badge-success py-2 px-3">
                                                        <i class="fas fa-check-circle mr-1"></i> Disetujui
                                                    </span>
                                                @elseif($payment->status == 'rejected')
                                                    <span class="badge badge-danger py-2 px-3">
                                                        <i class="fas fa-times-circle mr-1"></i> Ditolak
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-sm btn-primary">
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

            <!-- Review Card -->
            @if($booking->status->status_code == 'completed')
            <div class="card shadow mb-4 border-left-warning">
                <div class="card-header py-3 d-flex align-items-center">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-star mr-2"></i> Review Customer
                    </h6>
                </div>
                <div class="card-body">
                    @if(!is_null($booking->rating))
                        <div class="text-center mb-3">
                            <div class="d-inline-block bg-light px-4 py-2 rounded">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="mr-2 font-weight-bold">Rating:</div>
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
                                <h6 class="card-subtitle mb-2 text-muted">Feedback Customer:</h6>
                                <p class="card-text">{{ $booking->feedback }}</p>
                            </div>
                        </div>
                        <div class="text-muted small mt-2 text-center">
                            <i class="fas fa-clock mr-1"></i> Diberikan pada: {{ $booking->updated_at->format('d M Y H:i') }}
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle mr-2"></i> Customer belum memberikan review untuk booking ini.
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Card untuk konten lain jika diperlukan di masa depan -->

        </div>

        <div class="col-lg-4">
            <!-- Aksi Card -->
            <div class="card shadow mb-4 border-left-primary">
                <div class="card-header py-3 d-flex align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        @php
                            $statusCode = $booking->status->status_code;
                        @endphp
                        
                        @if($statusCode == 'waiting_validation_dp' && $booking->payments()->latest()->first())
                            <a href="{{ route('admin.payments.show', $booking->payments()->latest()->first()->id) }}" class="btn btn-info btn-block mb-2">
                                <i class="fas fa-check-circle mr-1"></i> Validasi Pembayaran DP
                            </a>
                        @endif
                        
                        @if($statusCode == 'waiting_validation_pelunasan' && $booking->payments()->latest()->first())
                            <a href="{{ route('admin.payments.show', $booking->payments()->latest()->first()->id) }}" class="btn btn-success btn-block mb-2">
                                <i class="fas fa-check-double mr-1"></i> Validasi Pelunasan
                            </a>
                        @endif
                        
                        @if($statusCode == 'dp_validated' && !$booking->tukang)
                            <button type="button" class="btn btn-primary btn-block mb-2" data-toggle="modal" data-target="#assignTukangModal">
                                <i class="fas fa-user-plus mr-1"></i> Pilih Tukang
                            </button>
                        @endif
                        
                        <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-edit mr-1"></i> Edit Booking
                        </a>
                        
                        <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#deleteBookingModal">
                            <i class="fas fa-trash mr-1"></i> Hapus Booking
                        </button>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4 border-left-warning">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Tukang</h6>
                    @if($booking->tukang)
                        <span class="badge badge-success"><i class="fas fa-check mr-1"></i> Ditugaskan</span>
                    @else
                        <span class="badge badge-warning"><i class="fas fa-exclamation-triangle mr-1"></i> Belum Ditugaskan</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($booking->tukang)
                        <div class="d-flex align-items-center mb-4">
                            <div class="mr-3">
                                <img class="img-profile rounded-circle border" src="{{ $booking->tukang->profile_picture ? asset('storage/' . $booking->tukang->profile_picture) : asset('img/undraw_profile.svg') }}" width="80" height="80" style="object-fit: cover;">
                            </div>
                            <div>
                                <h5 class="mb-0 font-weight-bold">{{ $booking->tukang->name }}</h5>
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-envelope mr-1"></i> {{ $booking->tukang->email }}
                                </p>
                                @if($booking->tukang->phone)
                                    <p class="mb-0 text-muted">
                                        <i class="fas fa-phone mr-1"></i> {{ $booking->tukang->phone }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item mb-3">
                                    <div class="small text-gray-500">Spesialisasi</div>
                                    <div class="font-weight-bold">
                                        <i class="fas fa-tools mr-1 text-warning"></i>
                                        {{ $booking->tukang->specialization ?? 'Tidak tersedia' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item mb-3">
                                    <div class="small text-gray-500">Pengalaman</div>
                                    <div class="font-weight-bold">
                                        <i class="fas fa-briefcase mr-1 text-warning"></i>
                                        {{ $booking->tukang->experience ?? 'Tidak tersedia' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <button type="button" class="btn btn-sm btn-outline-warning" data-toggle="modal" data-target="#changeTukangModal">
                                <i class="fas fa-exchange-alt mr-1"></i> Ganti Tukang
                            </button>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-hard-hat fa-4x text-warning"></i>
                            </div>
                            <p class="mb-0">Belum ada tukang yang ditugaskan untuk booking ini.</p>
                            @if($booking->status->status_code == 'dp_validated')
                                <button type="button" class="btn btn-warning mt-3" data-toggle="modal" data-target="#assignTukangModal">
                                    <i class="fas fa-user-plus mr-1"></i> Pilih Tukang
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi</h6>
                </div>
                <div class="card-body">
                    @php
                        $statusCode = $booking->status->status_code;
                    @endphp
                    
                    @if($statusCode == 'waiting_validation_dp' && $booking->payments()->latest()->first())
                        <div class="mb-3">
                            <a href="{{ route('admin.payments.show', $booking->payments()->latest()->first()->id) }}" class="btn btn-warning btn-block">
                                <i class="fas fa-money-bill"></i> Validasi Pembayaran DP
                            </a>
                        </div>
                    @endif
                    
                    @if($statusCode == 'dp_validated')
                        <div class="mb-3">
                            <a href="{{ route('admin.bookings.assign.id', $booking->id) }}" class="btn btn-info btn-block">
                                <i class="fas fa-user-cog"></i> Tugaskan Tukang
                            </a>
                        </div>
                    @endif
                    
                    @if($statusCode == 'waiting_validation_pelunasan' && $booking->payments()->latest()->first())
                        <div class="mb-3">
                            <a href="{{ route('admin.payments.show', $booking->payments()->latest()->first()->id) }}" class="btn btn-warning btn-block">
                                <i class="fas fa-money-bill"></i> Validasi Pelunasan
                            </a>
                        </div>
                    @endif
                    
                    @if(!in_array($statusCode, ['completed', 'canceled', 'rejected']))
                        <div class="mb-3">
                            <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#cancelBookingModal">
                                <i class="fas fa-times-circle"></i> Batalkan Booking
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Booking Modal -->
<div class="modal fade" id="cancelBookingModal" tabindex="-1" role="dialog" aria-labelledby="cancelBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelBookingModalLabel">Konfirmasi Pembatalan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin membatalkan booking ini?</p>
                    <div class="form-group">
                        <label for="cancel_reason">Alasan Pembatalan</label>
                        <textarea class="form-control" id="cancel_reason" name="cancel_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Batalkan Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Assign Tukang -->
<div class="modal fade" id="assignTukangModal" tabindex="-1" role="dialog" aria-labelledby="assignTukangModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignTukangModalLabel">Pilih Tukang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.bookings.assign.store', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tukang_id">Tukang</label>
                        <select class="form-control" id="tukang_id" name="tukang_id" required>
                            <option value="">-- Pilih Tukang --</option>
                            @foreach($tukangs ?? [] as $tukang)
                                <option value="{{ $tukang->id }}">{{ $tukang->name }} - {{ $tukang->specialization ?? 'Umum' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notes">Catatan (Opsional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Tambahkan catatan untuk tukang"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Change Tukang -->
<div class="modal fade" id="changeTukangModal" tabindex="-1" role="dialog" aria-labelledby="changeTukangModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeTukangModalLabel">Ganti Tukang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.bookings.assign.store', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Anda akan mengganti tukang yang saat ini ditugaskan ({{ $booking->tukang->name ?? '' }}).
                    </div>
                    <div class="form-group">
                        <label for="tukang_id">Tukang Baru</label>
                        <select class="form-control" id="tukang_id" name="tukang_id" required>
                            <option value="">-- Pilih Tukang --</option>
                            @foreach($tukangs ?? [] as $tukang)
                                <option value="{{ $tukang->id }}">{{ $tukang->name }} - {{ $tukang->specialization ?? 'Umum' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notes">Alasan Penggantian</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Alasan penggantian tukang" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Ganti Tukang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Delete Booking -->
<div class="modal fade" id="deleteBookingModal" tabindex="-1" role="dialog" aria-labelledby="deleteBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteBookingModalLabel">Hapus Booking</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle mr-1"></i> Anda yakin ingin menghapus booking ini? Tindakan ini tidak dapat dibatalkan.
                    </div>
                    <div class="form-group">
                        <label for="delete_reason">Alasan Penghapusan</label>
                        <textarea class="form-control" id="delete_reason" name="delete_reason" rows="3" placeholder="Berikan alasan penghapusan booking" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Tooltip initialization
        $('[data-toggle="tooltip"]').tooltip();
        
        // Print functionality
        $('#printButton').on('click', function() {
            window.print();
        });
    });
</script>
@endpush
@endsection
