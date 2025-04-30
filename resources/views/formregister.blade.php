<!-- resources/views/register.blade.php -->

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
</head>

<body>
    <h2>Form Registrasi</h2>

    @if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
        @csrf <!-- INI PENTING -->

        <label>Nama:</label><br>
        <input type="text" name="name"><br>

        <label>Email:</label><br>
        <input type="email" name="email"><br>

        <label>No. Telepon:</label><br>
        <input type="text" name="phone" placeholder="Nomor Telepon" required>

        <label>Alamat Rumah:</label><br>
        <input type="text" name="address" placeholder="Alamat Rumah" required>

        <label>Password:</label><br>
        <input type="password" name="password"><br>

        <label>Konfirmasi Password:</label><br>
        <input type="password" name="password_confirmation"><br>

        <button type="submit">Daftar</button>
    </form>

</body>

</html>