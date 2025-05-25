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
            <a href="{{ route('booking.index') }}" class="@if (request()->routeIs('booking')) text-orange-500 font-semibold border-b-2 border-orange-500 @else text-gray-700 @endif hover:text-orange-500">Booking</a>
            <a href="{{ route('faq') }}" class="@if (request()->routeIs('faq')) text-orange-500 font-semibold border-b-2 border-orange-500 @else text-gray-700 @endif hover:text-orange-500">FAQ</a>
            <a href="{{ route('chatify') }}" class="@if (request()->routeIs('chatify')) text-orange-500 font-semibold border-b-2 border-orange-500 @else text-gray-700 @endif hover:text-orange-500">Chat</a>
            @auth
                @if(Auth::user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}" class="@if (request()->routeIs('admin.dashboard')) text-orange-500 font-semibold border-b-2 border-orange-500 @else text-gray-700 @endif hover:text-orange-500">Dashboard Admin</a>
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
                <a href="{{ route('profile') }}" class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-600 font-medium text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                </a>
            @endguest
        @endif
    </div>
</header>
