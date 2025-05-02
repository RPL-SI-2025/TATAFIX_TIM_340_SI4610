@extends('layouts.app') {{-- atau layouts.master sesuai layout kamu --}}

@section('content')
<div class="container">
    <h2>Edit Profil</h2>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf

        <div>
            <label>Nama:</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}">
            @error('name')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label>Email:</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}">
            @error('email')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Simpan</button>
    </form>
</div>
@endsection
