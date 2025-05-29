@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.services.index') }}" class="text-blue-500 hover:text-blue-700 mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold">Edit Layanan</h1>
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

        <form action="{{ route('admin.services.update', $service->service_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                </div>

                <div>
                    <label for="availbility" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <div class="flex gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="availbility" value="1" {{ old('availbility', $service->availbility) ? 'checked' : '' }} class="form-radio h-5 w-5 text-blue-600">
                            <span class="ml-2 text-gray-700">Aktif</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="availbility" value="0" {{ old('availbility', $service->availbility) ? '' : 'checked' }} class="form-radio h-5 w-5 text-red-600">
                            <span class="ml-2 text-gray-700">Tidak Aktif</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Gambar Layanan</label>
                    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2