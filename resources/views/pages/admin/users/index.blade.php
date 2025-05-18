@extends('layouts.admin')
@section('title', 'User Management')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">User Management</h1>
    <div class="flex items-center gap-2">
        <form method="get" action="" class="flex gap-2 items-center">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by name..." class="border rounded px-2 py-1" />
            <label for="role" class="font-semibold text-sm">Filter Role:</label>
            <select name="role" id="role" class="border rounded px-2 py-1">
                <option value="" {{ empty($role) ? 'selected' : '' }}>All</option>
                <option value="admin" {{ ($role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="tukang" {{ ($role ?? '') == 'tukang' ? 'selected' : '' }}>Tukang</option>
                <option value="customer" {{ ($role ?? '') == 'customer' ? 'selected' : '' }}>Customer</option>
            </select>
            <label for="status" class="font-semibold text-sm">Status:</label>
            <select name="status" id="status" class="border rounded px-2 py-1">
                <option value="" {{ empty($status) ? 'selected' : '' }}>All</option>
                <option value="active" {{ ($status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ ($status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">Filter</button>
        </form>
        <button onclick="document.getElementById('addUserModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">Add User</button>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

<div class="bg-white rounded shadow p-6">
    <div class="overflow-x-visible">
        <table class="min-w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-50">
                    <th class="py-2 px-4 font-semibold">Name</th>
                    <th class="py-2 px-4 font-semibold">Email</th>
                    <th class="py-2 px-4 font-semibold">Role</th>
                    <th class="py-2 px-4 font-semibold">Status</th>
                    <th class="py-2 px-4 font-semibold">Created</th>
                    <th class="py-2 px-4 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-b">
                    <td class="py-2 px-4">{{ $user->name }}</td>
                    <td class="py-2 px-4">{{ $user->email }}</td>
                    <td class="py-2 px-4">
                        <span class="px-2 py-1 rounded text-xs {{ $user->hasRole('admin') ? 'bg-blue-100 text-blue-700' : ($user->hasRole('tukang') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700') }}">
                            {{ $user->getRoleNames()->first() }}
                        </span>
                    </td>
                    <td class="py-2 px-4">
                        <span class="px-2 py-1 rounded text-xs {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td class="py-2 px-4">{{ $user->created_at->diffForHumans() }}</td>
                    <td class="py-2 px-4 text-center relative">
                        <button onclick="toggleActionMenu({{ $user->id }})" class="p-1 rounded hover:bg-gray-200 focus:outline-none">
                            <span class="material-icons">more_horiz</span>
                        </button>
                        <div id="action-menu-{{ $user->id }}" class="action-menu absolute right-0 mt-2 w-48 bg-white rounded shadow border z-40 hidden">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="flex items-center px-4 py-2 hover:bg-gray-100 text-sm">
                                <span class="material-icons text-base mr-2">edit</span> Edit Profile
                            </a>
                            @if($user->status === 'active')
                                <button type="button" onclick="confirmToggleStatus({{ $user->id }}, 'inactive')" class="flex items-center px-4 py-2 hover:bg-red-50 text-red-600 w-full text-left text-sm">
                                    <span class="material-icons text-base mr-2">block</span> Nonaktifkan User
                                </button>
                            @else
                                <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="flex items-center px-4 py-2 hover:bg-green-50 text-green-600 w-full text-left text-sm">
                                        <span class="material-icons text-base mr-2">check_circle</span> Aktifkan User
                                    </button>
                                </form>
                            @endif
                            @if(!$user->hasRole('admin') || auth()->id() === $user->id)
                                <form method="POST" action="{{ route('admin.users.delete', $user->id) }}" onsubmit="return confirm('Delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center px-4 py-2 text-red-600 hover:bg-red-50 w-full text-left text-sm">
                                        <span class="material-icons text-base mr-2">delete</span> Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Konfirmasi Nonaktifkan User Modal -->
<div id="confirmToggleStatusModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-8 w-full max-w-md relative">
        <button onclick="document.getElementById('confirmToggleStatusModal').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">
            <span class="material-icons">close</span>
        </button>
        <h2 class="text-xl font-bold mb-4">Konfirmasi Nonaktifkan User</h2>
        <p class="mb-6">Apakah Anda yakin ingin menonaktifkan user ini? User tidak akan dapat login ke sistem.</p>
        <form id="toggleStatusForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="flex justify-end gap-4">
                <button type="button" onclick="document.getElementById('confirmToggleStatusModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg text-sm">Batal</button>
                <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-semibold">Nonaktifkan</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleActionMenu(userId) {
    // Hide all open menus first
    document.querySelectorAll('.action-menu').forEach(menu => menu.classList.add('hidden'));
    // Toggle current menu
    const menu = document.getElementById('action-menu-' + userId);
    if(menu) menu.classList.toggle('hidden');
}

// Hide menu when clicking outside
window.addEventListener('click', function(e) {
    document.querySelectorAll('.action-menu').forEach(menu => {
        if (!menu.contains(e.target) && !menu.previousElementSibling.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
});

function confirmToggleStatus(userId, status) {
    const modal = document.getElementById('confirmToggleStatusModal');
    const form = document.getElementById('toggleStatusForm');
    form.action = `/users/${userId}/toggle-status`;
    modal.classList.remove('hidden');
}
</script>

<!-- Add User Modal -->
<div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-8 w-full max-w-md relative">
        <button onclick="document.getElementById('addUserModal').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">
            <span class="material-icons">close</span>
        </button>
        <h2 class="text-xl font-bold mb-4">Add User</h2>
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Name</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Role</label>
                <select name="role" class="w-full border rounded px-3 py-2" required>
                    <option value="admin">Admin</option>
                    <option value="tukang">Tukang</option>
                </select>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">Create</button>
            </div>
        </form>
    </div>
</div>
<!-- Tambahkan filter verified setelah filter status -->
@if($role === 'tukang')
<label for="verified" class="font-semibold text-sm">Verifikasi:</label>
<select name="verified" id="verified" class="border rounded px-2 py-1">
    <option value="" {{ empty($verified) ? 'selected' : '' }}>Semua</option>
    <option value="1" {{ ($verified ?? '') == '1' ? 'selected' : '' }}>Terverifikasi</option>
    <option value="0" {{ ($verified ?? '') == '0' ? 'selected' : '' }}>Belum Terverifikasi</option>
</select>
@endif

<!-- Tambahkan kolom Verifikasi di tabel untuk tukang -->
@if($user->hasRole('tukang'))
<td class="py-2 px-4">
    <span class="px-2 py-1 rounded text-xs {{ $user->is_verified ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
        {{ $user->is_verified ? 'Terverifikasi' : 'Belum Terverifikasi' }}
    </span>
</td>
@endif

<!-- Tambahkan tombol verifikasi di menu aksi untuk tukang yang belum diverifikasi -->
@if($user->hasRole('tukang') && !$user->is_verified)
<form method="POST" action="{{ route('admin.users.verify', $user->id) }}">
    @csrf
    @method('PUT')
    <!-- Ganti tombol verifikasi dengan ini -->
    @if($user->hasRole('tukang') && !$user->is_verified)
    <button type="button" onclick="confirmVerify({{ $user->id }})" class="flex items-center px-4 py-2 hover:bg-green-50 text-green-600 w-full text-left text-sm">
        <span class="material-icons text-base mr-2">verified</span> Verifikasi Tukang
    </button>
    @endif
</form>
@endif
<!-- Tambahkan modal konfirmasi verifikasi di bagian bawah file -->
<div id="confirmVerifyModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-8 w-full max-w-md relative">
        <button onclick="document.getElementById('confirmVerifyModal').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">
            <span class="material-icons">close</span>
        </button>
        <h2 class="text-xl font-bold mb-4">Konfirmasi Verifikasi Tukang</h2>
        <p class="mb-6">Apakah Anda yakin ingin memverifikasi tukang ini? Tukang yang terverifikasi dapat menerima booking dari customer.</p>
        <form id="verifyForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="flex justify-end gap-4">
                <button type="button" onclick="document.getElementById('confirmVerifyModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg text-sm">Batal</button>
                <button type="submit" class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold">Verifikasi</button>
            </div>
        </form>
    </div>
</div>

<!-- Tambahkan script untuk konfirmasi verifikasi -->
<script>
function confirmVerify(userId) {
    const modal = document.getElementById('confirmVerifyModal');
    const form = document.getElementById('verifyForm');
    form.action = `/users/${userId}/verify`;
    modal.classList.remove('hidden');
}
</script>
@endsection
