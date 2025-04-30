<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TataFix - Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .left-decoration {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 200px;
            height: 400px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .left-decoration div {
            height: 8px;
            background: linear-gradient(to right, #FF6B00, #FFB088);
            border-radius: 4px;
        }

        .right-decoration {
            position: absolute;
            right: 0;
            bottom: 0;
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #FF6B00, #FFB088);
            border-radius: 50% 0 0 0;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 450px;
            margin: 50px auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 50px;
        }

        .logo {
            color: #1B6FA8;
            font-size: 24px;
            font-weight: bold;
        }

        .nav-buttons {
            display: flex;
            gap: 10px;
        }

        .login-btn {
            background: #1B6FA8;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .signup-btn {
            border: 1px solid #1B6FA8;
            color: #1B6FA8;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome-text h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .welcome-text p {
            color: #666;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .forgot-password {
            text-align: right;
            margin-bottom: 20px;
        }

        .forgot-password a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }

        .login-submit {
            width: 100%;
            padding: 12px;
            background: #1B6FA8;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #003B5C;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="left-decoration">
        <?php for($i = 60; $i <= 100; $i += 10): ?>
            <div style="width: <?php echo $i; ?>%;"></div>
        <?php endfor; ?>
        <?php for($i = 90; $i >= 60; $i -= 10): ?>
            <div style="width: <?php echo $i; ?>%;"></div>
        <?php endfor; ?>
    </div>

    <div class="right-decoration"></div>

    <div class="container">
        <div class="header">
            <div class="logo">TataFix</div>
            <div class="nav-buttons">
                <a href="login.php" class="login-btn">Login</a>
                <a href="register.php" class="signup-btn">Sign Up</a>
            </div>
        </div>

        <div class="welcome-text">
            <h1>Selamat Datang</h1>
            <p>Masuk untuk Mendapatkan Layanan Terbaik!</p>
        </div>

        <form action="login_process.php" method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Masukkan Email" required>
            </div>

            <div class="form-group">
                <label>Kata Sandi</label>
                <input type="password" name="password" placeholder="Masukkan Kata Sandi" required>
            </div>

            <div class="forgot-password">
                <a href="forgot_password.php">Lupa Kata Sandi?</a>
            </div>

            <button type="submit" class="login-submit">Login</button>
        </form>
    </div>

    <div class="footer">
        Copyright © <?php echo date('Y'); ?> TATAFIX| All Right Reserved
    </div>
</body>
</html>