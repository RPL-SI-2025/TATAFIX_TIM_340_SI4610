@extends('layouts.app')

@section('title', 'Buat Booking')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-blue-600 mb-6">Buat Booking Layanan</h1>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-6 mb-6">
                <div class="w-full md:w-1/3">
                    @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title_service }}" class="w-full h-auto rounded-lg object-cover">
                    @else
                        <img src="{{ asset('images/default-service.jpg') }}" alt="{{ $service->title_service }}" class="w-full h-auto rounded-lg object-cover">
                    @endif
                </div>
                <div class="w-full md:w-2/3">
                    <h2 class="text-xl font-semibold mb-1">{{ $service->title_service }}</h2>
                    <p class="text-gray-600 mb-2">{{ $service->category->name }}</p>
                    <div class="flex items-center mb-3">
                        <div class="flex items-center mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            {{ number_format($service->rating_avg, 1) }}
                        </div>
                        <div class="text-gray-400 mx-2">|</div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            {{ $service->provider->name }}
                        </div>
                    </div>
                    <p class="text-xl font-bold text-orange-500 mb-3">Rp {{ number_format($service->base_price, 0, ',', '.') }}</p>
                    <p class="text-gray-700">{{ $service->description }}</p>
                </div>
                    </div>

                    <form action="{{ route('booking.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $service->service_id }}">
                        
                        <div class="mb-5">
                            <label for="tanggal_booking" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Booking <span class="text-red-500">*</span></label>
                            <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_booking') border-red-500 @enderror" id="tanggal_booking" name="tanggal_booking" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            @error('tanggal_booking')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">Pilih tanggal minimal hari besok</p>
                        </div>
                        
                        <div class="mb-5">
                            <label for="waktu_booking" class="block text-sm font-medium text-gray-700 mb-1">Waktu Booking <span class="text-red-500">*</span></label>
                            <select class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('waktu_booking') border-red-500 @enderror" id="waktu_booking" name="waktu_booking" required>
                                <option value="" selected disabled>Pilih waktu</option>
                                <option value="08:00">08:00</option>
                                <option value="09:00">09:00</option>
                                <option value="10:00">10:00</option>
                                <option value="11:00">11:00</option>
                                <option value="13:00">13:00</option>
                                <option value="14:00">14:00</option>
                                <option value="15:00">15:00</option>
                                <option value="16:00">16:00</option>
                            </select>
                            @error('waktu_booking')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label for="nama_pemesan" class="block text-sm font-medium text-gray-700 mb-1">Nama Pemesan <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_pemesan') border-red-500 @enderror" id="nama_pemesan" name="nama_pemesan" value="{{ auth()->user()->name ?? '' }}" required>
                            @error('nama_pemesan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label for="no_handphone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Handphone <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('no_handphone') border-red-500 @enderror" id="no_handphone" name="no_handphone" value="{{ auth()->user()->phone ?? '' }}" required>
                            @error('no_handphone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-5">
                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('alamat') border-red-500 @enderror" id="alamat" name="alamat" rows="3" required>{{ auth()->user()->address ?? '' }}</textarea>
                            @error('alamat')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-5">
                            <label for="catatan_perbaikan" class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                            <textarea class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('catatan_perbaikan') border-red-500 @enderror" id="catatan_perbaikan" name="catatan_perbaikan" rows="3"></textarea>
                            @error('catatan_perbaikan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">Berikan detail spesifik tentang kebutuhan Anda</p>
                        </div>
                        
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Informasi Pembayaran</h3>
                                    <p class="text-sm text-blue-700 mt-1">Setelah booking dikonfirmasi, Anda akan diminta melakukan pembayaran DP sebesar 50% dari total biaya layanan.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('services.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">Batal</a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 rounded-md text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Buat Booking</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
