@extends('layouts.admin')

@section('title', 'Edit Booking')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Booking #{{ $booking->id }}</h1>
        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Booking</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="customer_name">Nama Customer</label>
                            <input type="text" class="form-control" id="customer_name" value="{{ $booking->user->name }}" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label for="service_id">Layanan</label>
                            <select class="form-control @error('service_id') is-invalid @enderror" id="service_id" name="service_id">
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ $booking->service_id == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} - {{ $service->category->name }} (Rp {{ number_format($service->price, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="status_id">Status</label>
                            <select class="form-control @error('status_id') is-invalid @enderror" id="status_id" name="status_id">
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ $booking->status_id == $status->id ? 'selected' : '' }}>
                                        {{ $status->display_name }} ({{ $status->status_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('status_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4">{{ $booking->notes }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Customer</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar bg-gray-200 rounded-circle mr-3">
                            <span class="text-gray-700">{{ substr($booking->user->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $booking->user->name }}</h5>
                            <small class="text-muted">{{ $booking->user->email }}</small>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted">Tanggal Registrasi</small>
                        <p>{{ $booking->user->created_at->format('d M Y') }}</p>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted">Nomor Telepon</small>
                        <p>{{ $booking->user->phone ?? 'Tidak ada' }}</p>
                    </div>
                    
                    <div>
                        <small class="text-muted">Alamat</small>
                        <p class="mb-0">{{ $booking->user->address ?? 'Tidak ada' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Booking</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">ID Booking</small>
                        <p>#{{ $booking->id }}</p>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted">Tanggal Booking</small>
                        <p>{{ $booking->created_at->format('d M Y H:i') }}</p>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted">Total Harga</small>
                        <p>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                    </div>
                    
                    <div>
                        <small class="text-muted">Tukang</small>
                        <p class="mb-0">
                            @if($booking->assigned_worker_id)
                                {{ $booking->tukang->name ?? 'Tukang tidak ditemukan' }}
                            @else
                                <span class="text-warning">Belum ditugaskan</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Tambahkan konfirmasi sebelum submit form
        $('form').on('submit', function(e) {
            if (!confirm('Apakah Anda yakin ingin menyimpan perubahan ini?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush
