@extends('Layout.app')

@section('content')
<script>
    // Kontrol panel filter
    document.addEventListener('DOMContentLoaded', function() {
        const filterButton = document.getElementById('filter-button');
        const closeFilter = document.getElementById('close-filter');
        const filterPanel = document.getElementById('filter-panel');
        const resetFilter = document.getElementById('reset-filter');

        // Buka panel filter
        filterButton.addEventListener('click', (e) => {
            e.preventDefault();
            filterPanel.classList.remove('hidden');
        });

        // Tutup panel filter
        closeFilter.addEventListener('click', (e) => {
            e.preventDefault();
            filterPanel.classList.add('hidden');
        });

        // Reset filter
        resetFilter.addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelectorAll('select, input[type="number"]').forEach(input => {
                input.value = '';
            });
            filterPanel.classList.add('hidden');
        });

        // Tutup panel saat klik di luar
        document.addEventListener('click', (e) => {
            if (!filterPanel.contains(e.target) && !filterButton.contains(e.target)) {
                filterPanel.classList.add('hidden');
            }
        });

        // Cari layanan secara real-time
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                const cards = document.querySelectorAll('.service-card');
                
                cards.forEach(card => {
                    const title = card.querySelector('.service-title');
                    const description = card.querySelector('.service-description');
                    
                    if (title && description) {
                        const titleText = title.textContent.toLowerCase();
                        const descriptionText = description.textContent.toLowerCase();
                        
                        if (titleText.includes(query) || descriptionText.includes(query)) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });
            });
        }
    });
</script>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Layanan Tersedia</h1>
        <p class="mt-2 text-sm text-gray-500">Pilih layanan yang Anda butuhkan</p>
    </div>

    <div class="mb-8">
        <form action="{{ route('booking.index') }}" method="GET" class="flex items-center space-x-4">
            <!-- Search Input -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                    placeholder="Cari layanan...">
            </div>

            <!-- Filter Button -->
            <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="filter-button">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                </svg>
                Filter
            </button>

            <!-- Filter Panel -->
            <div class="fixed inset-0 z-50 hidden" id="filter-panel">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
                <div class="fixed inset-y-0 right-0 max-w-md w-full bg-white shadow-xl overflow-y-auto">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Filter</h3>
                            <button type="button" class="text-gray-400 hover:text-gray-500" id="close-filter">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form action="{{ route('booking.index') }}" method="GET" class="space-y-6">
                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                                <select name="category_id" class="w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price Range -->
                            <div class="space-y-4">
                                <div>
                                    <label for="min_price" class="block text-sm font-medium text-gray-700 mb-2">Harga Minimum (Rp)</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="number" 
                                            name="min_price" 
                                            value="{{ request('min_price') }}"
                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="0">
                                    </div>
                                </div>
                                <div>
                                    <label for="max_price" class="block text-sm font-medium text-gray-700 mb-2">Harga Maksimum (Rp)</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="number" 
                                            name="max_price" 
                                            value="{{ request('max_price') }}"
                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="0">
                                    </div>
                                </div>
                            </div>

                            <!-- Rating -->
                            <div>
                                <label for="min_rating" class="block text-sm font-medium text-gray-700 mb-2">Minimal Rating</label>
                                <select name="min_rating" class="w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Semua Rating</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ request('min_rating') == $i ? 'selected' : '' }}>
                                            {{ $i }}⭐
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="flex justify-end space-x-4">
                                <button type="button" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="reset-filter">
                                    Reset
                                </button>
                                <button type="submit" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Terapkan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="servicesGrid">
        @foreach($services as $service)
        <div class="service-card bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
            @if($service->image_url)
            <div class="h-48 bg-cover bg-center" style="background-image: url('{{ $service->image_url }}')">
            </div>
            @endif
            <div class="p-6">
                <h2 class="service-title text-xl font-semibold text-gray-900 mb-2">{{ $service->title_service }}</h2>
                <p class="service-description text-gray-600 mb-4">{{ $service->description }}</p>
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-lg font-bold">Rp {{ number_format($service->base_price) }}</span>
                        <span class="text-gray-500 text-xs">/ {{ $service->label_unit }}</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="ml-1 text-gray-500 text-sm">{{ $service->rating_avg }}</span>
                    </div>
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <span class="text-xs text-gray-500">{{ $service->category->name }}</span>
                    <a href="{{ route('booking.create', $service->service_id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-300">Pesan</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchBox = document.getElementById('searchInput');
        if (searchBox) {
            searchBox.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                const cards = document.querySelectorAll('#servicesGrid > div');
                
                cards.forEach(card => {
                    const title = card.querySelector('h2').textContent.toLowerCase();
                    const description = card.querySelector('p').textContent.toLowerCase();
                    
                    if (title.includes(query) || description.includes(query)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    });
@endsection
