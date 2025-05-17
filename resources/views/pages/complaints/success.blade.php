@extends('layouts.app')

@section('title', 'Pengaduan Berhasil - TATAFIX')

@section('content')
<div class="thank-you-container">
    <div class="thank-you-card">
        <div class="checkmark-circle">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#4CAF50" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
        </div>
        
        <h1 class="thank-you-title">Terima kasih!</h1>
        
        <p class="thank-you-message">
            Pengaduan Anda telah berhasil dikirim.
        </p>
    </div>
</div>

<style>
    .thank-you-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 20px;
        background-color: #f8f9fa;
    }
    
    .thank-you-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 40px 50px;
        text-align: center;
        max-width: 500px;
        width: 100%;
    }
    
    .checkmark-circle {
        margin: 0 auto 25px;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f0fdf0;
        border-radius: 50%;
    }
    
    .thank-you-title {
        color: #2c3e50;
        font-size: 32px;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .thank-you-message {
        color: #555;
        font-size: 18px;
        line-height: 1.5;
    }
</style>
@endsection