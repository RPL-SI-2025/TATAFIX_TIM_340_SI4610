@extends('layouts.admin')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('admin.payments.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Pembayaran
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
        <span class="font-medium">Sukses!</span> {{ session('success') }}
    </div>
    @endif

    <!-- Alert Error -->
    @if(session('error'))
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
        <span class="font-medium">Error!</span> {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Detail Pembayaran -->
        <div class="md:col-span-2 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-blue-600 text-white px-6 py-4">
                <h1 class="text-xl font-semibold">Detail Pembayaran #{{ $payment->id }}</h1>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <h2 class="text-lg font-semibold mb-4">Informasi Pembayaran</h2>
                        <p class="mb-2"><span class="font-medium">ID Pembayaran:</span> #{{ $payment->id }}</p>
                        <p class="mb-2"><span class="font-medium">Metode Pembayaran:</span> 
                            {{ $payment->payment_method == 'bank_transfer' ? 'Transfer Bank' : 'E-Wallet' }}</p>
                        <p class="mb-2"><span class="font-medium">Jumlah:</span> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        <p class="mb-2"><span class="font-medium">Tanggal Pembayaran:</span> {{ $payment->created_at->format('d M Y H:i') }}</p>
                        <p class="mb-2"><span class="font-medium">Status:</span> 
                            @if($payment->status == 'pending')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">Menunggu Validasi</span>
                            @elseif($payment->status == 'validated')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Tervalidasi</span>
                            @elseif($payment->status == 'rejected')
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Ditolak</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold mb-4">Informasi Booking</h2>
                        <p class="mb-2"><span class="font-medium">ID Booking:</span> #{{ $payment->booking->id }}</p>
                        <p class="mb-2"><span class="font-medium">Layanan:</span> {{ $payment->booking->service->title_service }}</p>
                        <p class="mb-2"><span class="font-medium">Customer:</span> {{ $payment->booking->user->name }}</p>
                        <p class="mb-2"><span class="font-medium">Email:</span> {{ $payment->booking->user->email }}</p>
                        <p class="mb-2"><span class="font-medium">Status Booking:</span> {{ $payment->booking->status->display_name }}</p>
                    </div>
                </div>

                @if($payment->payment_notes)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Catatan Customer</h2>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p>{{ $payment->payment_notes }}</p>
                    </div>
                </div>
                @endif

                {{-- Catatan admin tidak disimpan di database --}}
            </div>
        </div>

        <!-- Bukti Pembayaran dan Form Validasi -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if($payment->proof_of_payment)
            <div class="bg-blue-600 text-white px-6 py-4">
                <h2 class="text-lg font-semibold">Bukti Pembayaran</h2>
            </div>
            <div class="p-6">
                <img src="{{ asset('storage/' . $payment->proof_of_payment) }}" alt="Bukti Pembayaran" class="w-full h-auto rounded-lg border border-gray-200 mb-6">
            </div>
            @endif

            @if($payment->status == 'pending')
            <div class="bg-blue-600 text-white px-6 py-4">
                <h2 class="text-lg font-semibold">Validasi Pembayaran</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.payments.validate', $payment->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Validasi</label>
                        <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Pilih Status</option>
                            <option value="validated">Tervalidasi</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Admin</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Simpan Validasi</button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection