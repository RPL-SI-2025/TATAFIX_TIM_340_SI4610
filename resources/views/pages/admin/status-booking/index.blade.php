@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Status Booking</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="{{ route('admin.status-booking.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Code</label>
                <input type="text" name="status_code" value="{{ request('status_code') }}" 
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Customer</label>
                <input type="text" name="customer_name" value="{{ request('customer_name') }}" 
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Display Name</label>
                <input type="text" name="display_name" value="{{ request('display_name') }}" 
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Code</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Display Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($bookingStatuses as $status)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $status->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($status->status_code == 'PENDING') bg-yellow-100 text-yellow-800
                                @elseif($status->status_code == 'CONFIRMED') bg-blue-100 text-blue-800
                                @elseif($status->status_code == 'ON_PROCESS') bg-purple-100 text-purple-800
                                @elseif($status->status_code == 'COMPLETED') bg-green-100 text-green-800
                                @elseif($status->status_code == 'CANCELLED') bg-red-100 text-red-800
                                @elseif($status->status_code == 'WAITING_DP') bg-orange-100 text-orange-800
                                @endif">
                                {{ $status->status_code }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $status->display_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.status-booking.edit', $status->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Tidak ada data status booking</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $bookingStatuses->links() }}
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.booking-status-dropdown').on('change', function() {
        var bookingId = $(this).data('booking-id');
        var newStatusId = $(this).val();
        var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Pastikan Anda memiliki meta tag CSRF token di layout Anda

        if (confirm('Apakah Anda yakin ingin mengubah status booking ini?')) {
            $.ajax({
                url: '/admin/status-booking/' + bookingId + '/edit',
                type: 'PUT',
                data: {
                    _token: csrfToken,
                    status_id: newStatusId
                },
                success: function(response) {
                    alert('Status booking berhasil diperbarui!');
                },
                error: function(xhr) {
                    alert('Gagal memperbarui status booking: ' + xhr.responseText);
                }
            });
        }
    });
});
</script>
@endpush

<div class="bg-white rounded shadow p-6 mt-6">
    <h2 class="text-xl font-bold mb-4">Daftar Booking</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="py-2 px-4 font-semibold">Nama Customer</th>
                    <th class="py-2 px-4 font-semibold">Email</th>
                    <th class="py-2 px-4 font-semibold">Layanan</th>
                    <th class="py-2 px-4 font-semibold">Status</th>
                    <th class="py-2 px-4 font-semibold">Tukang</th>
                    <th class="py-2 px-4 font-semibold">Pembayaran</th>
                    <th class="py-2 px-4 font-semibold">Tanggal</th>
                    <th class="py-2 px-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-2 px-4">{{ $booking->user->name }}</td>
                        <td class="py-2 px-4">{{ $booking->user->email }}</td>
                        <td class="py-2 px-4">{{ $booking->service->name }}</td>
                        <td class="py-2 px-4">
                            <span class="px-2 py-1 text-xs rounded {{ $booking->status->color_code }}">
                                {{-- Menampilkan status_code --}}
                                {{ $booking->status->status_code }}
                            </span>
                        </td>
                        <td class="py-2 px-4">
                            {{-- Menggunakan dropdown untuk update status --}}
                            <select class="booking-status-dropdown border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" data-booking-id="{{ $booking->id }}">
                                @foreach($allBookingStatuses as $statusOption)
                                    <option value="{{ $statusOption->id }}" {{ $booking->status_id == $statusOption->id ? 'selected' : '' }}>
                                        {{ $statusOption->display_name }} ({{ $statusOption->status_code }})
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="py-2 px-4">
                            {{ $booking->provider ? $booking->provider->name : '-' }}
                        </td>
                        <td class="py-2 px-4">
                            @if($booking->payments->isNotEmpty())
                                @php
                                    $latestPayment = $booking->payments->last();
                                    $paymentStatus = match($latestPayment->status) {
                                        'VERIFIED' => 'bg-green-100 text-green-800',
                                        'PENDING' => 'bg-yellow-100 text-yellow-800',
                                        'REJECTED' => 'bg-red-100 text-red-800',
                                        'default' => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="px-2 py-1 text-xs rounded {{ $paymentStatus }}">
                                    {{ $latestPayment->payment_type }} - {{ $latestPayment->status }}
                                </span>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="py-2 px-4">{{ $booking->created_at->format('d M Y') }}</td>
                        <td class="py-2 px-4 text-center">
                            <a href="{{ route('admin.status-booking.edit', $booking->id) }}" 
                               class="inline-block p-2 text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit text-lg"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-gray-500 py-4">Tidak ada data booking.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $bookings->links() }}
    </div>
</div>

@endsection