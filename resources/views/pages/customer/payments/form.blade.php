@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-blue-600 text-white px-6 py-4">
            <h1 class="text-xl font-semibold">Konfirmasi Pembayaran</h1>
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
            
            <form action="{{ route('customer.payments.process', $booking->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                    <select id="payment_method" name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="bank_transfer">Transfer Bank</option>
                        <option value="e-wallet">E-Wallet</option>
                    </select>
                    @error('payment_method')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div id="bank_info" class="mb-4 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-medium mb-2">Informasi Rekening</h3>
                    <p>Bank BCA: 1234567890</p>
                    <p>a/n TataFix</p>
                </div>
                
                <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pembayaran (Rp)</label>
                    <input type="number" id="amount" name="amount" value="{{ $booking->service->base_price * 0.5 }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">*Minimal pembayaran DP 50% dari total biaya</p>
                    @error('amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="proof_of_payment" class="block text-sm font-medium text-gray-700 mb-1">Bukti Pembayaran</label>
                    <input type="file" id="proof_of_payment" name="proof_of_payment" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, JPEG (Maks. 2MB)</p>
                    @error('proof_of_payment')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="payment_notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                    <textarea id="payment_notes" name="payment_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                    @error('payment_notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end">
                    <a href="{{ route('customer.bookings.index') }}" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 mr-2">Batal</a>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">Konfirmasi Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle informasi pembayaran berdasarkan metode yang dipilih
    document.getElementById('payment_method').addEventListener('change', function() {
        const bankInfo = document.getElementById('bank_info');
        if (this.value === 'bank_transfer') {
            bankInfo.innerHTML = `
                <h3 class="font-medium mb-2">Informasi Rekening</h3>
                <p>Bank BCA: 1234567890</p>
                <p>a/n TataFix</p>
            `;
        } else {
            bankInfo.innerHTML = `
                <h3 class="font-medium mb-2">Informasi E-Wallet</h3>
                <p>DANA: 081234567890</p>
                <p>a/n TataFix</p>
            `;
        }
    });
</script>
@endpush