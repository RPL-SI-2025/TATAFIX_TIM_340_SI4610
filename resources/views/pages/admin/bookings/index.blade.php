@extends('layouts.admin')

@section('title', 'Manajemen Booking')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <!-- Page Heading -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Manajemen Booking</h1>
    </div>
    
    <!-- Filter Status Cards -->
    <div class="mb-6">
        <div class="w-full">
            <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500">
                <div class="p-4">
                    <h6 class="font-bold text-blue-600 mb-3">Filter Status:</h6>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.bookings.index') }}" class="px-3 py-1.5 text-sm rounded-md border {{ request()->query('status') ? 'border-gray-300 text-gray-700 hover:bg-gray-100' : 'bg-gray-700 text-white border-gray-700' }} transition-colors duration-200 mr-2 mb-2">Semua</a>
                        <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" class="px-3 py-1.5 text-sm rounded-md border {{ request()->query('status') == 'pending' ? 'bg-yellow-600 text-white border-yellow-600' : 'border-yellow-300 text-yellow-700 hover:bg-yellow-50' }} transition-colors duration-200 mr-2 mb-2">Menunggu Pembayaran DP</a>
                        <a href="{{ route('admin.bookings.index', ['status' => 'waiting_validation_dp']) }}" class="px-3 py-1.5 text-sm rounded-md border {{ request()->query('status') == 'waiting_validation_dp' ? 'bg-blue-500 text-white border-blue-500' : 'border-blue-300 text-blue-700 hover:bg-blue-50' }} transition-colors duration-200 mr-2 mb-2">Menunggu Validasi DP</a>
                        <a href="{{ route('admin.bookings.index', ['status' => 'dp_validated']) }}" class="px-3 py-1.5 text-sm rounded-md border {{ request()->query('status') == 'dp_validated' ? 'bg-indigo-600 text-white border-indigo-600' : 'border-indigo-300 text-indigo-700 hover:bg-indigo-50' }} transition-colors duration-200 mr-2 mb-2">DP Divalidasi</a>
                        <a href="{{ route('admin.bookings.index', ['status' => 'in_progress']) }}" class="px-3 py-1.5 text-sm rounded-md border {{ request()->query('status') == 'in_progress' ? 'bg-purple-600 text-white border-purple-600' : 'border-purple-300 text-purple-700 hover:bg-purple-50' }} transition-colors duration-200 mr-2 mb-2">Sedang Dikerjakan</a>
                        <a href="{{ route('admin.bookings.index', ['status' => 'done']) }}" class="px-3 py-1.5 text-sm rounded-md border {{ request()->query('status') == 'done' ? 'bg-blue-600 text-white border-blue-600' : 'border-blue-300 text-blue-700 hover:bg-blue-50' }} transition-colors duration-200 mr-2 mb-2">Pekerjaan Selesai</a>
                        <a href="{{ route('admin.bookings.index', ['status' => 'waiting_validation_pelunasan']) }}" class="px-3 py-1.5 text-sm rounded-md border {{ request()->query('status') == 'waiting_validation_pelunasan' ? 'bg-blue-500 text-white border-blue-500' : 'border-blue-300 text-blue-700 hover:bg-blue-50' }} transition-colors duration-200 mr-2 mb-2">Menunggu Validasi Pelunasan</a>
                        <a href="{{ route('admin.bookings.index', ['status' => 'completed']) }}" class="px-3 py-1.5 text-sm rounded-md border {{ request()->query('status') == 'completed' ? 'bg-green-600 text-white border-green-600' : 'border-green-300 text-green-700 hover:bg-green-50' }} transition-colors duration-200 mr-2 mb-2">Selesai</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 bg-white border-b flex justify-between items-center">
            <h6 class="text-lg font-semibold text-blue-600">Daftar Booking</h6>
            <div>
                <a href="#" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200" id="refreshTable">
                    <i class="fas fa-sync-alt mr-2"></i> Refresh
                </a>
            </div>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="dataTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[5%]">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%]">Pelanggan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%]">Layanan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%]">Tukang</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900">{{ $booking->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 mr-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-medium">
                                            {{ substr($booking->nama_pemesan, 0, 1) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->nama_pemesan }}</div>
                                        <div class="text-sm text-gray-500">
                                            <a href="mailto:{{ $booking->user->email }}" class="hover:text-blue-600">{{ $booking->user->email }}</a>
                                        </div>
                                    </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->service->title_service }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->service->category->name ?? 'Tanpa Kategori' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><i class="far fa-calendar-alt mr-1 text-blue-500"></i> {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}</div>
                                <div class="text-sm text-gray-500"><i class="far fa-clock mr-1"></i> {{ $booking->waktu_booking }}</div>
                            </td>
                            <td>
                                @php
                                    $statusCode = $booking->status->status_code;
                                    $badgeClass = 'bg-gray-500';
                                    $statusIcon = 'fa-info-circle';
                                    
                                    if ($statusCode == 'pending') {
                                        $badgeClass = 'bg-yellow-500';
                                        $statusIcon = 'fa-clock';
                                    } elseif ($statusCode == 'waiting_validation_dp') {
                                        $badgeClass = 'bg-blue-400';
                                        $statusIcon = 'fa-money-check-alt';
                                    } elseif ($statusCode == 'dp_validated') {
                                        $badgeClass = 'bg-indigo-600';
                                        $statusIcon = 'fa-check-circle';
                                    } elseif ($statusCode == 'in_progress') {
                                        $badgeClass = 'bg-purple-600';
                                        $statusIcon = 'fa-tools';
                                    } elseif ($statusCode == 'done') {
                                        $badgeClass = 'bg-blue-600';
                                        $statusIcon = 'fa-clipboard-check';
                                    } elseif ($statusCode == 'completed') {
                                        $badgeClass = 'bg-green-600';
                                        $statusIcon = 'fa-check-double';
                                    } elseif (in_array($statusCode, ['rejected', 'canceled'])) {
                                        $badgeClass = 'bg-red-600';
                                        $statusIcon = 'fa-times-circle';
                                    }
                                @endphp
                                <div class="flex items-center">
                                    <span class="{{ $badgeClass }} text-white text-xs font-medium py-1.5 px-3 rounded-full inline-flex items-center shadow-sm">
                                        <i class="fas {{ $statusIcon }} mr-1"></i> {{ $booking->status->display_name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($booking->technician)
                                    <div class="flex items-center">
                                        <div class="mr-2 bg-green-600 rounded-full text-white flex items-center justify-center" style="width: 28px; height: 28px;">
                                            <i class="fas fa-hard-hat"></i>
                                        </div>
                                        <span class="font-medium">{{ $booking->technician->name }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center">
                                        <div class="mr-2 bg-gray-500 rounded-full text-white flex items-center justify-center" style="width: 28px; height: 28px;">
                                            <i class="fas fa-user-slash"></i>
                                        </div>
                                        <div class="font-medium text-gray-500">Belum ditugaskan</div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2 justify-end">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </a>
                                    @if(in_array($booking->status->status_code, ['waiting_validation_dp', 'waiting_validation_pelunasan']))
                                        @php
                                            // Cari payment terbaru untuk booking ini yang statusnya pending
                                            $pendingPayment = \App\Models\Payment::where('booking_id', $booking->id)
                                                ->where('status', 'pending')
                                                ->latest()
                                                ->first();
                                        @endphp
                                        
                                        @if($pendingPayment)
                                            <a href="{{ route('admin.payments.show', $pendingPayment->id) }}?redirect_back=bookings" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700 transition-colors duration-200">
                                                <i class="fas fa-check-circle mr-1"></i> Validasi
                                            </a>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded text-white bg-gray-400 cursor-not-allowed">
                                                <i class="fas fa-check-circle mr-1"></i> Tidak Ada Pembayaran
                                            </span>
                                        @endif
                                    @endif
                                    @if($booking->status->status_code == 'dp_validated' && !$booking->technician_id)
                                        <a href="{{ route('admin.bookings.assign', $booking->id) }}" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200">
                                            <i class="fas fa-user-cog mr-1"></i> Tugaskan
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Tidak ada data booking</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6">
                <div class="flex justify-between items-center">
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
    /* Tailwind sudah menangani sebagian besar styling */
    /* Styling tambahan untuk DataTable */
    #dataTable_filter input {
        @apply border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
    }
    
    /* Styling untuk pagination dari Laravel */
    .pagination {
        @apply flex justify-center mt-4;
    }
    
    .pagination > nav > div:first-child {
        @apply hidden;
    }
    
    .pagination .relative.inline-flex.items-center {
        @apply px-4 py-2 text-sm font-medium border border-gray-300 bg-white text-gray-700 hover:bg-gray-50;
    }
    
    .pagination span[aria-current="page"] > span {
        @apply px-4 py-2 text-sm font-medium border border-blue-500 bg-blue-600 text-white;
    }
    
    /* DataTable styling */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        @apply px-3 py-1 text-sm border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 mx-1 rounded;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        @apply bg-blue-600 text-white border-blue-600 hover:bg-blue-700;
    }
</style>
@endpush
