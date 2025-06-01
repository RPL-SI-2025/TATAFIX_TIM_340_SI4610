@extends('layouts.admin')
@section('title', 'Edit User')
@section('content')
<div class="max-w-xl mx-auto bg-white rounded shadow p-8">
    <h2 class="text-2xl font-bold mb-6">Edit User</h2>
    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    
    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="flex items-center gap-6 mb-8">
            <div>
                <img id="previewFoto" src="{{ $user->photo ? asset('storage/'.$user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=ddd&color=555' }}" class="h-24 w-24 rounded-full object-cover border" alt="Foto User">
            </div>
            <div class="flex flex-col gap-2">
                <label class="block">
                    <span class="sr-only">Change photo</span>
                    <button type="button" onclick="document.getElementById('photoInput').click()" class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded font-semibold">Ganti Foto</button>
                    <input type="file" id="photoInput" name="photo" class="hidden" onchange="window.previewFoto(event)">
                </label>
            </div>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Domisili</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}" class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">No. Handphone</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2" required>
                <option value="active" {{ (old('status', $user->status) === 'active') ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ (old('status', $user->status) === 'inactive') ? 'selected' : '' }}>Inactive</option>
            </select>
            <!-- Tambahkan field verifikasi setelah field status -->
            @if($user->hasRole('tukang'))
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Status Verifikasi</label>
                <select name="is_verified" class="w-full border rounded px-3 py-2" required>
                    <option value="1" {{ (old('is_verified', $user->is_verified) == 1) ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="0" {{ (old('is_verified', $user->is_verified) == 0) ? 'selected' : '' }}>Belum Terverifikasi</option>
                </select>
                <p class="text-sm text-gray-600 mt-1">Tukang yang terverifikasi dapat menerima booking dari customer.</p>
            </div>
            @endif
            @if($user->hasRole('admin') && $user->id != auth()->id())
                <p class="text-sm text-red-600 mt-1">Perhatian: Anda tidak dapat menonaktifkan user dengan peran admin lainnya.</p>
            @endif
        </div>
        <div class="flex justify-end gap-4 pt-6">
            <a href="{{ route('admin.users') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg text-sm">Batal</a>
            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold">Update</button>
        </div>
    </form>
    <form id="deletePhotoForm" method="POST" action="{{ route('admin.users.update', $user->id) }}" onsubmit="return confirm('Hapus foto profil ini?')" class="mt-2">
        @csrf
        @method('PUT')
        <input type="hidden" name="delete_photo" value="1">
        <button type="submit" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded text-red-600 hover:bg-red-50">
            <span class="material-icons text-base">delete</span> Hapus Foto
        </button>
    </form>
</div>
@endsection
<script>
window.previewFoto = function(event) {
    const input = event.target;
    const preview = document.getElementById('previewFoto');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
