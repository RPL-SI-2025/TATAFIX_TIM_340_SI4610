@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Daftar Booking yang Dapat Direview</h1>

    @if($bookings->isEmpty())
        <div class="bg-gray-100 rounded-lg p-6 text-center">
            <p class="text-gray-600">Tidak ada booking yang dapat direview saat ini.</p>
        </div>
    @else
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($bookings as $booking)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-lg mb-2">Booking #{{ $booking->id }}</h3>
                <p class="text-gray-600 mb-2">Layanan: {{ $booking->service->name }}</p>
                <p class="text-gray-600 mb-4">Tanggal: {{ $booking->booking_date }}</p>
                <a href="{{ route('review.show', $booking) }}" 
                   class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    Beri Review
                </a>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection