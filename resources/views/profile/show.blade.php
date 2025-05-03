@extends('layout.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ auth()->user()->profile_image ? asset('storage/profile-images/' . auth()->user()->profile_image) : asset('images/default-avatar.jpg') }}" 
                             alt="Profile Image" class="rounded-circle" width="100" height="100">
                        <h4 class="mt-3">{{ auth()->user()->name }}</h4>
                        <p class="text-muted">{{ auth()->user()->email }}</p>
                        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary">Edit Profil</a>
                    </div>
                    
                    <h5 class="mb-3">Informasi Akun</h5>
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1">Nama Lengkap</p>
                            <p class="fw-medium">{{ auth()->user()->name }}</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1">No. Handphone</p>
                            <p class="fw-medium">{{ auth()->user()->phone ?? '-' }}</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1">Email</p>
                            <p class="fw-medium">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection