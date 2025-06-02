@extends('layouts.admin')

@section('title', 'Detail Booking')

@section('styles')
<style>
    /* Print Styling */
    @media print {
        .print-hide {
            display: none !important;
        }
        .print-show {
            display: block !important;
        }
        .print-container {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container px-4 py-6 mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Detail Booking</h1>
            <nav class="mt-1">
                <ol class="flex text-sm">
                    <li class="text-gray-500 hover:text-gray-700"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="mx-2 text-gray-400">/</li>
                    <li class="text-gray-500 hover:text-gray-700"><a href="{{ route('admin.bookings.index') }}">Booking</a></li>
                    <li class="mx-2 text-gray-400">/</li>
                    <li class="text-gray-700 font-medium">Detail #{{ $booking->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="mt-4 sm:mt-0 print-hide">
            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center px-3 py-2 mr-2 text-sm font-medium rounded-md text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button onclick="window.print()" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                <i class="fas fa-print mr-2"></i> Cetak
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 rounded-md bg-green-50 border border-green-200 print-hide">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-200 print-hide">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-600"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Status Timeline Card -->
    <div class="mb-6">
        <div class="w-full">
            <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 overflow-hidden">
                <div class="p-5">
                    @php
                        $statusCode = $booking->status->status_code;
                        $badgeClass = 'gray';
                        $statusIcon = 'fa-info-circle';
                        $bgColor = 'bg-gray-500';
                        $textColor = 'text-gray-800';
                        $bgBadge = 'bg-gray-500';
                        
                        if (in_array($statusCode, ['pending', 'waiting_pelunasan'])) {
                            $badgeClass = 'yellow';
                            $statusIcon = 'fa-clock';
                            $bgColor = 'bg-yellow-500';
                            $textColor = 'text-yellow-800';
                            $bgBadge = 'bg-yellow-500';
                        } elseif (in_array($statusCode, ['waiting_validation_dp', 'waiting_validation_pelunasan'])) {
                            $badgeClass = 'blue';
                            $statusIcon = 'fa-search-dollar';
                            $bgColor = 'bg-blue-500';
                            $textColor = 'text-blue-800';
                            $bgBadge = 'bg-blue-500';
                        } elseif (in_array($statusCode, ['confirmed', 'dp_paid', 'waiting_tukang_response', 'tukang_accepted'])) {
                            $badgeClass = 'indigo';
                            $statusIcon = 'fa-calendar-check';
                            $bgColor = 'bg-indigo-500';
                            $textColor = 'text-indigo-800';
                            $bgBadge = 'bg-indigo-500';
                        } elseif (in_array($statusCode, ['in_progress', 'waiting_payment_confirmation'])) {
                            $badgeClass = 'blue';
                            $statusIcon = 'fa-tools';
                            $bgColor = 'bg-blue-500';
                            $textColor = 'text-blue-800';
                            $bgBadge = 'bg-blue-500';
                        } elseif ($statusCode == 'payment_confirmed') {
                            $badgeClass = 'blue';
                            $statusIcon = 'fa-clipboard-check';
                            $bgColor = 'bg-blue-500';
                            $textColor = 'text-blue-800';
                            $bgBadge = 'bg-blue-500';
                        } elseif ($statusCode == 'completed') {
                            $badgeClass = 'green';
                            $statusIcon = 'fa-check-double';
                            $bgColor = 'bg-green-500';
                            $textColor = 'text-green-800';
                            $bgBadge = 'bg-green-500';
                        } elseif (in_array($statusCode, ['rejected', 'canceled'])) {
                            $badgeClass = 'red';
                            $statusIcon = 'fa-times-circle';
                            $bgColor = 'bg-red-500';
                            $textColor = 'text-red-800';
                            $bgBadge = 'bg-red-500';
                        }
                    @endphp
                    
                    <div class="flex items-center">
                        <div class="mr-4">
                            <div class="h-10 w-10 rounded-full {{ $bgColor }} flex items-center justify-center">
                                <i class="fas {{ $statusIcon }} text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="font-semibold mb-1">Status saat ini</h6>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium text-white {{ $bgBadge }}">
                                <i class="fas {{ $statusIcon }} mr-1"></i> {{ $booking->status->status_name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 bg-white border-b border-gray-200 flex justify-between items-center">
                    <h6 class="text-lg font-semibold text-blue-600">Informasi Booking</h6>
                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-1 rounded-md">ID: #{{ $booking->id }}</span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <div class="mb-4">
                                <div class="text-sm text-gray-500 mb-1">Tanggal Booking</div>
                                <div class="text-gray-900 font-medium">
                                    {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="text-sm text-gray-500 mb-1">Waktu Booking</div>
                                <div class="text-gray-900 font-medium">
                                    {{ $booking->waktu_booking }}
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <div class="text-sm text-gray-500 mb-1">Layanan</div>
                                <div class="text-gray-900 font-medium">
                                    {{ $booking->service->title_service }}
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="text-sm text-gray-500 mb-1">Kategori</div>
                                <div class="text-gray-900 font-medium">
                                    {{ $booking->service->category->name }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="mb-1 font-weight-bold">Alamat</p>
                            <p>{{ $booking->alamat }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="mb-1 font-weight-bold">Catatan</p>
                            <p>{{ $booking->notes ?? 'Tidak ada catatan' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 bg-white border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-blue-600">Detail Layanan</h6>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <div class="text-sm text-gray-500 mb-1">Nama Layanan</div>
                                <div class="text-gray-900 font-medium">{{ $booking->service->title_service }}</div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="text-sm text-gray-500 mb-1">Kategori</div>
                                <div class="text-gray-900 font-medium">{{ $booking->service->category->name }}</div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="text-sm text-gray-500 mb-1">Harga</div>
                                <div class="text-gray-900 font-medium">Rp {{ number_format($booking->service->price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <div class="text-sm text-gray-500 mb-1">Durasi</div>
                                <div class="text-gray-900 font-medium">{{ $booking->service->duration }} Jam</div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="text-sm text-gray-500 mb-1">Rating</div>
                                <div class="text-gray-900 font-medium">
                                    <i class="fas fa-star text-yellow-400"></i> 
                                    {{ number_format($booking->service->rating, 1) }} ({{ $booking->service->reviews_count ?? 0 }} ulasan)
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="text-sm text-gray-500 mb-1">Deskripsi Layanan</div>
                                <div class="text-gray-900 font-medium">{{ $booking->service->description }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6 border-l-4 border-green-500">
                <div class="px-6 py-4 bg-white border-b border-gray-200 flex justify-between items-center">
                    <h6 class="text-lg font-semibold text-blue-600">Riwayat Pembayaran</h6>
                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-1 rounded-md">{{ $booking->payments->count() }} Transaksi</span>
                </div>
                <div class="p-6">
                    @if($booking->payments->isEmpty())
                        <div class="text-center py-8">
                            <div class="mb-4">
                                <i class="fas fa-money-bill-wave text-5xl text-green-500 opacity-50"></i>
                            </div>
                            <p class="text-gray-500">Belum ada riwayat pembayaran untuk booking ini.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($booking->payments as $payment)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="font-medium text-gray-900">#{{ $payment->id }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $payment->created_at->format('d M Y') }}</div>
                                                <div class="text-sm text-gray-500">{{ $payment->created_at->format('H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    // Tentukan jenis pembayaran berdasarkan urutan
                                                    $paymentIndex = $booking->payments->sortBy('created_at')->search(function($item) use ($payment) {
                                                        return $item->id === $payment->id;
                                                    });
                                                    $isDP = $paymentIndex === 0;
                                                @endphp
                                                @if($isDP)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Down Payment</span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Pelunasan</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($payment->payment_method == 'bank_transfer')
                                                    <span class="inline-flex items-center">
                                                        <i class="fas fa-university mr-1.5 text-gray-600"></i> Transfer Bank
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center">
                                                        <i class="fas fa-wallet mr-1.5 text-gray-600"></i> E-Wallet
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($payment->status == 'pending')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <i class="fas fa-clock mr-1"></i> Menunggu Validasi
                                                    </span>
                                                @elseif($payment->status == 'approved')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-check-circle mr-1"></i> Disetujui
                                                    </span>
                                                @elseif($payment->status == 'rejected')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-times-circle mr-1"></i> Ditolak
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.payments.show', $payment->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <i class="fas fa-eye mr-1"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Review Card -->
            @if(!is_null($booking->rating) || $booking->status->status_code == 'completed')
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6 border-l-4 border-yellow-500">
                <div class="px-6 py-4 bg-white border-b border-gray-200 flex items-center">
                    <h6 class="text-lg font-semibold text-yellow-600">
                        <i class="fas fa-star mr-2"></i> Review Customer
                    </h6>
                </div>
                <div class="p-6">
                    @if(!is_null($booking->rating))
                        <div class="text-center mb-4">
                            <div class="inline-block bg-gray-100 px-4 py-2 rounded-lg">
                                <div class="flex items-center justify-center">
                                    <div class="mr-2 font-medium">Rating:</div>
                                    <div class="flex">
                                        @php
                                            $rating = $booking->rating ?? 0;
                                        @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $rating)
                                                <span class="text-yellow-400 text-xl mx-1">★</span>
                                            @else
                                                <span class="text-gray-300 text-xl mx-1">★</span>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 mb-3">
                            <h6 class="text-sm font-medium text-gray-600 mb-2">Feedback Customer:</h6>
                            <p class="text-gray-800">{{ $booking->feedback }}</p>
                        </div>
                        <div class="text-gray-500 text-sm text-center">
                            <i class="fas fa-clock mr-1"></i> Diberikan pada: {{ $booking->updated_at->format('d M Y H:i') }}
                        </div>
                    @else
                        <div class="p-4 rounded-md bg-blue-50 border border-blue-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-blue-800">Customer belum memberikan review untuk booking ini.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Card untuk konten lain jika diperlukan di masa depan -->

        </div>

        <div class="lg:col-span-1">
            <!-- Aksi Card -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6 border-l-4 border-blue-500">
                <div class="px-6 py-4 bg-white border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-blue-600">Aksi</h6>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @php
                            $statusCode = $booking->status->status_code;
                        @endphp
                        
                        @if($statusCode == 'waiting_validation_dp' && $booking->payments()->latest()->first())
                            <a href="{{ route('admin.payments.show', $booking->payments()->latest()->first()->id) }}" class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mb-2">
                                <i class="fas fa-check-circle mr-2"></i> Validasi Pembayaran DP
                            </a>
                        @endif
                        
                        @if($statusCode == 'waiting_validation_pelunasan' && $booking->payments()->latest()->first())
                            <a href="{{ route('admin.payments.show', $booking->payments()->latest()->first()->id) }}" class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mb-2">
                                <i class="fas fa-check-double mr-2"></i> Validasi Pelunasan
                            </a>
                        @endif
                        
                        @if($statusCode == 'dp_validated' && !$booking->tukang)
                            <button type="button" class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mb-2" onclick="document.getElementById('assignTukangForm').classList.toggle('hidden')">
                                <i class="fas fa-user-plus mr-2"></i> Pilih Tukang
                            </button>
                        @endif
                        
                        <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 mb-2">
                            <i class="fas fa-edit mr-2"></i> Edit Booking
                        </a>
                        
                        <button type="button" class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="document.getElementById('deleteBookingForm').classList.toggle('hidden')">
                            <i class="fas fa-trash mr-2"></i> Hapus Booking
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6 border-l-4 border-yellow-500">
                <div class="px-6 py-4 bg-white border-b border-gray-200 flex justify-between items-center">
                    <h6 class="text-lg font-semibold text-blue-600">Tukang</h6>
                    @if($booking->tukang)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i> Ditugaskan
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Belum Ditugaskan
                        </span>
                    @endif
                </div>
                <div class="p-6">
                    @if($booking->tukang)
                        <div class="flex flex-col sm:flex-row items-center mb-6">
                            <div class="mb-4 sm:mb-0 sm:mr-4">
                                <img class="h-20 w-20 rounded-full border border-gray-200 object-cover" src="{{ $booking->tukang->profile_picture ? asset('storage/' . $booking->tukang->profile_picture) : asset('img/undraw_profile.svg') }}">
                            </div>
                            <div class="text-center sm:text-left">
                                <h5 class="text-xl font-semibold text-gray-900 mb-1">{{ $booking->tukang->name }}</h5>
                                <p class="text-gray-600 mb-1">
                                    <i class="fas fa-envelope mr-1"></i> {{ $booking->tukang->email }}
                                </p>
                                @if($booking->tukang->phone)
                                    <p class="text-gray-600">
                                        <i class="fas fa-phone mr-1"></i> {{ $booking->tukang->phone }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex justify-center">
                            <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500" onclick="document.getElementById('changeTukangForm').classList.toggle('hidden')">
                                <i class="fas fa-exchange-alt mr-2"></i> Ganti Tukang
                            </button>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mb-4">
                                <i class="fas fa-user-hard-hat text-5xl text-yellow-500 opacity-50"></i>
                            </div>
                            <p class="mb-6 text-gray-500">Belum ada tukang yang ditugaskan untuk booking ini.</p>
                            <a href="{{ route('admin.bookings.assign', $booking->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-user-plus mr-2"></i> Tugaskan Tukang
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información adicional del técnico ya se ha incluido en la sección Tailwind anterior -->
        </div>

            <!-- Sección de acciones ya convertida a Tailwind arriba -->
        </div>
    </div>
</div>

<!-- Cancel Booking Form (Collapsible) -->
<div id="cancelBookingForm" class="hidden mt-4 bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
    <div class="px-6 py-4 bg-white border-b border-gray-200 flex justify-between items-center">
        <h5 class="text-lg font-medium text-gray-900">Konfirmasi Pembatalan</h5>
        <button type="button" class="text-gray-400 hover:text-gray-500" onclick="document.getElementById('cancelBookingForm').classList.add('hidden');">
            <span class="sr-only">Close</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="px-6 py-4">
            <p class="text-gray-700 mb-4">Apakah Anda yakin ingin membatalkan booking ini?</p>
            <div class="mb-4">
                <label for="cancel_reason" class="block text-sm font-medium text-gray-700 mb-1">Alasan Pembatalan</label>
                <textarea id="cancel_reason" name="cancel_reason" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required></textarea>
            </div>
        </div>
        <div class="px-6 py-3 bg-gray-50 flex justify-end space-x-2">
            <button type="button" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="document.getElementById('cancelBookingForm').classList.add('hidden');">Batal</button>
            <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Batalkan Booking</button>
        </div>
    </form>
</div>

<!-- Assign Tukang Form (Collapsible) -->
<div id="assignTukangForm" class="hidden mt-4 bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
    <div class="px-6 py-4 bg-white border-b border-gray-200 flex justify-between items-center">
        <h5 class="text-lg font-medium text-gray-900">Pilih Tukang</h5>
        <button type="button" class="text-gray-400 hover:text-gray-500" onclick="document.getElementById('assignTukangForm').classList.add('hidden');">
            <span class="sr-only">Close</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <form action="{{ route('admin.bookings.assign.store', $booking->id) }}" method="POST">
        @csrf
        <div class="px-6 py-4">
            <div class="mb-4">
                <label for="tukang_id" class="block text-sm font-medium text-gray-700 mb-1">Tukang</label>
                <select id="tukang_id" name="tukang_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" required>
                    <option value="">-- Pilih Tukang --</option>
                    @foreach($tukangs ?? [] as $tukang)
                        <option value="{{ $tukang->id }}">{{ $tukang->name }} - {{ $tukang->specialization ?? 'Umum' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                <textarea id="notes" name="notes" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Tambahkan catatan untuk tukang"></textarea>
            </div>
        </div>
        <div class="px-6 py-3 bg-gray-50 flex justify-end space-x-2">
            <button type="button" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="document.getElementById('assignTukangForm').classList.add('hidden');">Batal</button>
            <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Simpan</button>
        </div>
    </form>
</div>

<!-- Change Tukang Form (Collapsible) -->
<div id="changeTukangForm" class="hidden mt-4 bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
    <div class="px-6 py-4 bg-white border-b border-gray-200 flex justify-between items-center">
        <h5 class="text-lg font-medium text-gray-900">Ganti Tukang</h5>
        <button type="button" class="text-gray-400 hover:text-gray-500" onclick="document.getElementById('changeTukangForm').classList.add('hidden');">
            <span class="sr-only">Close</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <form action="{{ route('admin.bookings.assign.store', $booking->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="px-6 py-4">
            <div class="p-4 mb-4 rounded-md bg-yellow-50 border border-yellow-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-800">Anda akan mengganti tukang yang saat ini ditugaskan ({{ $booking->tukang->name ?? '' }}).</p>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label for="tukang_id_change" class="block text-sm font-medium text-gray-700 mb-1">Tukang Baru</label>
                <select id="tukang_id_change" name="tukang_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" required>
                    <option value="">-- Pilih Tukang --</option>
                    @foreach($tukangs ?? [] as $tukang)
                        <option value="{{ $tukang->id }}">{{ $tukang->name }} - {{ $tukang->specialization ?? 'Umum' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="change_reason" class="block text-sm font-medium text-gray-700 mb-1">Alasan Penggantian</label>
                <textarea id="change_reason" name="change_reason" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Alasan penggantian tukang" required></textarea>
            </div>
        </div>
        <div class="px-6 py-3 bg-gray-50 flex justify-end space-x-2">
            <button type="button" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="document.getElementById('changeTukangForm').classList.add('hidden');">Batal</button>
            <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-yellow-600 border border-transparent rounded-md shadow-sm hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">Ganti Tukang</button>
        </div>
    </form>
</div>

<!-- Delete Booking Form (Collapsible) -->
<div id="deleteBookingForm" class="hidden mt-4 bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
    <div class="px-6 py-4 bg-white border-b border-gray-200 flex justify-between items-center">
        <h5 class="text-lg font-medium text-gray-900">Hapus Booking</h5>
        <button type="button" class="text-gray-400 hover:text-gray-500" onclick="document.getElementById('deleteBookingForm').classList.add('hidden');">
            <span class="sr-only">Close</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="px-6 py-4">
            <div class="p-4 mb-4 rounded-md bg-red-50 border border-red-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">Anda yakin ingin menghapus booking ini? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label for="delete_reason" class="block text-sm font-medium text-gray-700 mb-1">Alasan Penghapusan</label>
                <textarea id="delete_reason" name="delete_reason" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Berikan alasan penghapusan booking" required></textarea>
            </div>
        </div>
        <div class="px-6 py-3 bg-gray-50 flex justify-end space-x-2">
            <button type="button" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="document.getElementById('deleteBookingForm').classList.add('hidden');">Batal</button>
            <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Hapus Booking</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Función para ocultar todos los formularios desplegables
        function hideAllForms() {
            const forms = [
                'cancelBookingForm',
                'assignTukangForm',
                'changeTukangForm',
                'deleteBookingForm'
            ];
            
            forms.forEach(formId => {
                const form = document.getElementById(formId);
                if (form) {
                    form.classList.add('hidden');
                }
            });
        }
        
        // Asegurarse de que todos los formularios estén ocultos al cargar la página
        hideAllForms();
        
        // Agregar controladores de eventos para los botones de cierre dentro de los formularios
        const closeButtons = document.querySelectorAll('[onclick*="classList.add(\'hidden\');"]');
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                hideAllForms();
            });
        });
        
        // Modificar los botones de acción para mostrar solo un formulario a la vez
        const actionButtons = document.querySelectorAll('[onclick*="classList.toggle(\'hidden\');"]');
        actionButtons.forEach(button => {
            const originalOnClick = button.getAttribute('onclick');
            button.setAttribute('onclick', `hideAllForms(); ${originalOnClick}`);
        });
    });
</script>
@endpush
@endsection
