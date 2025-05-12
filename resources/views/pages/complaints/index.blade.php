@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow border-0 rounded-lg">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Pengaduan Anda</h3>
                    <a href="{{ route('customer.complaints.create') }}" class="btn btn-light">
                        <i class="fas fa-plus me-1"></i> Buat Baru
                    </a>
                </div>
                
                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($complaints->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="30%">Judul</th>
                                        <th width="20%">Tanggal</th>
                                        <th width="20%">Status</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($complaints as $index => $complaint)
                                        <tr>
                                            <td>{{ $index + $complaints->firstItem() }}</td>
                                            <td>{{ Str::limit($complaint->subject, 40) }}</td>
                                            <td>{{ $complaint->created_at->format('d M Y H:i') }}</td>
                                            <td>
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
                                            </td>
                                            <td>
                                                <a href="{{ route('customer.complaints.show', $complaint->id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $complaints->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center py-4">
                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                            <h4 class="mb-3">Anda belum memiliki pengaduan</h4>
                            <a href="{{ route('customer.complaints.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Buat Pengaduan Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .badge {
        font-weight: 500;
    }
    
    .table th {
        font-weight: 600;
        color: #495057;
    }
    
    .alert-info {
        background-color: #e7f5ff;
        border-color: #d0ebff;
    }
</style>
@endsection