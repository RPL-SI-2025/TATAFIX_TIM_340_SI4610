@extends('Layout.admin')
@section('title', 'User Management')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">User Management</h1>
    <div class="flex items-center gap-2">
        <form method="get" action="" class="flex gap-2 items-center">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by name..." class="border rounded px-2 py-1" />
            <label for="role" class="font-semibold text-sm">Filter Role:</label>
            <select name="role" id="role" onchange="this.form.submit()" class="border rounded px-2 py-1">
                <option value="" {{ empty($role) ? 'selected' : '' }}>All</option>
                <option value="admin" {{ ($role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="tukang" {{ ($role ?? '') == 'tukang' ? 'selected' : '' }}>Tukang</option>
            </select>
            <button type="submit" class="hidden">Cari</button>
        </form>
        <button onclick="document.getElementById('addUserModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">Add User</button>
    </div>
</div>
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
                        <span class="px-2 py-1 rounded text-xs {{ $user->hasRole('admin') ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                            {{ $user->getRoleNames()->first() }}
                        </span>
                    </td>
                    <td class="py-2 px-4">
                        <span class="text-xs px-2 py-1 rounded {{ $user->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-500' }}">
                            {{ ucfirst($user->status ?? 'active') }}
                        </span>
                    </td>
                    <td class="py-2 px-4">{{ $user->created_at->diffForHumans() }}</td>
                    <td class="py-2 px-4 text-center relative">
                        <button onclick="toggleActionMenu({{ $user->id }})" class="p-1 rounded hover:bg-gray-200 focus:outline-none">
                            <span class="material-icons">more_horiz</span>
                        </button>
                        <div id="action-menu-{{ $user->id }}" class="action-menu absolute right-0 mt-2 w-36 bg-white rounded shadow border z-40 hidden">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="flex items-center px-4 py-2 hover:bg-gray-100 text-sm">
                                <span class="material-icons text-base mr-2">edit</span> Edit Profile
                            </a>
                            <form method="POST" action="{{ route('admin.users.delete', $user->id) }}" onsubmit="return confirm('Delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center px-4 py-2 text-red-600 hover:bg-red-50 w-full text-left text-sm">
                                    <span class="material-icons text-base mr-2">delete</span> Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
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
@endsection
