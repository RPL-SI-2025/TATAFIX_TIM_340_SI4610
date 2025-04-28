<header class="bg-white shadow-md py-2 px-6 flex justify-between items-center">
    <!-- Logo -->
    <div class="flex items-center space-x-2">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-6 w-6">
        <span class="font-bold text-blue-800 text-lg">SI MAHIR</span>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex space-x-6 text-sm">
        <a href="{{ route('home') }}" class="@if (request()->routeIs('home')) text-orange-500 font-semibold border-b-2 border-orange-500 @else text-gray-700 @endif hover:text-orange-500">Home</a>
        <a href="{{ route('booking') }}" class="@if (request()->routeIs('booking')) text-orange-500 font-semibold border-b-2 border-orange-500 @else text-gray-700 @endif hover:text-orange-500">Booking</a>
        {{-- <a href="{{ route('jadwal') }}" class="text-gray-700 hover:text-orange-500">Jadwal</a>
        <a href="{{ route('chat') }}" class="text-gray-700 hover:text-orange-500">Chat</a>
        <a href="{{ route('faq') }}" class="text-gray-700 hover:text-orange-500">FAQ</a> --}}
    </nav>

    <!-- Login / User Icon -->
    <div>
        @guest
            {{-- <a href="{{ route('login') }}" class="bg-blue-800 hover:bg-blue-900 text-white text-sm px-4 py-1.5 rounded">Login</a>
        @else
            <a href="{{ route('profile') }}">
                <img src="{{ asset('images/user-icon.png') }}" alt="User" class="h-8 w-8 rounded-full hover:scale-105 transition"> --}}
            </a>
        @endguest
    </div>
</header>
