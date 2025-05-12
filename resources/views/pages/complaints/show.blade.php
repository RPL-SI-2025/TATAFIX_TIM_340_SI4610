@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="fas fa-file-alt me-2"></i>Detail Pengaduan</h3>
                    <a href="{{ route('customer.complaints.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h4 class="fw-bold">{{ $complaint->subject }}</h4>
                        <div class="d-flex flex-wrap gap-3 mt-2">
                            <span class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $complaint->created_at->format('d M Y H:i') }}
                            </span>
                            <span>
                                @if ($complaint->status == 'pending')
                                    <span class="badge bg-warning text-dark py-2 px-3 rounded-pill">
                                        <i class="fas fa-clock me-1"></i> Menunggu Validasi
                                    </span>
                                @elseif ($complaint->status == 'valid')
                                    <span class="badge bg-success py-2 px-3 rounded-pill">
                                        <i class="fas fa-check-circle me-1"></i> Valid
                                    </span>
                                @else
                                    <span class="badge bg-danger py-2 px-3 rounded-pill">
                                        <i class="fas fa-times-circle me-1"></i> Tidak Valid
                                    </span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Deskripsi Pengaduan</label>
                        <div class="border p-4 rounded-3 bg-light-subtle">
                            {!! nl2br(e($complaint->description)) !!}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Bukti Pendukung</label>
                        <div class="border p-3 rounded-3 text-center">
                            @if (Str::endsWith($complaint->evidence_file, ['.jpg', '.jpeg', '.png', '.gif']))
                                <img src="{{ asset('storage/' . $complaint->evidence_file) }}" 
                                     alt="Bukti Pengaduan" 
                                     class="img-fluid rounded-3 border shadow-sm" 
                                     style="max-height: 400px;">
                                <div class="mt-3">
                                    <a href="{{ asset('storage/' . $complaint->evidence_file) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-expand me-1"></i> Lihat Full Size
                                    </a>
                                </div>
                            @else
                                <div class="py-4">
                                    <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                                    <h5>Dokumen Pendukung</h5>
                                    <a href="{{ asset('storage/' . $complaint->evidence_file) }}" 
                                       target="_blank" 
                                       class="btn btn-primary mt-2">
                                        <i class="fas fa-download me-1"></i> Unduh Dokumen
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($complaint->status != 'pending')
                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan Admin</label>
                            <div class="border p-4 rounded-3 bg-light-subtle">
                                {!! nl2br(e($complaint->admin_notes)) !!}
                                <div class="text-muted small mt-3">
                                    <i class="fas fa-clock me-1"></i>
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

<style>
    .bg-light-subtle {
        background-color: #f8f9fa;
    }
    
    .rounded-pill {
        border-radius: 50rem !important;
    }
    
    .card-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
</style>
@endsection