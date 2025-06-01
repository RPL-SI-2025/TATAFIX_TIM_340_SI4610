@extends('layouts.app')

@section('title', 'Detail Booking')

@section('styles')
<style>
    .booking-status {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 1rem;
    }
    .status-pending {
        background-color: #FEF3C7;
        color: #92400E;
    }
    .status-waiting_validation_dp {
        background-color: #DBEAFE;
        color: #1E40AF;
    }
    .status-dp_validated {
        background-color: #D1FAE5;
        color: #065F46;
    }
    .status-in_progress {
        background-color: #E0E7FF;
        color: #3730A3;
    }
    .status-done {
        background-color: #D1FAE5;
        color: #065F46;
    }
    .status-waiting_validation_pelunasan {
        background-color: #DBEAFE;
        color: #1E40AF;
    }
    .status-completed {
        background-color: #D1FAE5;
        color: #065F46;
    }
    .status-rejected {
        background-color: #FEE2E2;
        color: #B91C1C;
    }
    .status-canceled {
        background-color: #F3F4F6;
        color: #4B5563;
    }
    .timeline-container {
        position: relative;
        padding-left: 2rem;
    }
    .timeline-container::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #E5E7EB;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -2rem;
        top: 0.25rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background-color: #E5E7EB;
        border: 2px solid #FFF;
    }
    .timeline-item.active::before {
        background-color: #3B82F6;
    }
    .timeline-item.completed::before {
        background-color: #10B981;
    }
    .card {
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        background-color: white;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    .card-header {
        padding: 1rem 1.5rem;
        background-color: #F9FAFB;
        border-bottom: 1px solid #E5E7EB;
        font-weight: 600;
    }
    .card-body {
        padding: 1.5rem;
    }
    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-primary {
        background-color: #EFF6FF;
        color: #2563EB;
    }
    .badge-success {
        background-color: #ECFDF5;
        color: #059669;
    }
    .badge-warning {
        background-color: #FFFBEB;
        color: #D97706;
    }
    .badge-danger {
        background-color: #FEF2F2;
        color: #DC2626;
    }
    .btn-primary {
        background-color: #2563EB;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        display: inline-block;
        text-decoration: none;
        transition: background-color 0.2s;
    }
    .btn-primary:hover {
        background-color: #1D4ED8;
    }
    .btn-success {
        background-color: #059669;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        display: inline-block;
        text-decoration: none;
        transition: background-color 0.2s;
    }
    .btn-success:hover {
        background-color: #047857;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('booking.history') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Riwayat Booking
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <!-- Booking Status -->
            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <h2 class="text-lg font-bold">Detail Booking #{{ $booking->id }}</h2>
                    <span class="booking-status status-{{ $booking->status->status_code }}">
                        {{ $booking->status->display_name }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="mb-2"><span class="font-semibold">Tanggal Booking:</span> {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</p>
                            <p class="mb-2"><span class="font-semibold">Waktu:</span> {{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }} WIB</p>
                            <p class="mb-2"><span class="font-semibold">Layanan:</span> {{ $booking->service->title_service }}</p>
                            <p class="mb-2"><span class="font-semibold">Kategori:</span> {{ $booking->service->category->name }}</p>
                        </div>
                        <div>
                            <p class="mb-2"><span class="font-semibold">Alamat:</span> {{ $booking->address }}</p>
                            <p class="mb-2"><span class="font-semibold">Harga Dasar:</span> Rp {{ number_format($booking->base_price, 0, ',', '.') }}</p>
                            <p class="mb-2"><span class="font-semibold">Total Harga:</span> Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                            <p class="mb-2"><span class="font-semibold">Status Terakhir:</span> {{ \Carbon\Carbon::parse($booking->status_updated_at)->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    @if($booking->notes)
                    <div class="mt-4">
                        <h3 class="font-semibold mb-2">Catatan:</h3>
                        <div class="p-3 bg-gray-50 rounded-md">
                            {{ $booking->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Tukang Information (if assigned) -->
            @if($booking->tukang)
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-bold">Informasi Tukang</h2>
                </div>
                <div class="card-body">
                    <div class="flex items-center mb-4">
                        <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                            @if($booking->tukang->profile_picture)
                                <img src="{{ asset('storage/' . $booking->tukang->profile_picture) }}" alt="{{ $booking->tukang->name }}" class="w-16 h-16 rounded-full object-cover">
                            @else
                                <i class="fas fa-user text-gray-400 text-2xl"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">{{ $booking->tukang->name }}</h3>
                            <p class="text-gray-600">{{ $booking->tukang->email }}</p>
                            <p class="text-gray-600">{{ $booking->tukang->phone ?? 'No phone number' }}</p>
                        </div>
                    </div>
                    
                    @if($booking->tukang->specialization)
                    <div class="mb-2">
                        <span class="font-semibold">Spesialisasi:</span> {{ $booking->tukang->specialization }}
                    </div>
                    @endif
                    
                    @if($booking->tukang->experience)
                    <div class="mb-2">
                        <span class="font-semibold">Pengalaman:</span> {{ $booking->tukang->experience }} tahun
                    </div>
                    @endif
                    
                    @if($booking->status->status_code == 'in_progress')
                    <div class="mt-4">
                        <a href="tel:{{ $booking->tukang->phone }}" class="btn-primary">
                            <i class="fas fa-phone-alt mr-2"></i> Hubungi Tukang
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Payment History -->
            @if(count($booking->payments) > 0)
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-bold">Riwayat Pembayaran</h2>
                </div>
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">ID</th>
                                    <th class="px-4 py-2 text-left">Tanggal</th>
                                    <th class="px-4 py-2 text-left">Jumlah</th>
                                    <th class="px-4 py-2 text-left">Metode</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($booking->payments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">#{{ $payment->id }}</td>
                                    <td class="px-4 py-3">{{ $payment->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-4 py-3">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        @if($payment->payment_method == 'bank_transfer')
                                            <span class="badge badge-primary">Transfer Bank</span>
                                        @else
                                            <span class="badge badge-primary">E-Wallet</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($payment->status == 'pending')
                                            <span class="badge badge-warning">Menunggu Validasi</span>
                                        @elseif($payment->status == 'validated')
                                            <span class="badge badge-success">Tervalidasi</span>
                                        @elseif($payment->status == 'rejected')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="lg:col-span-1">
            <!-- Booking Status Timeline -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-bold">Status Booking</h2>
                </div>
                <div class="card-body">
                    <div class="timeline-container">
                        @php
                            $statusOrder = [
                                'pending' => 1,
                                'waiting_validation_dp' => 2,
                                'dp_validated' => 3,
                                'in_progress' => 4,
                                'done' => 5,
                                'waiting_validation_pelunasan' => 6,
                                'completed' => 7,
                                'rejected' => 8,
                                'canceled' => 9
                            ];
                            $currentStatusCode = $booking->status->status_code;
                            $currentStatusOrder = $statusOrder[$currentStatusCode] ?? 0;
                        @endphp

                        <div class="timeline-item {{ $currentStatusOrder >= 1 ? 'completed' : '' }}">
                            <h3 class="font-semibold">Booking Dibuat</h3>
                            <p class="text-sm text-gray-600">Booking telah dibuat dan menunggu pembayaran DP</p>
                        </div>

                        <div class="timeline-item {{ $currentStatusOrder >= 2 ? ($currentStatusOrder > 2 ? 'completed' : 'active') : '' }}">
                            <h3 class="font-semibold">Menunggu Validasi DP</h3>
                            <p class="text-sm text-gray-600">Pembayaran DP sedang divalidasi oleh admin</p>
                        </div>

                        <div class="timeline-item {{ $currentStatusOrder >= 3 ? ($currentStatusOrder > 3 ? 'completed' : 'active') : '' }}">
                            <h3 class="font-semibold">DP Tervalidasi</h3>
                            <p class="text-sm text-gray-600">Pembayaran DP telah divalidasi, menunggu penugasan tukang</p>
                        </div>

                        <div class="timeline-item {{ $currentStatusOrder >= 4 ? ($currentStatusOrder > 4 ? 'completed' : 'active') : '' }}">
                            <h3 class="font-semibold">Dalam Pengerjaan</h3>
                            <p class="text-sm text-gray-600">Tukang sedang mengerjakan layanan</p>
                        </div>

                        <div class="timeline-item {{ $currentStatusOrder >= 5 ? ($currentStatusOrder > 5 ? 'completed' : 'active') : '' }}">
                            <h3 class="font-semibold">Pengerjaan Selesai</h3>
                            <p class="text-sm text-gray-600">Tukang telah menyelesaikan pekerjaan, menunggu pelunasan</p>
                        </div>

                        <div class="timeline-item {{ $currentStatusOrder >= 6 ? ($currentStatusOrder > 6 ? 'completed' : 'active') : '' }}">
                            <h3 class="font-semibold">Menunggu Validasi Pelunasan</h3>
                            <p class="text-sm text-gray-600">Pembayaran pelunasan sedang divalidasi oleh admin</p>
                        </div>

                        <div class="timeline-item {{ $currentStatusOrder >= 7 ? 'active' : '' }}">
                            <h3 class="font-semibold">Booking Selesai</h3>
                            <p class="text-sm text-gray-600">Booking telah selesai dan semua pembayaran telah divalidasi</p>
                        </div>

                        @if($currentStatusCode == 'rejected' || $currentStatusCode == 'canceled')
                        <div class="timeline-item active">
                            <h3 class="font-semibold">{{ $currentStatusCode == 'rejected' ? 'Booking Ditolak' : 'Booking Dibatalkan' }}</h3>
                            <p class="text-sm text-gray-600">
                                {{ $currentStatusCode == 'rejected' ? 'Booking telah ditolak oleh admin' : 'Booking telah dibatalkan' }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-bold">Aksi</h2>
                </div>
                <div class="card-body">
                    @if($booking->status->status_code == 'pending')
                    <a href="{{ route('booking.payment.create', $booking->id) }}" class="btn-primary w-full block text-center mb-3">
                        <i class="fas fa-credit-card mr-2"></i> Bayar DP
                    </a>
                    @endif

                    @if($booking->status->status_code == 'done')
                    <a href="{{ route('booking.payment.create', $booking->id) }}" class="btn-success w-full block text-center mb-3">
                        <i class="fas fa-credit-card mr-2"></i> Bayar Pelunasan
                    </a>
                    @endif

                    @if(in_array($booking->status->status_code, ['pending', 'waiting_validation_dp']))
                    <form action="{{ route('booking.cancel', $booking->id) }}" method="POST" class="mt-3">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500" onclick="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                            <i class="fas fa-times-circle mr-2"></i> Batalkan Booking
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
