@extends('layouts.app')

@section('title', 'Detail Penugasan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Penugasan #{{ $booking->id }}</h4>
                    <a href="{{ route('tukang.bookings.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
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

                    <!-- Tombol Aksi Booking -->
                    <div class="mb-4">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Aksi Booking</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $statusCode = strtolower($booking->status_code ?: $booking->status->status_code);
                                @endphp

                                @if($statusCode == 'waiting_tukang_response')
                                    <div class="alert alert-warning mb-3">
                                        <i class="fas fa-exclamation-triangle mr-2"></i> Booking ini menunggu konfirmasi Anda. Silakan terima atau tolak penugasan ini.
                                    </div>
                                    <div class="d-flex">
                                        <form action="{{ route('tukang.bookings.accept', $booking->id) }}" method="POST" class="mr-2">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check"></i> Terima Penugasan
                                            </button>
                                        </form>
                                        <form action="{{ route('tukang.bookings.reject', $booking->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-times"></i> Tolak Penugasan
                                            </button>
                                        </form>
                                    </div>
                                @elseif($statusCode == 'in_progress')
                                    <div class="alert alert-info mb-3">
                                        <i class="fas fa-info-circle mr-2"></i> Booking ini sedang Anda kerjakan. Jika pekerjaan sudah selesai, klik tombol di bawah.
                                    </div>
                                    <form action="{{ route('tukang.bookings.complete', $booking->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-check-circle"></i> Selesaikan Pekerjaan
                                        </button>
                                    </form>
                                @elseif(in_array($statusCode, ['done', 'waiting_validation_pelunasan', 'waiting_final_payment']))
                                    <div class="alert alert-success mb-3">
                                        <i class="fas fa-check-circle mr-2"></i> Pekerjaan telah Anda selesaikan. Menunggu pelanggan melakukan pelunasan pembayaran.
                                    </div>
                                @elseif($statusCode == 'completed')
                                    <div class="alert alert-success mb-3">
                                        <i class="fas fa-check-double mr-2"></i> Booking ini telah selesai. Terima kasih atas pekerjaan Anda.
                                    </div>
                                @elseif(in_array($statusCode, ['rejected', 'canceled']))
                                    <div class="alert alert-danger mb-3">
                                        <i class="fas fa-ban mr-2"></i> Booking ini telah dibatalkan atau ditolak.
                                    </div>
                                @else
                                    <div class="alert alert-secondary mb-3">
                                        <i class="fas fa-info-circle mr-2"></i> Tidak ada aksi yang tersedia untuk status booking saat ini.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Informasi Booking</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="font-weight-bold">Status:</label>
                                        @php
                                            $statusCode = strtolower($booking->status->status_code);
                                            $badgeClass = 'secondary';
                                            
                                            if (in_array($statusCode, ['pending', 'waiting_dp', 'waiting_validation_dp', 'pending_dp'])) {
                                                $badgeClass = 'warning';
                                            } elseif (in_array($statusCode, ['dp_validated', 'waiting_tukang_response', 'waiting_worker_confirmation'])) {
                                                $badgeClass = 'info';
                                            } elseif (in_array($statusCode, ['in_progress', 'done', 'waiting_validation_pelunasan', 'waiting_final_payment'])) {
                                                $badgeClass = 'primary';
                                            } elseif (in_array($statusCode, ['completed', 'paid'])) {
                                                $badgeClass = 'success';
                                            } elseif (in_array($statusCode, ['cancelled', 'rejected', 'dp_rejected', 'expired'])) {
                                                $badgeClass = 'danger';
                                            }
                                        @endphp
                                        <div><span class="badge badge-{{ $badgeClass }}">{{ $booking->status->display_name ?? ucwords(str_replace('_', ' ', $statusCode)) }}</span></div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="font-weight-bold">Tanggal Booking:</label>
                                        <div>{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="font-weight-bold">Waktu Booking:</label>
                                        <div>{{ $booking->waktu_booking }}</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="font-weight-bold">Alamat:</label>
                                        <div>{{ $booking->alamat ?? $booking->address ?? 'Alamat tidak tersedia' }}</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="font-weight-bold">Catatan:</label>
                                        <div>{{ $booking->notes ?? $booking->catatan ?? $booking->description ?? 'Tidak ada catatan' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Informasi Layanan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="font-weight-bold">Nama Layanan:</label>
                                        <div>{{ $booking->service->title_service }}</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="font-weight-bold">Kategori:</label>
                                        <div>{{ $booking->service->category->name }}</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="font-weight-bold">Harga Dasar:</label>
                                        <div>Rp {{ number_format($booking->service->base_price, 0, ',', '.') }}</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="font-weight-bold">Deskripsi:</label>
                                        <div>{{ $booking->service->description }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Informasi Pelanggan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        @php
                                            $profilePicture = $booking->user->profile_photo_path ?? null;
                                            $avatarUrl = $profilePicture 
                                                ? (filter_var($profilePicture, FILTER_VALIDATE_URL) 
                                                    ? $profilePicture 
                                                    : asset('storage/' . ltrim($profilePicture, '/')))
                                                : 'https://ui-avatars.com/api/?name=' . urlencode($booking->user->name) . '&color=7F9CF5&background=EBF4FF';
                                        @endphp
                                        <img src="{{ $avatarUrl }}" class="rounded-circle" width="80" height="80" style="object-fit: cover;" alt="Profil {{ $booking->user->name }}">
                                        <h5 class="mt-2">{{ $booking->user->name }}</h5>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="font-weight-bold">Email:</label>
                                        <div>{{ $booking->user->email }}</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="font-weight-bold">No. Telepon:</label>
                                        <div>{{ $booking->user->phone ?? 'Tidak tersedia' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bagian Tindakan dihapus karena sudah ada di bagian atas halaman -->
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Informasi Pelanggan Lanjutan</h5>
                                </div>
                                <div class="card-body">
                                    @if(in_array($booking->status->status_code, ['done', 'DONE', 'waiting_validation_pelunasan', 'WAITING_VALIDATION_PELUNASAN', 'completed', 'COMPLETED']))
                                        <div class="alert alert-success">
                                            <p>Anda telah menyelesaikan pekerjaan ini. Pelanggan akan melakukan pelunasan pembayaran.</p>
                                            @if($booking->status->status_code == 'completed' || $booking->status->status_code == 'COMPLETED')
                                                <p class="mb-0"><strong>Status:</strong> Pembayaran telah dilunasi dan divalidasi.</p>
                                            @elseif($booking->status->status_code == 'waiting_validation_pelunasan' || $booking->status->status_code == 'WAITING_VALIDATION_PELUNASAN')
                                                <p class="mb-0"><strong>Status:</strong> Menunggu validasi pelunasan oleh admin.</p>
                                            @else
                                                <p class="mb-0"><strong>Status:</strong> Menunggu pelunasan dari pelanggan.</p>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <div class="mt-3">
                                        <a href="{{ route('tukang.bookings.index') }}" class="btn btn-outline-secondary btn-block">
                                            <i class="fas fa-list"></i> Kembali ke Daftar Penugasan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Review Card -->
                    @if(strtolower($booking->status->status_code) == 'completed' && !is_null($booking->rating))
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-warning text-white">
                                    <h5 class="mb-0"><i class="fas fa-star mr-2"></i> Ulasan dari Customer</h5>
                                </div>
                                <div class="card-body">
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
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if(strtolower($booking->status->status_code) != 'assigned')
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Timeline Booking</h5>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <!-- 1. Booking Dibuat -->
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <h5 class="timeline-title">Booking Dibuat</h5>
                                            <p class="timeline-date">{{ $booking->created_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>

                                    @php
                                        $statusCode = strtolower($booking->status->status_code);
                                        // Asumsikan pembayaran pertama adalah DP dan kedua adalah pelunasan
                                        $payments = $booking->payments->sortBy('created_at');
                                        $dpPayment = $payments->first();
                                        $finalPayment = $payments->count() > 1 ? $payments->skip(1)->first() : null;
                                    @endphp

                                    <!-- 2. Pembayaran DP -->
                                    @if($dpPayment)
                                        <div class="timeline-item">
                                            <div class="timeline-marker {{ $dpPayment->status === 'paid' ? 'bg-success' : 'bg-warning' }}"></div>
                                            <div class="timeline-content">
                                                <h5 class="timeline-title">
                                                    {{ $dpPayment->status === 'paid' ? 'DP Lunas' : 'Menunggu Pembayaran DP' }}
                                                </h5>
                                                <p class="timeline-date">
                                                    {{ $dpPayment->created_at->format('d M Y H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- 3. Tukang Ditetapkan -->
                                    @if($booking->assigned_worker_id)
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-info"></div>
                                            <div class="timeline-content">
                                                <h5 class="timeline-title">Tukang Ditetapkan</h5>
                                                <p class="timeline-date">
                                                    {{ $booking->assigned_at ? \Carbon\Carbon::parse($booking->assigned_at)->format('d M Y H:i') : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- 4. Status Pekerjaan -->
                                    @if(in_array($statusCode, ['in_progress', 'inprogress', 'done', 'waiting_final_payment', 'waiting_final_validation', 'completed']))
                                        <div class="timeline-item">
                                            <div class="timeline-marker {{ in_array($statusCode, ['completed']) ? 'bg-success' : 'bg-primary' }}"></div>
                                            <div class="timeline-content">
                                                <h5 class="timeline-title">
                                                    @if(in_array($statusCode, ['in_progress', 'inprogress']))
                                                        Pekerjaan Dimulai
                                                    @elseif(in_array($statusCode, ['done', 'waiting_final_payment', 'waiting_final_validation']))
                                                        Pekerjaan Selesai
                                                    @else
                                                        Booking Selesai
                                                    @endif
                                                </h5>
                                                <p class="timeline-date">
                                                    @if($statusCode === 'completed')
                                                        {{ $booking->updated_at->format('d M Y H:i') }}
                                                    @elseif($booking->completed_at)
                                                        {{ \Carbon\Carbon::parse($booking->completed_at)->format('d M Y H:i') }}
                                                    @else
                                                        {{ $booking->updated_at->format('d M Y H:i') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- 5. Pembayaran Pelunasan -->
                                    @if($finalPayment && in_array($statusCode, ['waiting_final_validation', 'completed']))
                                        <div class="timeline-item">
                                            <div class="timeline-marker {{ $statusCode === 'completed' ? 'bg-success' : 'bg-warning' }}"></div>
                                            <div class="timeline-content">
                                                <h5 class="timeline-title">
                                                    {{ $statusCode === 'completed' ? 'Pelunasan Divalidasi' : 'Menunggu Validasi Pelunasan' }}
                                                </h5>
                                                <p class="timeline-date">
                                                    @if($statusCode === 'completed' && $finalPayment->updated_at)
                                                        {{ $finalPayment->updated_at->format('d M Y H:i') }}
                                                    @else
                                                        {{ $finalPayment->created_at->format('d M Y H:i') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-marker {
        position: absolute;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        left: -30px;
        top: 5px;
    }
    
    .timeline-item:not(:last-child) .timeline-marker:before {
        content: '';
        position: absolute;
        width: 2px;
        background-color: #e0e0e0;
        top: 15px;
        bottom: -20px;
        left: 6px;
    }
    
    .timeline-title {
        margin-bottom: 5px;
        font-size: 16px;
    }
    
    .timeline-date {
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 0;
    }
</style>
@endpush
