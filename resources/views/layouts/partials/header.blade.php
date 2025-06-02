<header class="bg-white shadow-md py-2 px-6 flex justify-between items-center sticky top-0 z-10">
    <!-- Logo -->
    <a href="{{ route('home') }}" class="flex items-center space-x-2">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-6 w-6">
        <span class="font-bold text-blue-800 text-lg">TATAFIX</span>
    </a>

    <!-- Navigation Menu -->
    @if (request()->routeIs('login') || request()->routeIs('register.form'))
        <nav class="flex-1"></nav>
    @else
        <nav class="flex space-x-6 text-sm">
            <a href="{{ route('home') }}" class="@if (request()->routeIs('home')) text-orange-500 font-semibold border-b-2 border-orange-500 @else text-gray-700 @endif hover:text-orange-500">Home</a>
            <a href="{{ route('services.index') }}" class="@if (request()->routeIs('services.index')) text-orange-500 font-semibold border-b-2 border-orange-500 @else text-gray-700 @endif hover:text-orange-500">Layanan</a>
            <a href="{{ route('faq') }}" class="@if (request()->routeIs('faq')) text-orange-500 font-semibold border-b-2 border-orange-500 @else text-gray-700 @endif hover:text-orange-500">FAQ</a>
            <a href="{{ route('chatify') }}" class="@if (request()->routeIs('chatify')) text-orange-500 font-semibold border-b-2 border-orange-500 @else text-gray-700 @endif hover:text-orange-500">Chat</a>
            @auth
                <a href="{{ route('booking.history') }}" class="@if (request()->routeIs('booking.history')) text-orange-500 font-semibold border-b-2 border-orange-500 @else text-gray-700 @endif hover:text-orange-500">Riwayat Pemesanan</a>
                <a href="{{ route('customer.complaints.create') }}" class="@if (request()->routeIs('customer.complaints.*')) text-orange-500 font-semibold border-b-2 border-orange-500 @else text-gray-700 @endif hover:text-orange-500">Pengaduan</a>
                @if(Auth::user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}" class="@if (request()->routeIs('admin.dashboard')) text-orange-500 font-semibold border-b-2 border-orange-500 @else text-gray-700 @endif hover:text-orange-500">Dashboard Admin</a>
                @endif
                @if(Auth::user()->hasRole('tukang'))
                    <a href="{{ route('tukang.bookings.index') }}" class="@if (request()->routeIs('tukang.bookings.*')) text-orange-500 font-semibold border-b-2 border-orange-500 @else text-gray-700 @endif hover:text-orange-500">Penugasan Saya</a>
                @endif
            @endauth
        </nav>
    @endif

    <!-- Login / User Icon -->
    <div>
        @if (request()->routeIs('login') || request()->routeIs('register.form'))
            <div class="flex space-x-2">
                <a href="{{ route('login') }}" class="bg-blue-800 hover:bg-blue-900 text-white text-sm px-4 py-1.5 rounded">Login</a>
                <a href="{{ route('register.form') }}" class="bg-orange-500 hover:bg-orange-600 text-white text-sm px-4 py-1.5 rounded">Register</a>
            </div>
        @else
            @guest
                <div class="flex space-x-2">
                    <a href="{{ route('login') }}" class="bg-blue-800 hover:bg-blue-900 text-white text-sm px-4 py-1.5 rounded">Login</a>
                    <a href="{{ route('register.form') }}" class="bg-orange-500 hover:bg-orange-600 text-white text-sm px-4 py-1.5 rounded">Register</a>
                </div>
            @else
                <div class="flex items-center space-x-4">
                    <!-- Notification Bell -->
                    <div class="relative" id="notification-bell">
                        <button class="text-gray-600 hover:text-orange-500 focus:outline-none" id="notification-button">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span id="notification-badge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">0</span>
                        </button>
                        <!-- Notification Dropdown -->
                        <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50 max-h-96 overflow-y-auto">
                            <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                                <h3 class="text-sm font-semibold text-gray-700">Notifikasi</h3>
                                <button id="mark-all-read" class="text-xs text-blue-600 hover:text-blue-800">Tandai semua dibaca</button>
                            </div>
                            <div id="notification-list" class="divide-y divide-gray-100">
                                <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                    Memuat notifikasi...
                                </div>
                            </div>
                            <div class="px-4 py-2 border-t border-gray-100">
                                <a href="{{ route('notifications.index') }}" class="text-xs text-blue-600 hover:text-blue-800 block text-center">Lihat semua notifikasi</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Profile Picture -->
                    <a href="{{ route('profile') }}" class="flex items-center">
                        <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-600 font-medium text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    </a>
                </div>
            @endguest
        @endif
    </div>
</header>
