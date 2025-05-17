@extends('layouts.admin')
@section('title', 'Edit Status Booking')
@section('content')
<div class="max-w-xl mx-auto bg-white rounded shadow p-8">
    <h2 class="text-2xl font-bold mb-6">Edit Status Booking</h2>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.status-booking.update', $booking->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nama Customer</label>
            <input type="text" value="{{ $booking->customer }}" class="w-full border rounded px-3 py-2 bg-gray-100 text-gray-600" disabled>
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Tanggal Booking</label>
            <input type="text" value="{{ $booking->created_at->format('d M Y') }}" class="w-full border rounded px-3 py-2 bg-gray-100 text-gray-600" disabled>
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Status</label>
            <select name="status" id="status" class="w-full border rounded px-3 py-2" required onchange="updateDropdownColor()">
                <option value="Pending" {{ $booking->status === 'Pending' ? 'selected' : '' }} class="bg-yellow-100 text-yellow-800">Pending</option>
                <option value="Selesai" {{ $booking->status === 'Selesai' ? 'selected' : '' }} class="bg-green-100 text-green-800">Selesai</option>
                <option value="Dibatalkan" {{ $booking->status === 'Dibatalkan' ? 'selected' : '' }} class="bg-red-100 text-red-800">Dibatalkan</option>
            </select>
        </div>

        <div class="flex justify-end gap-4 pt-6">
            <a href="{{ route('admin.status-booking') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg text-sm">Batal</a>
            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold">Simpan</button>
        </div>
    </form>
</div>

<script>
    function updateDropdownColor() {
        const selectElement = document.getElementById('status');
        const selectedValue = selectElement.value;
        let colorClass = '';

        switch (selectedValue) {
            case 'Pending':
                colorClass = 'bg-yellow-100 text-yellow-800';
                break;
            case 'Selesai':
                colorClass = 'bg-green-100 text-green-800';
                break;
            case 'Dibatalkan':
                colorClass = 'bg-red-100 text-red-800';
                break;
            default:
                colorClass = 'bg-gray-100 text-gray-800';
        }

        selectElement.className = 'w-full border rounded px-3 py-2 ' + colorClass;
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateDropdownColor();
    });
</script>
@endsection
