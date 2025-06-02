@extends('layouts.admin')

@section('title', 'Edit Layanan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Edit Layanan</h1>
            <nav class="mt-1">
                <ol class="flex text-sm">
                    <li class="text-gray-500 hover:text-gray-700"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="mx-2 text-gray-400">/</li>
                    <li class="text-gray-500 hover:text-gray-700"><a href="{{ route('admin.services.index') }}">Layanan</a></li>
                    <li class="mx-2 text-gray-400">/</li>
                    <li class="text-gray-700 font-medium">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.services.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
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

        <form action="{{ route('admin.services.update', $service->service_id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="col-span-2">
                    <label for="title_service" class="block text-sm font-medium text-gray-700 mb-1">Judul Layanan <span class="text-red-500">*</span></label>
                    <input type="text" name="title_service" id="title_service" value="{{ old('title_service', $service->title_service) }}" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}" {{ old('category_id', $service->category_id) == $category->category_id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-4">
                    <div class="flex-1">
                        <label for="base_price" class="block text-sm font-medium text-gray-700 mb-1">Harga Dasar <span class="text-red-500">*</span></label>
                        <input type="number" name="base_price" id="base_price" value="{{ old('base_price', $service->base_price) }}" min="0" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex-1">
                        <label for="label_unit" class="block text-sm font-medium text-gray-700 mb-1">Satuan <span class="text-red-500">*</span></label>
                        <input type="text" name="label_unit" id="label_unit" value="{{ old('label_unit', $service->label_unit) }}" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="5" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $service->description) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Berikan deskripsi yang jelas tentang layanan ini</p>
                </div>

                <div>
                    <label for="availbility" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <div class="flex gap-4 mt-1">
                        <label class="inline-flex items-center bg-green-50 px-3 py-2 rounded border border-green-200 hover:bg-green-100 cursor-pointer">
                            <input type="radio" name="availbility" value="1" {{ old('availbility', $service->availbility) ? 'checked' : '' }} class="form-radio h-5 w-5 text-green-600">
                            <span class="ml-2 text-gray-700">Aktif</span>
                        </label>
                        <label class="inline-flex items-center bg-red-50 px-3 py-2 rounded border border-red-200 hover:bg-red-100 cursor-pointer">
                            <input type="radio" name="availbility" value="0" {{ old('availbility', $service->availbility) ? '' : 'checked' }} class="form-radio h-5 w-5 text-red-600">
                            <span class="ml-2 text-gray-700">Tidak Aktif</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Gambar Layanan</label>
                    <div class="mt-1 flex items-center">
                        @if($service->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title_service }}" class="h-24 w-auto object-cover rounded">
                                <p class="text-xs text-gray-500 mt-1">Gambar saat ini</p>
                            </div>
                        @endif
                    </div>
                    <div class="mt-2">
                        <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Format yang didukung: JPG, JPEG, PNG. Maksimal 2MB.</p>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('admin.services.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection