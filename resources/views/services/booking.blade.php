@extends('Layout.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-3">
                <div class="card-body p-4">
                    <h3 class="mb-4 fw-bold">Booking Layanan</h3>
                    <p class="text-muted mb-4">Silahkan isi form berikut untuk memesan layanan</p>
                    
                    <form method="POST" action="{{ route('services.book') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <input type="text" class="form-control py-2" name="service_type" placeholder="Jenis Layanan" required>
                        </div>
                        
                        <div class="mb-3">
                            <input type="text" class="form-control py-2" name="address" placeholder="Alamat Lengkap" required>
                        </div>
                        
                        <div class="mb-3">
                            <input type="date" class="form-control py-2" name="service_date" placeholder="Tanggal Layanan" required>
                        </div>
                        
                        <div class="mb-3">
                            <input type="time" class="form-control py-2" name="service_time" placeholder="Waktu Layanan" required>
                        </div>
                        
                        <div class="mb-3">
                            <textarea class="form-control py-2" name="description" rows="3" placeholder="Deskripsi Masalah"></textarea>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2">Booking Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection