@extends('layouts.admin')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        Manajemen Kategori
    </h2>

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

    <!-- Search & Filter -->
    <div class="mb-6 p-6 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="w-full md:w-3/4">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Kategori</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                    placeholder="Cari berdasarkan nama atau deskripsi...">
            </div>
            <div class="w-full md:w-1/4 flex items-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md w-full md:w-auto">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>
                <a href="{{ route('admin.categories.index') }}" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md">
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Actions -->
    <div class="mb-6">
        <a href="{{ route('admin.categories.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
            <i class="fas fa-plus mr-2"></i> Tambah Kategori Baru
        </a>
    </div>

    <!-- Table -->
    <div class="w-full overflow-hidden rounded-lg shadow-md">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-100 border-b">
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Nama Kategori</th>
                        <th class="px-4 py-3">Deskripsi</th>
                        <th class="px-4 py-3">Tanggal Dibuat</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($categories as $category)
                    <tr class="text-gray-700 hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">
                            {{ $category->category_id }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div>
                                    <p class="font-semibold">{{ $category->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ Str::limit($category->description, 50) ?? 'Tidak ada deskripsi' }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $category->created_at->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.categories.edit', $category->category_id) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded-md">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->category_id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded-md"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                            Tidak ada data kategori
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="px-4 py-3 bg-white border-t">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection