@extends('layouts.admin')

@section('title', 'Edit Booking')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <!-- Page Heading -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Edit Booking</h1>
            <nav class="mt-1">
                <ol class="flex text-sm">
                    <li class="text-gray-500 hover:text-gray-700"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="mx-2 text-gray-400">/</li>
                    <li class="text-gray-500 hover:text-gray-700"><a href="{{ route('admin.bookings.index') }}">Booking</a></li>
                    <li class="mx-2 text-gray-400">/</li>
                    <li class="text-gray-700 font-medium">Edit #{{ $booking->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 bg-white border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-blue-600">Informasi Booking</h6>
                </div>
                <div class="p-6">
                    <!-- Debug URL: {{ route('admin.bookings.update', $booking->id) }} -->
                    <form id="editBookingForm" action="{{ url('/admin/bookings/' . $booking->id) }}" method="POST" onsubmit="console.log('Form submitted'); return true;">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Customer</label>
                            <input type="text" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md bg-gray-50" id="customer_name" value="{{ $booking->user->name }}" disabled>
                        </div>
                        
                        <div class="mb-4">
                            <label for="service_display" class="block text-sm font-medium text-gray-700 mb-1">Layanan</label>
                            <input type="hidden" name="service_id" value="{{ $booking->service_id }}">
                            <div class="shadow-sm block w-full sm:text-sm border border-gray-300 rounded-md bg-gray-50 px-3 py-2">
                                @foreach($services as $service)
                                    @if($booking->service_id == $service->service_id)
                                        {{ $service->title_service }} - {{ $service->category->name ?? 'Kategori tidak tersedia' }} (Rp {{ number_format($service->base_price ?? 0, 0, ',', '.') }})
                                    @endif
                                @endforeach
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Layanan tidak dapat diubah untuk booking yang sudah ada.</p>
                            @error('service_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="status_id" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('status_id') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" id="status_id" name="status_id">
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ $booking->status_id == $status->id ? 'selected' : '' }}>
                                        {{ $status->display_name }} ({{ $status->status_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('status_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('notes') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" id="notes" name="notes" rows="4">{{ $booking->notes }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div> --}}
                        
                        <div class="flex justify-start space-x-3">
                            <button type="submit" id="submitBtn" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="return confirm('Apakah Anda yakin ingin menyimpan perubahan ini?')">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 bg-white border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-blue-600">Informasi Customer</h6>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold mr-3">
                            {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h5 class="font-medium text-gray-900">{{ $booking->user->name }}</h5>
                            <p class="text-sm text-gray-500">{{ $booking->user->email }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-3 border-b border-gray-100 pb-3">
                        <p class="text-xs text-gray-500 mb-1">Tanggal Registrasi</p>
                        <p class="text-sm font-medium text-gray-900">{{ $booking->user->created_at->format('d M Y') }}</p>
                    </div>
                    
                    <div class="mb-3 border-b border-gray-100 pb-3">
                        <p class="text-xs text-gray-500 mb-1">Nomor Telepon</p>
                        <p class="text-sm font-medium text-gray-900">{{ $booking->user->phone ?? 'Tidak ada' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Alamat</p>
                        <p class="text-sm font-medium text-gray-900">{{ $booking->user->address ?? 'Tidak ada' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 bg-white border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-blue-600">Detail Booking</h6>
                </div>
                <div class="p-6">
                    <div class="mb-3 border-b border-gray-100 pb-3">
                        <p class="text-xs text-gray-500 mb-1">ID Booking</p>
                        <p class="text-sm font-medium text-gray-900">#{{ $booking->id }}</p>
                    </div>
                    
                    <div class="mb-3 border-b border-gray-100 pb-3">
                        <p class="text-xs text-gray-500 mb-1">Tanggal Booking</p>
                        <p class="text-sm font-medium text-gray-900">{{ $booking->created_at->format('d M Y H:i') }}</p>
                    </div>
                    
                    <div class="mb-3 border-b border-gray-100 pb-3">
                        <p class="text-xs text-gray-500 mb-1">Total Harga</p>
                        <p class="text-sm font-medium text-gray-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tukang</p>
                        @if($booking->assigned_worker_id)
                            <p class="text-sm font-medium text-gray-900">{{ $booking->tukang->name ?? 'Tukang tidak ditemukan' }}</p>
                        @else
                            <p class="text-sm font-medium text-yellow-600">Belum ditugaskan</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
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
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded');
        const form = document.getElementById('editBookingForm');
        
        if (form) {
            console.log('Form found');
            console.log('Form action:', form.getAttribute('action'));
            console.log('Form method:', form.getAttribute('method'));
            
            // Tambahkan event listener untuk form submit
            form.addEventListener('submit', function(e) {
                console.log('Form submit triggered');
            });
        } else {
            console.error('Form dengan ID "editBookingForm" tidak ditemukan');
        }
    });
</script>
@endpush
