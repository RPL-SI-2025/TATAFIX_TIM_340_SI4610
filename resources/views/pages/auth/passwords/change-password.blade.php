@extends('layouts.app')

@section('content')
<div style="font-family: Arial, sans-serif; background-color: #ffffff; display: flex; justify-content: center; align-items: center; height: 100vh;">
    <div style="background-color: #0D6E96; padding: 40px; border-radius: 20px; width: 400px; color: white; box-shadow: 0px 5px 20px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 20px; font-weight: bold; text-align: center;">Ganti Password</h2>

        @if(session('success'))
            <div style="background-color: #d1e7dd; color: #0f5132; padding: 10px; border-radius: 8px; margin-bottom: 15px;">
                {{ session('success') }}
            </div>

            <script>
             // Redirect ke profile setelah 2 detik
                setTimeout(function() {
                window.location.href = "{{ route('profile') }}";
                }, 2000);
            </script>
        @endif

        @if(session('error'))
            <div style="background-color: #f8d7da; color: #842029; padding: 10px; border-radius: 8px; margin-bottom: 15px;">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background-color: #f8d7da; color: #842029; padding: 10px; border-radius: 8px; margin-bottom: 15px;">
                <ul style="list-style-type: disc; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.change-password.update') }}">
            @csrf

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="current_password" style="display: block; margin-bottom: 8px;">Kata Sandi Lama</label>
                <div class="input-group" style="position: relative;">
                    <input type="password" id="current_password" name="current_password" required placeholder="Kata Sandi Lama"
                        style="width: 100%; padding: 12px 40px 12px 15px; border-radius: 15px; border: none; background-color: #e0e0e0; color: #333; font-size: 14px;">
                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('current_password', this)"
                        style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer; color: #333;"></i>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="password" style="display: block; margin-bottom: 8px;">Kata Sandi Baru</label>
                <div class="input-group" style="position: relative;">
                    <input type="password" id="password" name="password" required placeholder="Kata Sandi Baru"
                        style="width: 100%; padding: 12px 40px 12px 15px; border-radius: 15px; border: none; background-color: #e0e0e0; color: #333; font-size: 14px;">
                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password', this)"
                        style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer; color: #333;"></i>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label for="password_confirmation" style="display: block; margin-bottom: 8px;">Konfirmasi Kata Sandi Baru</label>
                <div class="input-group" style="position: relative;">
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Konfirmasi Kata Sandi Baru"
                        style="width: 100%; padding: 12px 40px 12px 15px; border-radius: 15px; border: none; background-color: #e0e0e0; color: #333; font-size: 14px;">
                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password_confirmation', this)"
                        style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer; color: #333;"></i>
                </div>
            </div>

            <button type="submit" style="width: 100%; padding: 12px; background-color: #003F62; border: none; color: white; border-radius: 15px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background-color 0.3s;"
                onmouseover="this.style.backgroundColor='#002e48'" 
                onmouseout="this.style.backgroundColor='#003F62'">
                Ganti Password
            </button>
        </form>
    </div>
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endsection
