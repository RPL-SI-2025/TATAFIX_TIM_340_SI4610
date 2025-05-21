@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-blue-600 text-white px-6 py-4">
            <h1 class="text-xl font-semibold">Status Pembayaran</h1>
        </div>
        
        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">Detail Booking</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p><span class="font-medium">ID Booking:</span> #{{ $booking->id }}</p>
                    <p><span class="font-medium">Layanan:</span> {{ $booking->service->title_service }}</p>
                    <p><span class="font-medium">Tanggal:</span> {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}</p>
                    <p><span class="font-medium">Waktu:</span> {{ $booking->waktu_booking }}</p>
                    <p><span class="font-medium">Total Biaya:</span> Rp {{ number_format($booking->service->base_price, 0, ',', '.') }}</p>
                </div>
            </div>
            
            @if($payment)
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">Detail Pembayaran</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p><span class="font-medium">Metode Pembayaran:</span> 
                        {{ $payment->payment_method == 'bank_transfer' ? 'Transfer Bank' : 'E-Wallet' }}</p>
                    <p><span class="font-medium">Jumlah:</span> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    <p><span class="font-medium">Tanggal Pembayaran:</span> {{ $payment->created_at->format('d M Y H:i') }}</p>
                    <p><span class="font-medium">Status:</span> 
                        @if($payment->status == 'pending')
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">Menunggu Validasi</span>
                        @elseif($payment->status == 'validated')
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Tervalidasi</span>
                        @elseif($payment->status == 'rejected')
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Ditolak</span>
                        @endif
                    </p>
                    
                    @if($payment->proof_of_payment)
                    <div class="mt-4">
                        <p class="font-medium mb-2">Bukti Pembayaran:</p>
                        <img src="{{ asset('storage/' . $payment->proof_of_payment) }}" alt="Bukti Pembayaran" class="max-w-full h-auto rounded-lg border border-gray-200">
                    </div>
                    @endif
                    
                    @if($payment->payment_notes)
                    <div class="mt-4">
                        <p class="font-medium mb-1">Catatan:</p>
                        <p>{{ $payment->payment_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="mb-6 text-center p-6">
                <p class="text-gray-600">Belum ada informasi pembayaran untuk booking ini.</p>
            </div>
            @endif
            
            <div class="flex justify-end">
                <a href="{{ route('customer.bookings.index') }}" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300">Kembali ke Daftar Booking</a>
            </div>
        </div>
    </div>
</div>
@endsection