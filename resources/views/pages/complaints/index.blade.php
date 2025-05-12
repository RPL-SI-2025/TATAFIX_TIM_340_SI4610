@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Daftar Pengaduan Saya</span>
                    <a href="{{ route('customer.complaints.create') }}" class="btn btn-sm btn-primary">Buat Pengaduan Baru</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (count($complaints) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($complaints as $index => $complaint)
                                        <tr>
                                            <td>{{ $index + $complaints->firstItem() }}</td>
                                            <td>{{ $complaint->subject }}</td>
                                            <td>{{ $complaint->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                @if ($complaint->status == 'pending')
                                                    <span class="badge bg-warning">Menunggu Validasi</span>
                                                @elseif ($complaint->status == 'valid')
                                                    <span class="badge bg-success">Valid</span>
                                                @else
                                                    <span class="badge bg-danger">Tidak Valid</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('customer.complaints.show', $complaint->id) }}" class="btn btn-sm btn-info">Detail</a>
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
                        <div class="alert alert-info">
                            Anda belum memiliki pengaduan. <a href="{{ route('customer.complaints.create') }}">Buat pengaduan baru</a>.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection