@extends('Layout.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Informasi Akun</h4>
                </div>
                
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            <div class="text-center">
                                <i class="bi bi-check-circle text-success fs-3"></i>
                                <p class="mt-2">Perubahan ini telah mengupdate profil Anda. Apakah sudah sesuai?</p>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('profile.show') }}" class="btn btn-primary">Ya, Sesuai</a>
                                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">Tidak, Batalkan</a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                @if($user->profile_image)
                                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image">
                                @else
                                    <img src="{{ asset('images/default-avatar.jpg') }}" alt="Profile Image">
                                @endif
                                <label for="profile_image" class="btn btn-sm btn-primary position-absolute bottom-0 end-0">
                                    <i class="bi bi-pencil"></i>
                                </label>
                                <input type="file" name="profile_image" id="profile_image" class="d-none" accept="image/*">
                            </div>
                            
                            <h5 class="mt-2">{{ $user->name }}</h5>
                            <p class="text-muted small">{{ $user->email }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">No. Handphone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                                   placeholder="Contoh: 081234567890">
                            @error('phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" 
                                   id="email" value="{{ $user->email }}" readonly disabled>
                            <small class="text-muted">Email tidak dapat diubah</small>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">Batalkan</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection