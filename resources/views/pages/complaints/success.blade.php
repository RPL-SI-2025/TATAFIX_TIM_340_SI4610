@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">Informasi Pengaduan</div>

                <div class="card-body text-center">
                    <div class="alert alert-success">
                        <h4>Terima kasih! Pengaduan Anda telah berhasil dikirim.</h4>
                        <p>Tim kami akan segera memproses pengaduan Anda.</p>
                    </div>
                    

                        <a href="{{ route('customer.complaints.index') }}" class="btn btn-primary">Lihat Daftar Pengaduan</a>
                        <a href="{{ route('customer.complaints.create') }}" class="btn btn-outline-primary">Buat Pengaduan Baru</a>
                    </div>

                    <div>
                        &nbsp;
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection