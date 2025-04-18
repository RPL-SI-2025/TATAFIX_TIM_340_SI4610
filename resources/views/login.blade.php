<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TataFix - Login</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>


    <!-- HEADER -->
    <div class="header">
    <div class="logo">TataFix</div>
    <div class="nav-buttons">
        <a href="{{ route('login') }}" class="login-btn {{ request()->routeIs('login') ? 'active' : '' }}">Login</a>
        <a href="{{ route('register') }}" class="signup-btn {{ request()->routeIs('register') ? 'active' : '' }}">Sign Up</a>
    </div>
</div>


    <!-- DECORATION LEFT -->
    <div class="left-decoration"></div>

    <!-- DECORATION RIGHT -->
    <div class="right-decoration"></div>

    <!-- LOGIN CONTAINER -->
    <div class="login-container">
        <h1>Selamat Datang</h1>
        <p>Masuk untuk Mendapatkan Layanan Terbaik!</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Masukkan Email" required>
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Kata Sandi</label>
                <input type="password" name="password" placeholder="Masukkan Kata Sandi" required>
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="forgot-password">
                <a href="{{ route('password.request') }}">Lupa Kata Sandi?</a>
            </div>

            <button type="submit" class="login-submit">Login</button>
        </form>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        Copyright © {{ date('Y') }} TATAFIX | All Right Reserved
    </footer>
</body>
</html>
