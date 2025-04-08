<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>TataFix - Login</title>
</head>
<body class="bg-light">

    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card p-5" style="width: 400px; border-radius: 15px;">
            <h2 class="text-center mb-4">Selamat Datang</h2>
            <p class="text-center mb-4">Masuk untuk Mendapatkan Layanan Terbaik!</p>
            
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email" required>
                </div>
                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Kata Sandi" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
                <div class="text-center mt-3">
                    <a href="#">Lupa Kata Sandi?</a>
                </div>
            </form>

            <div class="text-center mt-4">
                <a href="#" class="btn btn-link">Sign Up</a>
            </div>
            
            <footer class="text-center mt-4">
                <p>&copy; 2024 TATAFIX | All Rights Reserved</p>
            </footer>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>