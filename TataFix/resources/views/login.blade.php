<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TataFix</title>
    <link rel="stylesheet" href="path/to/your/css/styles.css">
</head>
<body>
    <div class="login-container">
        <h1>Selamat Datang</h1>
        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required placeholder="Masukkan Email">

            <label for="password">Kata Sandi:</label>
            <input type="password" name="password" id="password" required placeholder="Masukkan Kata Sandi">

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>