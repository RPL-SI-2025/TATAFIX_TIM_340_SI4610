<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking TataFix</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Form Booking Layanan TataFix</h2>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('booking.store') }}" method="POST">
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
                <input type="text" class="form-control" id="no_handphone" name="no_handphone" required>
            </div>
            <div class="form-group">
                <label for="catatan_perbaikan">Catatan Perbaikan</label>
                <textarea class="form-control" id="catatan_perbaikan" name="catatan_perbaikan" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Lanjut Pembayaran DP</button>
        </form>
    </div>

    <footer class="bg-light text-center text-lg-start">
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            <a href="{{ route('home') }}">Home</a> |
            <a href="{{ route('booking') }}">Booking</a> |
            {{-- <a href="{{ route('chat') }}">Chat</a> |
            <a href="{{ route('terms') }}">Terms of Service</a> |
            <a href="{{ route('privacy') }}">Privacy Policy</a> | --}}
            <a href="https://facebook.com">Facebook</a> |
            <a href="https://twitter.com">Twitter</a> |
            <a href="https://instagram.com">Instagram</a>
        </div>
    </footer>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
