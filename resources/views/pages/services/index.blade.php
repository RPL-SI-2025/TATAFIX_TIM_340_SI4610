@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Layanan Perbaikan</h1>
        <p class="text-gray-600 mb-6">Pilih jasa perbaikan yang Anda butuhkan untuk memperbaiki properti rumah Anda</p>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" action="{{ route('services.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search Input -->
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               placeholder="Cari layanan..." 
                               value="{{ request('search') }}"
                               class="w-full pl-8 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                            <svg class="h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <select name="category_id" 
                                class="w-full py-2 px-3 text-sm border border-gray-300 bg-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->category_id }}" 
                                        {{ request('category_id') == $category->category_id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="flex space-x-2">
                        <input type="number" 
                               name="min_price" 
                               placeholder="Harga min" 
                               value="{{ request('min_price') }}"
                               class="w-1/2 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <input type="number" 
                               name="max_price" 
                               placeholder="Harga max" 
                               value="{{ request('max_price') }}"
                               class="w-1/2 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Rating Filter -->
                    <div class="flex items-center">
                        <select name="rating" 
                                class="w-full py-2 px-3 text-sm border border-gray-300 bg-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Rating Minimal</option>
                            @foreach(range(5, 1) as $rating)
                                <option value="{{ $rating }}" {{ request('rating') == $rating ? 'selected' : '' }}>
                                    {{ $rating }}⭐ ke atas
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Filter Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Filter Layanan
                    </button>
                </div>
            </form>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($services as $service)
                <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-md transition-shadow duration-300">
                    @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" 
                             alt="{{ $service->title_service }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded">
                                {{ $service->category->name }}
                            </span>
                            <div class="flex items-center">
                                <svg class="h-3.5 w-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="ml-1 text-xs text-gray-600">{{ $service->rating_avg ?? 'Belum ada' }}</span>
                            </div>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 mb-1">{{ $service->title_service }}</h3>
                        <p class="text-sm text-gray-500 mb-3">{{ Str::limit($service->description, 100) }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-base font-bold text-blue-600">
                                Rp {{ number_format($service->base_price, 0, ',', '.') }}
                                <span class="text-xs font-normal text-gray-500">/ {{ $service->label_unit }}</span>
                            </span>
                            @auth
                                @if($service->availbility)
                                    <a href="{{ route('booking.create', $service) }}" 
                                        class="bg-blue-600 text-white px-3 py-1.5 text-sm rounded hover:bg-blue-700 transition-colors duration-300">
                                        Booking
                                    </a>
                                @else
                                    <button disabled 
                                        class="bg-gray-400 text-white px-3 py-1.5 text-sm rounded cursor-not-allowed">
                                        Tidak Tersedia
                                    </button>
                                @endif
                            @elseif(!auth()->check())
                                <button onclick="openLoginModal()" 
                                    class="bg-blue-600 text-white px-3 py-1.5 text-sm rounded hover:bg-blue-700 transition-colors duration-300">
                                    Booking
                                </button>
                            @elseif(!auth()->user()->hasVerifiedEmail())
                                <button onclick="openVerificationModal()" 
                                    class="bg-blue-600 text-white px-3 py-1.5 text-sm rounded hover:bg-blue-700 transition-colors duration-300">
                                    Booking
                                </button>
                            @else
                                <button disabled 
                                    class="bg-gray-400 text-white px-3 py-1.5 text-sm rounded cursor-not-allowed">
                                    Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada layanan ditemukan</h3>
                    <p class="mt-1 text-sm text-gray-500">Coba ubah filter pencarian Anda.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $services->links() }}
        </div>
    </div>
</div>

<!-- Login Notification Modal -->
<div id="loginNotificationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h2 class="text-xl font-bold text-gray-900 mt-4 mb-2">Login Diperlukan</h2>
            <p class="text-gray-600 mb-6">Anda perlu login terlebih dahulu untuk melakukan booking layanan ini.</p>
            <div class="flex justify-center space-x-3">
                <button type="button" onclick="closeLoginModal()" 
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Batal
                </button>
                <a href="{{ route('login') }}?redirect=services" 
                   class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Login Sekarang
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Email Verification Notification Modal -->
<div id="verificationNotificationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <h2 class="text-xl font-bold text-gray-900 mt-4 mb-2">Verifikasi Email Diperlukan</h2>
            <p class="text-gray-600 mb-6">Anda perlu memverifikasi email terlebih dahulu untuk melakukan booking layanan ini.</p>
            <div class="flex justify-center space-x-3">
                <button type="button" onclick="closeVerificationModal()" 
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Tutup
                </button>
                <a href="{{ route('verification.notice') }}" 
                   class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Verifikasi Email
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openLoginModal() {
    document.getElementById('loginNotificationModal').classList.remove('hidden');
    document.getElementById('loginNotificationModal').classList.add('flex');
}

function closeLoginModal() {
    document.getElementById('loginNotificationModal').classList.add('hidden');
    document.getElementById('loginNotificationModal').classList.remove('flex');
}

function openVerificationModal() {
    document.getElementById('verificationNotificationModal').classList.remove('hidden');
    document.getElementById('verificationNotificationModal').classList.add('flex');
}

function closeVerificationModal() {
    document.getElementById('verificationNotificationModal').classList.add('hidden');
    document.getElementById('verificationNotificationModal').classList.remove('flex');
}
</script>
@endpush