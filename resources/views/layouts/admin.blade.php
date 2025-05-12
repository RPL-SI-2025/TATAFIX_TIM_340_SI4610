<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TATAFIX')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar (full height, fixed left) -->
        <aside class="w-64 bg-gray-900 text-gray-100 flex flex-col min-h-screen fixed left-0 top-0 bottom-0 z-30">
            <div class="p-6 font-bold text-xl border-b border-gray-800">TataFix</div>
            <!-- Profile section -->
            <div class="flex items-center p-4 border-b border-gray-800">
                <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-xl font-bold text-white mr-3">
                    <span class="material-icons">account_circle</span>
                </div>
                <div>
                    <div class="font-semibold text-sm">{{ Auth::user()->name ?? 'Admin User' }}</div>
                    <div class="text-xs text-gray-400">{{ Auth::user()->email ?? '' }}</div>
                </div>
            </div>
            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li><a href="{{ url('/') }}" class="flex items-center p-2 rounded hover:bg-gray-800"><span class="material-icons mr-2">home</span> Home</a></li>
                    <li><a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 rounded hover:bg-gray-800"><span class="material-icons mr-2">dashboard</span> Dashboard</a></li>
                    <li><a href="{{ route('admin.users') }}" class="flex items-center p-2 rounded hover:bg-gray-800"><span class="material-icons mr-2">people</span> Users</a></li>
                    <li><a href="{{ route('admin.status-booking') }}" class="flex items-center p-2 rounded hover:bg-gray-800"><span class="material-icons mr-2">calendar_today</span> Booking Status</a></li>
                    <li><a href="#" class="flex items-center p-2 rounded hover:bg-gray-800"><span class="material-icons mr-2">category</span> Categories</a></li>
                    <li><a href="#" class="flex items-center p-2 rounded hover:bg-gray-800"><span class="material-icons mr-2">build</span> Services</a></li>
                    <li><a href="{{ route('admin.complaints.index') }}" class="flex items-center p-2 rounded hover:bg-gray-800"><span class="material-icons mr-2">report_problem</span> Pengaduan</a></li>
                </ul>
            </nav>
            <div class="p-4 text-xs text-gray-400">Admin Dashboard v1.0</div>
        </aside>
        <!-- Main area (margin-left for sidebar) -->
        <div class="flex-1 ml-64 flex flex-col min-h-screen">
            <!-- Header -->
            <header class="flex items-center justify-end bg-white border-b border-gray-200 px-6 py-3 h-16 sticky top-0 z-20">
                <div class="flex items-center gap-3">
                    <span class="material-icons text-gray-400">notifications_none</span>
                    <span class="rounded-full bg-gray-200 w-8 h-8 flex items-center justify-center font-bold text-blue-900 uppercase">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</span>
                    <span class="ml-1 text-sm text-gray-700 font-semibold">{{ Auth::user()->name ?? 'Admin User' }}</span>
                </div>
            </header>
            <!-- Main Content -->
            <main class="flex-1 p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
