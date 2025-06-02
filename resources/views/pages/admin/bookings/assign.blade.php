@extends('layouts.admin')

@section('title', 'Penugasan Tukang')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Penugasan Tukang untuk Booking #{{ $booking->id }}</h1>
        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 rounded-md bg-green-50 border border-green-200">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-200">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-600"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 bg-white border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-blue-600">Informasi Booking</h6>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <p class="mb-1 font-medium text-gray-700">ID Booking</p>
                        <p class="text-gray-900">{{ $booking->id }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="mb-1 font-medium text-gray-700">Layanan</p>
                        <p class="text-gray-900">{{ $booking->service->title_service }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="mb-1 font-medium text-gray-700">Kategori</p>
                        <p class="text-gray-900">{{ $booking->service->category->name }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="mb-1 font-medium text-gray-700">Tanggal & Waktu</p>
                        <p class="text-gray-900">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }} - {{ $booking->waktu_booking }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="mb-1 font-medium text-gray-700">Alamat</p>
                        <p class="text-gray-900">{{ $booking->alamat }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="mb-1 font-medium text-gray-700">Pelanggan</p>
                        <p class="text-gray-900">{{ $booking->user->name }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="mb-1 font-medium text-gray-700">Status</p>
                        <p><span class="bg-blue-500 text-white text-xs font-medium py-1 px-2 rounded-full">{{ $booking->status->status_name }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 bg-white border-b border-gray-200 flex justify-between items-center">
                    <h6 class="text-lg font-semibold text-blue-600">Daftar Tukang Tersedia</h6>
                    <div class="relative">
                        <button type="button" class="inline-flex items-center text-gray-500 hover:text-gray-700" id="filterDropdown" onclick="toggleDropdown()">
                            <i class="fas fa-filter mr-1"></i> Filter
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div id="filterMenu" class="hidden absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                            <div class="py-1">
                                <p class="px-4 py-2 text-xs font-medium text-gray-500">Filter:</p>
                                <a href="{{ route('admin.bookings.assign.id', $booking->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Semua Tukang</a>
                                <a href="{{ route('admin.bookings.assign.id', $booking->id) }}?filter=specialization&category={{ $booking->service->category->id }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Spesialisasi {{ $booking->service->category->name }}</a>
                                <a href="{{ route('admin.bookings.assign.id', $booking->id) }}?sort=rating" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Rating Tertinggi</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @if($tukangs->isEmpty())
                        <div class="p-4 rounded-md bg-blue-50 border border-blue-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-blue-800">Tidak ada tukang yang tersedia untuk saat ini.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="dataTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Spesialisasi</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pekerjaan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($tukangs as $tukang)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <img class="h-10 w-10 rounded-full mr-3" src="{{ $tukang->profile_picture ? asset('storage/' . $tukang->profile_picture) : asset('img/undraw_profile.svg') }}">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $tukang->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $tukang->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $tukang->specialization ?? 'Umum' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $rating = $tukang->rating_avg ?? 0;
                                            @endphp
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $rating)
                                                        <i class="fas fa-star text-yellow-500"></i>
                                                    @elseif($i <= $rating + 0.5)
                                                        <i class="fas fa-star-half-alt text-yellow-500"></i>
                                                    @else
                                                        <i class="far fa-star text-yellow-500"></i>
                                                    @endif
                                                @endfor
                                                <span class="ml-2 text-sm text-gray-700">{{ number_format($rating, 1) }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($tukang->is_available)
                                                <span class="bg-green-500 text-white text-xs font-medium py-1 px-2 rounded-full">Tersedia</span>
                                            @else
                                                <span class="bg-red-500 text-white text-xs font-medium py-1 px-2 rounded-full">Tidak Tersedia</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $tukang->completed_bookings_count ?? 0 }} selesai</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('admin.bookings.assign.store', $booking->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="tukang_id" value="{{ $tukang->id }}">
                                                <button type="submit" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" {{ !$tukang->is_available ? 'disabled' : '' }}>
                                                    <i class="fas fa-user-check mr-1"></i> Pilih
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination sudah ditangani oleh DataTables -->
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleDropdown() {
        const menu = document.getElementById('filterMenu');
        menu.classList.toggle('hidden');
    }

    // Menutup dropdown ketika mengklik di luar dropdown
    window.addEventListener('click', function(e) {
        const dropdown = document.getElementById('filterDropdown');
        const menu = document.getElementById('filterMenu');
        
        if (dropdown && menu && !dropdown.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });

    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "searching": true,
            "language": {
                "search": "Cari:",
                "zeroRecords": "Tidak ada data yang cocok"
            },
            "dom": '<"flex items-center justify-between mb-4"<"flex items-center"f><"">>t',
            "initComplete": function() {
                // Styling search input dengan Tailwind
                $('.dataTables_filter input').addClass('border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500');
                $('.dataTables_filter label').addClass('flex items-center text-sm text-gray-600');
            }
        });
    });
</script>
@endpush
