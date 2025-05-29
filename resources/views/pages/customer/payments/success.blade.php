@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-green-600 text-white px-6 py-4">
            <h1 class="text-xl font-semibold">Pembayaran Berhasil</h1>
        </div>
        
        <div class="p-6 text-center">
            <div class="mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <h2 class="text-lg font-semibold mb-2">Bukti Pembayaran Berhasil Diunggah</h2>
                <p class="text-gray-600">Terima kasih telah melakukan pembayaran. Bukti pembayaran Anda sedang divalidasi oleh tim kami.</p>
            </div>
            
            <div class="mb-6 bg-gray-50 p-4 rounded-lg text-left">
                <h3 class="font-medium mb-2">Detail Booking</h3>
                <p><span class="font-medium">ID Booking:</span> #{{ $booking->id }}</p>
                <p><span class="font-medium">Layanan:</span> {{ $booking->service->title_service }}</p>
                <p><span class="font-medium">Status:</span> <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">Menunggu Validasi</span></p>
            </div>
            
            <div class="flex justify-center space-x-4">
                <a href="{{ route('customer.payments.status', $booking->id) }}" class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">Lihat Status Pembayaran</a>
                <a href="{{ route('customer.bookings.index') }}" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300">Kembali ke Daftar Booking</a>
            </div>
        </div>
    </div>
</div>
@endsection