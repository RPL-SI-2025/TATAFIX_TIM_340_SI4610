@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.services.index') }}" class="text-blue-500 hover:text-blue-700 mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold">Tambah Layanan Baru</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">Terjadi kesalahan:</p>
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label for="title_service" class="block text-sm font-medium text-gray-700 mb-1">Judul Layanan <span class="text-red-500">*</span></label>
                    <input type="text" name="title_service" id="title_service" value="{{ old('title_service') }}" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}" {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-4">
                    <div class="flex-1">
                        <label for="base_price" class="block text-sm font-medium text-gray-700 mb-1">Harga Dasar <span class="text-red-500">*</span></label>
                        <input type="number" name="base_price" id="base_price" value="{{ old('base_price') }}" min="0" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex-1">
                        <label for="label_unit" class="block text-sm font-medium text-gray-700 mb-1">Satuan <span class="text-red-500">*</span></label>
                        <input type="text" name="label_unit" id="label_unit" value="{{ old('label_unit') }}" required placeholder="contoh: jam, hari, unit"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="5" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>

                <div class="col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Gambar Layanan</label>
                    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('admin.services.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                    Batal
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Simpan Layanan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection