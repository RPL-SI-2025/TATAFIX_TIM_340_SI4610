<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TATAFIX')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Control loading spinner size */
        .spinner {
            width: 40px !important;
            height: 40px !important;
        }
        
        /* Override any global loading indicators */
        img[src*="loading"],
        img[src*="spinner"] {
            max-width: 40px !important;
            max-height: 40px !important;
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('Layout.header')

    <main>
        @yield('content')
    </main>

    @include('Layout.footer')
    
    @stack('scripts')
</body>
</html>