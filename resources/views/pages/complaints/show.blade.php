@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detail Pengaduan</span>
                    <a href="{{ route('customer.complaints.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="card-title">{{ $complaint->subject }}</h5>
                        <div class="d-flex justify-content-between text-muted small mb-3">
                            <span>Dibuat pada: {{ $complaint->created_at->format('d M Y H:i') }}</span>
                            <span>
                                Status: 
                                @if ($complaint->status == 'pending')
                                    <span class="badge bg-warning">Menunggu Validasi</span>
                                @elseif ($complaint->status == 'valid')
                                    <span class="badge bg-success">Valid</span>
                                @else
                                    <span class="badge bg-danger">Tidak Valid</span>
                                @endif
                            </span>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Deskripsi Pengaduan</h6>
                                <p class="card-text">{{ $complaint->description }}</p>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6>Bukti Pendukung</h6>
                            <img src="{{ asset('storage/' . $complaint->evidence_file) }}" class="img-fluid img-thumbnail" style="max-height: 300px;">
                        </div>
                        
                        @if ($complaint->status != 'pending')
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">Catatan Admin</h6>
                                    <p class="card-text">{{ $complaint->admin_notes }}</p>
                                    <div class="text-muted small">
                                        Divalidasi pada: {{ $complaint->validated_at->format('d M Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection