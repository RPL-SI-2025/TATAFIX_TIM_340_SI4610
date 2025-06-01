@extends('layouts.admin')

@section('title', 'Manajemen Booking')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Booking</h1>
    </div>
    
    <!-- Filter Status Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-left-primary">
                <div class="card-body py-3">
                    <h6 class="font-weight-bold text-primary mb-2">Filter Status:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-secondary m-1 {{ request()->query('status') ? '' : 'active' }}">Semua</a>
                        <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-warning m-1 {{ request()->query('status') == 'pending' ? 'active' : '' }}">Menunggu Pembayaran DP</a>
                        <a href="{{ route('admin.bookings.index', ['status' => 'waiting_validation_dp']) }}" class="btn btn-sm btn-outline-info m-1 {{ request()->query('status') == 'waiting_validation_dp' ? 'active' : '' }}">Menunggu Validasi DP</a>
                        <a href="{{ route('admin.bookings.index', ['status' => 'dp_validated']) }}" class="btn btn-sm btn-outline-primary m-1 {{ request()->query('status') == 'dp_validated' ? 'active' : '' }}">DP Divalidasi</a>
                        <a href="{{ route('admin.bookings.index', ['status' => 'in_progress']) }}" class="btn btn-sm btn-outline-primary m-1 {{ request()->query('status') == 'in_progress' ? 'active' : '' }}">Sedang Dikerjakan</a>
                        <a href="{{ route('admin.bookings.index', ['status' => 'done']) }}" class="btn btn-sm btn-outline-primary m-1 {{ request()->query('status') == 'done' ? 'active' : '' }}">Pekerjaan Selesai</a>
                        <a href="{{ route('admin.bookings.index', ['status' => 'waiting_validation_pelunasan']) }}" class="btn btn-sm btn-outline-info m-1 {{ request()->query('status') == 'waiting_validation_pelunasan' ? 'active' : '' }}">Menunggu Validasi Pelunasan</a>
                        <a href="{{ route('admin.bookings.index', ['status' => 'completed']) }}" class="btn btn-sm btn-outline-success m-1 {{ request()->query('status') == 'completed' ? 'active' : '' }}">Selesai</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Booking</h6>
            <div>
                <a href="#" class="btn btn-sm btn-primary" id="refreshTable">
                    <i class="fas fa-sync-alt"></i> Refresh
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Pelanggan</th>
                            <th width="20%">Layanan</th>
                            <th width="15%">Tanggal</th>
                            <th width="15%">Status</th>
                            <th width="10%">Tukang</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td class="text-center">{{ $booking->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar mr-2 bg-primary rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $booking->user->name }}</strong><br>
                                        <small class="text-muted">{{ $booking->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $booking->service->title_service }}</strong><br>
                                <small class="text-muted">{{ $booking->service->category->name ?? 'Tanpa Kategori' }}</small>
                            </td>
                            <td>
                                <i class="far fa-calendar-alt mr-1 text-primary"></i> {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}<br>
                                <small class="text-muted"><i class="far fa-clock mr-1"></i> {{ $booking->waktu_booking }}</small>
                            </td>
                            <td>
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
                                    <span class="badge badge-{{ $badgeClass }} py-2 px-3">
                                        <i class="fas {{ $statusIcon }} mr-1"></i> {{ $booking->status->display_name }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                @if($booking->tukang)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar mr-2 bg-success rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                            <i class="fas fa-hard-hat"></i>
                                        </div>
                                        <span>{{ $booking->tukang->name }}</span>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center text-muted">
                                        <div class="avatar mr-2 bg-secondary rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                            <i class="fas fa-user-slash"></i>
                                        </div>
                                        <span>Belum ditugaskan</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-primary m-1">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    
                                    @if(($statusCode == 'waiting_validation_dp' || $statusCode == 'waiting_validation_pelunasan') && $booking->payments()->latest()->first())
                                        <a href="{{ route('admin.payments.show', $booking->payments()->latest()->first()->id) }}" class="btn btn-sm btn-warning m-1">
                                            <i class="fas fa-money-bill"></i> Validasi
                                        </a>
                                    @endif
                                    
                                    @if($statusCode == 'dp_validated')
                                        <a href="{{ route('admin.bookings.assign.id', $booking->id) }}" class="btn btn-sm btn-info m-1">
                                            <i class="fas fa-user-cog"></i> Tugaskan
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data booking</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm text-gray-700">Showing {{ $bookings->firstItem() ?? 0 }} to {{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }} bookings</p>
                    </div>
                    <div>
                        {{ $bookings->links() }}
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
        // Inisialisasi DataTable dengan opsi yang lebih baik
        const table = $('#dataTable').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "searching": true,
            "responsive": true,
            "language": {
                "search": "Cari:",
                "zeroRecords": "Tidak ada data booking yang ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ booking",
                "infoEmpty": "Tidak ada data booking",
                "infoFiltered": "(difilter dari _MAX_ total booking)"
            },
            "columnDefs": [
                { "orderable": false, "targets": [6] } // Kolom aksi tidak dapat diurutkan
            ],
            "order": [[0, 'desc']] // Urutkan berdasarkan ID secara descending
        });
        
        // Tombol refresh untuk memuat ulang halaman
        $('#refreshTable').on('click', function(e) {
            e.preventDefault();
            location.reload();
        });
        
        // Tambahkan pencarian custom
        $('#dataTable_filter input').addClass('form-control-sm');
        $('#dataTable_filter input').attr('placeholder', 'Cari booking...');
        
        // Highlight baris saat hover
        $('#dataTable tbody tr').hover(
            function() { $(this).addClass('bg-light'); },
            function() { $(this).removeClass('bg-light'); }
        );
    });
</script>
@endpush

@push('styles')
<style>
    /* Memperbaiki tampilan badge */
    .badge {
        font-size: 85%;
        font-weight: 500;
    }
    
    /* Memperbaiki tampilan tabel */
    #dataTable {
        border-collapse: separate;
        border-spacing: 0;
    }
    
    #dataTable thead th {
        border-bottom-width: 1px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
    }
    
    /* Memperbaiki tampilan tombol filter */
    .btn-outline-secondary.active,
    .btn-outline-warning.active,
    .btn-outline-info.active,
    .btn-outline-primary.active,
    .btn-outline-success.active {
        color: #fff;
    }
    
    /* Memperbaiki tampilan pagination */
    .pagination {
        margin-bottom: 0;
    }
    
    /* Memperbaiki tampilan avatar */
    .avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
</style>
@endpush
