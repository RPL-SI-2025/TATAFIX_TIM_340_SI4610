@extends('Layout.admin')
@section('title', 'Admin Dashboard')
@section('content')
<!-- Statistic Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded shadow text-center">
        <div class="text-gray-500">Total Users</div>
        <div class="text-3xl font-bold">{{ $totalUsers }}</div>
    </div>
    <div class="bg-white p-6 rounded shadow text-center">
        <div class="text-gray-500">Services</div>
        <div class="text-3xl font-bold">{{ $totalServices }}</div>
    </div>
    <div class="bg-white p-6 rounded shadow text-center">
        <div class="text-gray-500">Categories</div>
        <div class="text-3xl font-bold">{{ $totalCategories }}</div>
    </div>
    <div class="bg-white p-6 rounded shadow text-center">
        <div class="text-gray-500">Completed Jobs</div>
        <div class="text-3xl font-bold">{{ $completedJobs }}</div>
    </div>
</div>
<!-- Recent Users Table -->
<div class="bg-white rounded shadow p-6 mb-8">
    <h2 class="text-lg font-semibold mb-4">Recent Users</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left">
            <thead>
                <tr>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Role</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Added</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentUsers as $user)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">
                        @foreach($user->roles as $role)
                            <span class="inline-block bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs mr-1">{{ ucfirst($role->name) }}</span>
                        @endforeach
                    </td>
                    <td class="px-4 py-2">
                        <span class="inline-block bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Active</span>
                    </td>
                    <td class="px-4 py-2">{{ $user->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- Quick Tips -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-blue-50 p-4 rounded">
        <div class="font-semibold mb-2">Quick Tips</div>
        <ul class="list-disc ml-5 text-sm text-gray-700">
            <li>Use the sidebar to navigate between different sections.</li>
            <li>Add new user from the Users page.</li>
            <li>Create service categories before adding services.</li>
            <li>You can edit/manage profiles for detailed information.</li>
        </ul>
    </div>

</div>
@endsection
