@extends('layouts.app')

@section('content')
<div class="bg-[#F6F0F0] py-12 min-h-screen">
    <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg px-10 py-8">
        <h2 class="text-2xl font-bold text-center text-[#015D8F] mb-8">Laporan Pengaduan</h2>

        @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4 text-center">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('customer.complaints.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Title --}}
            <div>
                <label for="title" class="block mb-2 font-semibold text-gray-700">Judul Pengaduan</label>
                <input type="text" name="title" id="title" placeholder="Masukkan judul pengaduan"
                    value="{{ old('title') }}"
                    class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#015D8F]" required>
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block mb-2 font-semibold text-gray-700">Deskripsi</label>
                <textarea name="description" id="description" rows="5" placeholder="Jelaskan pengaduan Anda secara detail"
                    class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#015D8F]" required>{{ old('description') }}</textarea>
            </div>

            {{-- Attachment --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700">Upload Bukti Pengaduan</label>
                <label for="attachment"
                    class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-400 text-center text-gray-600 rounded-md cursor-pointer hover:border-[#015D8F] transition-all duration-200">
                    <span>Masukkan file di sini</span>
                    <p class="text-sm italic mt-1">Format yang didukung: JPG dan PNG (Maks. 5MB)</p>
                    <span class="mt-1 text-sm font-semibold text-gray-500" id="file-name">Tidak ada file yang dipilih</span>
                    <input type="file" name="attachment" id="attachment" class="hidden"
                        accept=".jpg,.jpeg,.png"
                        onchange="document.getElementById('file-name').innerText = this.files[0]?.name || 'Tidak ada file yang dipilih'" required>
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full bg-[#015D8F] hover:bg-[#01466B] text-white font-semibold py-3 rounded-md shadow-md transition duration-200">
                Upload Pengaduan
            </button>
        </form>

        <footer class="text-center text-sm text-gray-500 mt-8">
        </footer>
    </div>
</div>
@endsection