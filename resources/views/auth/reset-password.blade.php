<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .reset-form {
            background-color: #0D6E96;
            padding: 40px;
            border-radius: 20px;
            width: 400px;
            color: white;
            box-shadow: 0px 5px 20px rgba(0,0,0,0.1);
        }

        .reset-form h2 {
            margin-bottom: 20px;
            font-weight: bold;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group input {
            width: 90%;
            padding: 12px 40px 12px 15px;
            border-radius: 15px;
            border: none;
            background-color: #e0e0e0;
            color: #333;
            font-size: 14px;
        }

        .input-group .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #333;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #003F62;
            border: none;
            color: white;
            border-radius: 15px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #002e48;
        }
    </style>
</head>
<body>

<div class="reset-form">
    <h2>Ubah kata sandi</h2>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="email" value="{{ old('email', request('email')) }}">
        <input type="hidden" name="token" value="{{ old('token', request('token')) }}">

        <div class="input-group">
            <input type="password" name="password" placeholder="Kata sandi baru" id="password">
            <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
        </div>

        <div class="input-group">
            <input type="password" name="password_confirmation" placeholder="Konfirmasi kata sandi" id="password_confirmation">
            <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password_confirmation', this)"></i>
        </div>

        <button type="submit">Atur sandi</button>
    </form>
</div>

<script>
    function togglePassword(fieldId, icon) {
        const field = document.getElementById(fieldId);
        const isPassword = field.type === 'password';
        field.type = isPassword ? 'text' : 'password';
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }
</script>

</body>
</html>
