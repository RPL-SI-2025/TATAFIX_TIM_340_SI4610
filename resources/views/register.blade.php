<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Register | TATAFIX</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f6f6f6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
        }

        .header .logo {
            font-weight: 700;
            color: #1a73e8;
            font-size: 20px;
            text-decoration: none;
        }

        .header .auth-buttons a {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            font-size: 16px;
            margin-left: 10px;
            border: 1px solid #ccc;
        }

        .header .auth-buttons .signup-button {
            background-color: #1a73e8;
            color: white;
            border: none;
        }

        .container {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            z-index: 2;
        }

        .form-box {
            width: 400px;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            z-index: 10;
        }

        .form-box h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: left;
            color: #333;
        }

        .form-box p {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .form-box input {
            width: 100%;
            padding: 12px;
            margin-bottom: 18px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        .form-box button {
            width: 100%;
            padding: 12px;
            background-color: #1a73e8;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-box button:hover {
            background-color: #0e4caf;
        }

/* BULATAN KIRI */
.bg-decor-left {
    position: absolute;
    top: 180px;
    left: -100px;
    width: 260px;
    height: 260px;
    border-radius: 50%;
    opacity: 0.5;
    background: repeating-linear-gradient(
        to bottom,
        #DFA878,
        #DFA878 6px,
        transparent 6px,
        transparent 12px
    );
    z-index: 1;
}

/* BULATAN KANAN */
.bg-decor-right {
    position: absolute;
    top: 80px;
    right: -80px;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    opacity: 0.6;
    background: repeating-linear-gradient(
        to bottom,
        #F78C1F,
        #F78C1F 6px,
        transparent 6px,
        transparent 12px
    );
    z-index: 1;
}


        .bg-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 150px;
            z-index: 1;
        }

        .copyright {
            text-align: center;
            padding: 15px 0;
            font-size: 12px;
            color: white;
            background-color: #0d47a1;
            z-index: 3;
            position: relative;
        }

        .error-container {
            color: red;
            margin-bottom: 15px;
        }

        .error-container ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .error-container li {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <a href="/" class="logo">TATAFIX</a>
        <div class="auth-buttons">
            <a href="/login">Login</a>
            <a href="/register" class="signup-button">Sign Up</a>
        </div>
    </div>

    <div class="container">
        <div class="bg-decor-left"></div>
        <div class="bg-decor-right"></div>

        <div class="form-box">
            @if ($errors->any())
                <div class="error-container">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h1>Bergabunglah Bersama Kami</h1>
            <p>Mulai Daftar Sekarang!</p>

            <form method="POST" action="/register">
                @csrf
                <input type="text" name="name" placeholder="Nama Lengkap" required>
                <input type="email" name="email" placeholder="Alamat Email" required>
                <input type="text" name="phone" placeholder="No. Telepon" required>
                <input type="text" name="address" placeholder="Alamat Rumah" required>
                <input type="password" name="password" placeholder="Kata Sandi" required>
                <input type="password" name="password_confirmation" placeholder="Konfirmasi Kata Sandi" required>
                <button type="submit">Daftar</button>
            </form>

            @if (session('success'))
                <div class="mb-4 p-4 rounded-lg bg-green-100 border border-green-300 text-green-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>

    <div class="bg-wave">
        <svg viewBox="0 0 1440 320" width="100%" height="150" preserveAspectRatio="none">
            <path fill="#ff7043" fill-opacity="1"
                d="M0,288L60,261.3C120,235,240,181,360,181.3C480,181,600,235,720,245.3C840,256,960,224,1080,192C1200,160,1320,128,1380,112L1440,96L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z">
            </path>
        </svg>
    </div>

    <div class="copyright">
        Copyright &copy; 2024 TATAFIX | All Right Reserved
    </div>
</body>

</html>
