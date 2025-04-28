@extends('backgroundbooking')

@section('content')
    <h2>Form Booking Layanan TataFix</h2>

    <!-- Menampilkan pesan sukses -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Menampilkan error validasi -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('booking.store') }}">
        @csrf

        <div class="form-group">
            <label for="nama_pemesan">Nama Pemesan</label>
            <input type="text" class="form-control" id="nama_pemesan" name="nama_pemesan" required>
        </div>

        <div class="form-group">
            <label for="alamat">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <label for="no_handphone">No Handphone</label>
            <input type="text" class="form-control" id="no_handphone" name="no_handphone" required placeholder="Masukkan nomor handphone anda">
        </div>

        <div class="form-group">
            <label for="catatan_perbaikan">Catatan Perbaikan</label>
            <textarea class="form-control" id="catatan_perbaikan" name="catatan_perbaikan" rows="4" required placeholder="Masukkan catatan perbaikan anda"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Lanjut Pembayaran DP</button>
    </form>
@endsection