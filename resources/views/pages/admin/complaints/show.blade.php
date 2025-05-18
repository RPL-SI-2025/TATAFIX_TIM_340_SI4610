@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Detail Pengaduan #{{ $complaint->id }}</h1>
        <a href="{{ route('admin.complaints.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors">
            Kembali
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Informasi Pengaduan -->
        <div class="md:col-span-2 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">{{ $complaint->title }}</h2>

            <div class="mb-6">
                <div class="flex items-center mb-2">
                    <span class="text-gray-600 font-medium mr-2">Status:</span>
                    @if($complaint->status == 'menunggu_validasi')
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Menunggu Validasi
                    </span>
                    @elseif($complaint->status == 'valid')
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        Valid
                    </span>
                    @else
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                        Tidak Valid
                    </span>
                    @endif
                </div>

                <div class="flex items-center mb-2">
                    <span class="text-gray-600 font-medium mr-2">Tanggal Pengaduan:</span>
                    <span>{{ $complaint->created_at->format('d M Y, H:i') }}</span>
                </div>

                <div class="flex items-center mb-2">
                    <span class="text-gray-600 font-medium mr-2">Customer:</span>
                    <span>{{ $complaint->user->name }} ({{ $complaint->user->email }})</span>
                </div>

                @if($complaint->validated_at)
                <div class="flex items-center mb-2">
                    <span class="text-gray-600 font-medium mr-2">Divalidasi Oleh:</span>
                    <span>{{ $complaint->validator->name }} pada {{ $complaint->validated_at->format('d M Y, H:i') }}</span>
                </div>
                @endif
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-medium mb-2">Deskripsi Pengaduan</h3>
                <div class="bg-gray-50 p-4 rounded-md">
                    {{ $complaint->description }}
                </div>
            </div>

            @if($complaint->evidence_file)
            <div class="mb-6">
                <h3 class="text-lg font-medium mb-2">Bukti Pengaduan</h3>
                <div id="evidance_file" class="border rounded-md p-2">
                    <img src="{{ asset('storage/' . $complaint->evidence_file) }}" alt="Bukti Pengaduan" class="max-w-full h-auto rounded">
                    <button onclick="openFullscreen();">Open Fullscreen</button>
                </div>
            </div>
            @endif

            @if($complaint->admin_notes)
            <div class="mb-6">
                <h3 class="text-lg font-medium mb-2">Catatan Admin</h3>
                <div class="bg-gray-50 p-4 rounded-md">
                    {{ $complaint->admin_notes }}
                </div>
            </div>
            @endif
        </div>

        <!-- Form Validasi -->
        <div class="bg-white rounded-lg shadow-md p-6">
            @if($complaint->status == 'menunggu_validasi')
            <h2 class="text-xl font-semibold mb-4">Validasi Pengaduan</h2>
            <form action="{{ route('admin.complaints.validate', $complaint->id) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Validasi</label>
                    <div class="mt-1">
                        <div class="flex items-center mb-2">
                            <input type="radio" id="valid" name="status" value="valid" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" required>
                            <label for="valid" class="ml-2 block text-sm text-gray-900">Valid</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="tidak_valid" name="status" value="tidak_valid" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" required>
                            <label for="tidak_valid" class="ml-2 block text-sm text-gray-900">Tidak Valid</label>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Admin</label>
                    <textarea id="admin_notes" name="admin_notes" rows="5" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Berikan catatan tentang validasi pengaduan ini..."></textarea>
                    <p class="mt-1 text-sm text-gray-500">Minimal 10 karakter</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Simpan Validasi
                    </button>
                </div>
            </form>
            @else
            <div class="bg-gray-50 p-4 rounded-md text-center">
                <p class="text-gray-700">Pengaduan ini sudah divalidasi.</p>
                <p class="text-gray-500 text-sm mt-2">Status:
                    @if($complaint->status == 'valid')
                    <span class="font-semibold text-green-600">Valid</span>
                    @else
                    <span class="font-semibold text-red-600">Tidak Valid</span>
                    @endif
                </p>
            </div>
            @endif
        </div>
    </div>
</div>
<script>
    var elem = document.getElementById("evidance_file");

    function openFullscreen() {
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) {
            /* Safari */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) {
            /* IE11 */
            elem.msRequestFullscreen();
        }
    }

    function closeFullscreen() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            /* Safari */
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            /* IE11 */
            document.msExitFullscreen();
        }
    }
</script>
@endsection