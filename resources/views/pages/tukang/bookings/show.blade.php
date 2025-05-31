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
                                        <div><span class="badge badge-{{ $badgeClass }}">{{ $booking->status->status_name }}</span></div>
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
                                        <div>{{ $booking->alamat }}</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="font-weight-bold">Catatan:</label>
                                        <div>{{ $booking->notes ?? 'Tidak ada catatan' }}</div>
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
                                        <img src="{{ $booking->user->profile_picture ? asset('storage/' . $booking->user->profile_picture) : asset('img/undraw_profile.svg') }}" class="rounded-circle" width="80" alt="Profil Pelanggan">
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
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Tindakan</h5>
                                </div>
                                <div class="card-body">
                                    @if($booking->status->status_code == 'ASSIGNED')
                                        <div class="alert alert-info">
                                            <p>Anda telah ditugaskan untuk booking ini. Silakan konfirmasi apakah Anda menerima atau menolak penugasan ini.</p>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <form action="{{ route('tukang.bookings.accept', $booking->id) }}" method="POST">
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
                                    @elseif($booking->status->status_code == 'IN_PROCESS')
                                        <div class="alert alert-primary">
                                            <p>Anda sedang mengerjakan layanan ini. Setelah selesai, klik tombol di bawah untuk menandai pekerjaan telah selesai.</p>
                                        </div>
                                        <form action="{{ route('tukang.bookings.complete', $booking->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group">
                                                <label for="completion_notes">Catatan Penyelesaian (Opsional)</label>
                                                <textarea class="form-control" id="completion_notes" name="completion_notes" rows="3"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-success btn-block">
                                                <i class="fas fa-check-circle"></i> Tandai Selesai
                                            </button>
                                        </form>
                                    @elseif(in_array($booking->status->status_code, ['WAITING_FINAL_PAYMENT', 'WAITING_FINAL_VALIDATION', 'COMPLETED']))
                                        <div class="alert alert-success">
                                            <p>Anda telah menyelesaikan pekerjaan ini. Pelanggan akan melakukan pelunasan pembayaran.</p>
                                            @if($booking->status->status_code == 'COMPLETED')
                                                <p class="mb-0"><strong>Status:</strong> Pembayaran telah dilunasi dan divalidasi.</p>
                                            @elseif($booking->status->status_code == 'WAITING_FINAL_VALIDATION')
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
                    
                    @if($booking->status->status_code != 'ASSIGNED')
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Timeline Booking</h5>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <h5 class="timeline-title">Booking Dibuat</h5>
                                            <p class="timeline-date">{{ $booking->created_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($booking->payments->isNotEmpty())
                                        <div class="timeline-item">
                                            <div class="timeline-marker {{ $booking->status->status_code != 'PENDING' ? 'bg-success' : 'bg-warning' }}"></div>
                                            <div class="timeline-content">
                                                <h5 class="timeline-title">Pembayaran DP</h5>
                                                <p class="timeline-date">{{ $booking->payments->where('payment_type', 'dp')->first()->created_at->format('d M Y H:i') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($booking->status->status_code != 'PENDING' && $booking->status->status_code != 'WAITING_DP_VALIDATION')
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-success"></div>
                                            <div class="timeline-content">
                                                <h5 class="timeline-title">DP Divalidasi</h5>
                                                <p class="timeline-date">{{ $booking->payments->where('payment_type', 'dp')->first()->updated_at->format('d M Y H:i') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($booking->status->status_code != 'PENDING' && $booking->status->status_code != 'WAITING_DP_VALIDATION' && $booking->status->status_code != 'WAITING_TUKANG_ASSIGNMENT')
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-success"></div>
                                            <div class="timeline-content">
                                                <h5 class="timeline-title">Tukang Ditugaskan</h5>
                                                <p class="timeline-date">{{ $booking->assigned_at ? \Carbon\Carbon::parse($booking->assigned_at)->format('d M Y H:i') : '-' }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($booking->status->status_code != 'PENDING' && $booking->status->status_code != 'WAITING_DP_VALIDATION' && $booking->status->status_code != 'WAITING_TUKANG_ASSIGNMENT' && $booking->status->status_code != 'ASSIGNED')
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-success"></div>
                                            <div class="timeline-content">
                                                <h5 class="timeline-title">Tukang Menerima Penugasan</h5>
                                                <p class="timeline-date">{{ $booking->accepted_at ? \Carbon\Carbon::parse($booking->accepted_at)->format('d M Y H:i') : '-' }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if(in_array($booking->status->status_code, ['WAITING_FINAL_PAYMENT', 'WAITING_FINAL_VALIDATION', 'COMPLETED']))
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-success"></div>
                                            <div class="timeline-content">
                                                <h5 class="timeline-title">Pekerjaan Selesai</h5>
                                                <p class="timeline-date">{{ $booking->completed_at ? \Carbon\Carbon::parse($booking->completed_at)->format('d M Y H:i') : '-' }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if(in_array($booking->status->status_code, ['WAITING_FINAL_VALIDATION', 'COMPLETED']))
                                        <div class="timeline-item">
                                            <div class="timeline-marker {{ $booking->status->status_code == 'COMPLETED' ? 'bg-success' : 'bg-warning' }}"></div>
                                            <div class="timeline-content">
                                                <h5 class="timeline-title">Pelunasan Dibayar</h5>
                                                <p class="timeline-date">{{ $booking->payments->where('payment_type', 'final')->first()->created_at->format('d M Y H:i') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($booking->status->status_code == 'COMPLETED')
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-success"></div>
                                            <div class="timeline-content">
                                                <h5 class="timeline-title">Pelunasan Divalidasi</h5>
                                                <p class="timeline-date">{{ $booking->payments->where('payment_type', 'final')->first()->updated_at->format('d M Y H:i') }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-success"></div>
                                            <div class="timeline-content">
                                                <h5 class="timeline-title">Booking Selesai</h5>
                                                <p class="timeline-date">{{ $booking->updated_at->format('d M Y H:i') }}</p>
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
