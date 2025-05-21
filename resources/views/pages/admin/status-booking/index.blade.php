@extends('layouts.admin')
@section('title', 'Status Booking')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Status Booking</h1>
    <form method="GET" class="flex gap-2 items-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan customer..." class="border rounded px-2 py-1" />
        <div class="flex items-center gap-2">
            <label for="status" class="font-semibold text-sm">Status:</label>
            <select name="status" id="status" class="border rounded px-2 py-1">
                <option value="" {{ empty(request('status')) ? 'selected' : '' }}>Semua</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">Filter</button>
    </form>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

<div class="bg-white rounded shadow p-6">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
                <tr class="bg-gray-50 text-left">
                    <th class="py-2 px-4 font-semibold text-left">Nama Customer</th>
                    <th class="py-2 px-4 font-semibold text-left">Email</th>
                    <th class="py-2 px-4 font-semibold text-left">Status</th>
                    <th class="py-2 px-4 font-semibold text-left">Tanggal Booking</th>
                    <th class="py-2 px-4 font-semibold text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr class="border-b">
                        <td class="py-2 px-4 text-left">{{ $booking->customer }}</td>
                        <td class="py-2 px-4 text-left">{{ $booking->email ?? '-' }}</td>
                        <td class="py-2 px-4 text-left">
                            @php
                                $statusClass = match($booking->status) {
                                    'Pending' => 'bg-yellow-100 text-yellow-800',
                                    'Selesai' => 'bg-green-100 text-green-800',
                                    'Dibatalkan' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs rounded {{ $statusClass }}">{{ $booking->status }}</span>
                        </td>
                        <td class="py-2 px-4 text-left">{{ $booking->created_at->format('d M Y') }}</td>
                        <td class="py-2 px-4 text-center">
                            <a href="{{ route('admin.status-booking.edit', $booking->id) }}" class="inline-block">
                                <button type="button" class="p-2 rounded-full text-gray-600 hover:text-gray-800">
                                    <i class="fas fa-edit text-xl text-gray-600 hover:text-gray-800"></i> <!-- Icon edit dengan warna abu tua -->
                                </button>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-gray-500 py-4">Tidak ada data booking.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection