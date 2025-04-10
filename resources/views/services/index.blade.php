<form method="GET" action="{{ route('services.index') }}">
    <input type="text" name="search" placeholder="Cari layanan..." value="{{ request('search') }}">
    <select name="category_id">
        <option value="">Semua Kategori</option>
        @foreach ($categories as $category)
            <option value="{{ $category->category_id }}" {{ request('category_id') == $category->category_id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    <input type="number" name="min_price" placeholder="Harga min" value="{{ request('min_price') }}">
    <input type="number" name="max_price" placeholder="Harga max" value="{{ request('max_price') }}">
    <input type="number" step="0.1" name="rating" placeholder="Rating minimal" value="{{ request('rating') }}">
    <button type="submit">Filter</button>
</form>

<div class="grid">
    @foreach ($services as $service)
        <div class="card">
            <h3>{{ $service->title_service }}</h3>
            <p>{{ $service->category->name }} - {{ $service->base_price }} / {{ $service->label_unit }}</p>
            <p>Rating: {{ $service->rating_avg ?? 'Belum ada' }}</p>
            <p>{{ Str::limit($service->description, 100) }}</p>
        </div>
    @endforeach
</div>

{{ $services->links() }}
