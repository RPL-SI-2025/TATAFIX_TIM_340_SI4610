@extends('layouts.admin')

@section('content')
<script>
    // Redirect to the new booking detail page
    window.location.href = '{{ route("admin.bookings.show", $booking->id) }}';
</script>
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.status-booking.index') }}" class="text-blue-500 hover:text-blue-700 mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold">Edit Status Booking</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <form action="{{ route('admin.status-booking.update', $booking->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            {{-- Nama Customer --}}
            <div class="mb-4">
                <label for="nama_pemesan" class="block text-sm font-medium text-gray-700 mb-1">Nama Customer</label>
                <input
                    type="text"
                    name="nama_pemesan"
                    id="nama_pemesan"
                    value="{{ $booking->nama_pemesan }}"
                    disabled
                    class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 cursor-not-allowed"
                >
            </div>

            {{-- Layanan --}}
            <div class="mb-4">
                <label for="service_name" class="block text-sm font-medium text-gray-700 mb-1">Layanan</label>
                <input
                    type="text"
                    name="service_name"
                    id="service_name"
                    value="{{ $booking->service->title_service }}"
                    disabled
                    class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 cursor-not-allowed"
                >
            </div>

            {{-- Tanggal --}}
            <div class="mb-4">
                <label for="tanggal_booking" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input
                    type="text"
                    name="tanggal_booking"
                    id="tanggal_booking"
                    value="{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}"
                    disabled
                    class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 cursor-not-allowed"
                >
            </div>

            {{-- Status --}}
            <div class="mb-4">
                <label for="status_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Status<span class="text-red-500">*</span>
                </label>
                <select
                    name="status_id"
                    id="status_id"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 {{ in_array($booking->status->status_code, ['COMPLETED', 'CANCELLED']) ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                    {{ in_array($booking->status->status_code, ['COMPLETED', 'CANCELLED']) ? 'disabled' : '' }}
                >
                    @foreach($allBookingStatuses as $status)
                        @php
                            $bgColorClass = $borderColorClass = $textColorClass = '';

                            switch ($status->status_code) {
                                case 'PENDING':
                                    $bgColorClass = 'bg-yellow-100';
                                    $borderColorClass = 'border-yellow-500';
                                    $textColorClass = 'text-yellow-800';
                                    break;
                                case 'CONFIRMED':
                                    $bgColorClass = 'bg-blue-100';
                                    $borderColorClass = 'border-blue-500';
                                    $textColorClass = 'text-blue-800';
                                    break;
                                case 'ON_PROCESS':
                                    $bgColorClass = 'bg-purple-100';
                                    $borderColorClass = 'border-purple-500';
                                    $textColorClass = 'text-purple-800';
                                    break;
                                case 'COMPLETED':
                                    $bgColorClass = 'bg-green-200';
                                    $borderColorClass = 'border-green-500';
                                    $textColorClass = 'text-green-900';
                                    break;
                                case 'CANCELLED':
                                    $bgColorClass = 'bg-red-200';
                                    $borderColorClass = 'border-red-500';
                                    $textColorClass = 'text-red-900';
                                    break;
                                case 'WAITING_DP':
                                    $bgColorClass = 'bg-orange-100';
                                    $borderColorClass = 'border-orange-500';
                                    $textColorClass = 'text-orange-800';
                                    break;
                                default:
                                    $bgColorClass = 'bg-gray-100';
                                    $borderColorClass = 'border-gray-500';
                                    $textColorClass = 'text-gray-800';
                                    break;
                            }
                        @endphp
                        <option
                            value="{{ $status->id }}"
                            {{ $booking->status_id == $status->id ? 'selected' : '' }}
                            class="{{ $bgColorClass }} {{ $borderColorClass }} border-l-4 {{ $textColorClass }} py-1 px-2"
                            data-color-class="{{ $bgColorClass }}"
                        >
                            {{ $status->status_code }}
                        </option>
                    @endforeach
                </select>
                @error('status_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end space-x-3">
                <a
                    href="{{ route('admin.status-booking.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    {{ in_array($booking->status->status_code, ['COMPLETED', 'CANCELLED']) ? 'disabled' : '' }}
                >
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusSelect = document.getElementById('status_id');

        function applySelectedOptionColor() {
            const selectedOption = statusSelect.options[statusSelect.selectedIndex];
            const colorClass = selectedOption.getAttribute('data-color-class');

            // Remove previous bg-* classes
            statusSelect.classList.forEach(className => {
                if (className.startsWith('bg-')) {
                    statusSelect.classList.remove(className);
                }
            });

            // Add new bg-* class
            if (colorClass) {
                statusSelect.classList.add(colorClass);
            }
        }

        applySelectedOptionColor(); // On load
        statusSelect.addEventListener('change', applySelectedOptionColor); // On change
    });
</script>
@endsection
