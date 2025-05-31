@extends('layouts.admin')

@section('title', 'Detail Booking')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Booking #{{ $booking->id }}</h1>
        <a href="{{ route('admin.bookings.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Booking</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 font-weight-bold">ID Booking</p>
                            <p>{{ $booking->id }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 font-weight-bold">Status</p>
                            @php
                                $statusCode = $booking->status->status_code;
                                $badgeClass = 'secondary';
                                
                                if (in_array($statusCode, ['PENDING', 'WAITING_DP_VALIDATION', 'WAITING_FINAL_VALIDATION'])) {
                                    $badgeClass = 'warning';
                                } elseif (in_array($statusCode, ['WAITING_TUKANG_ASSIGNMENT'])) {
                                    $badgeClass = 'info';
                                } elseif (in_array($statusCode, ['ASSIGNED', 'IN_PROCESS', 'WAITING_FINAL_PAYMENT'])) {
                                    $badgeClass = 'primary';
                                } elseif ($statusCode == 'COMPLETED') {
                                    $badgeClass = 'success';
                                } elseif ($statusCode == 'CANCELLED') {
                                    $badgeClass = 'danger';
                                }
                            @endphp
                            <p><span class="badge badge-{{ $badgeClass }}">{{ $booking->status->status_name }}</span></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 font-weight-bold">Tanggal Booking</p>
                            <p>{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 font-weight-bold">Waktu Booking</p>
                            <p>{{ $booking->waktu_booking }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 font-weight-bold">Tanggal Dibuat</p>
                            <p>{{ $booking->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 font-weight-bold">Terakhir Diperbarui</p>
                            <p>{{ $booking->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="mb-1 font-weight-bold">Alamat</p>
                            <p>{{ $booking->alamat }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="mb-1 font-weight-bold">Catatan</p>
                            <p>{{ $booking->notes ?? 'Tidak ada catatan' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Layanan</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 font-weight-bold">Nama Layanan</p>
                            <p>{{ $booking->service->title_service }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 font-weight-bold">Kategori</p>
                            <p>{{ $booking->service->category->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 font-weight-bold">Harga Dasar</p>
                            <p>Rp {{ number_format($booking->service->base_price, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 font-weight-bold">Penyedia Layanan</p>
                            <p>{{ $booking->service->provider->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="mb-1 font-weight-bold">Deskripsi Layanan</p>
                            <p>{{ $booking->service->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Pembayaran</h6>
                </div>
                <div class="card-body">
                    @if($booking->payments->isEmpty())
                        <div class="alert alert-info">
                            Belum ada riwayat pembayaran untuk booking ini.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tanggal</th>
                                        <th>Jenis</th>
                                        <th>Metode</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($booking->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->id }}</td>
                                            <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                @if($payment->payment_type == 'dp')
                                                    <span class="badge badge-info">Down Payment</span>
                                                @else
                                                    <span class="badge badge-success">Pelunasan</span>
                                                @endif
                                            </td>
                                            <td>{{ $payment->payment_method == 'bank_transfer' ? 'Transfer Bank' : 'E-Wallet' }}</td>
                                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                            <td>
                                                @if($payment->status == 'pending')
                                                    <span class="badge badge-warning">Menunggu Validasi</span>
                                                @elseif($payment->status == 'approved')
                                                    <span class="badge badge-success">Disetujui</span>
                                                @elseif($payment->status == 'rejected')
                                                    <span class="badge badge-danger">Ditolak</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pelanggan</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img class="img-profile rounded-circle" src="{{ $booking->user->profile_picture ? asset('storage/' . $booking->user->profile_picture) : asset('img/undraw_profile.svg') }}" width="100">
                        <h5 class="mt-3">{{ $booking->user->name }}</h5>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1 font-weight-bold">Email</p>
                        <p>{{ $booking->user->email }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1 font-weight-bold">No. Telepon</p>
                        <p>{{ $booking->user->phone ?? 'Tidak tersedia' }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1 font-weight-bold">Bergabung Sejak</p>
                        <p>{{ $booking->user->created_at->format('d M Y') }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1 font-weight-bold">Jumlah Booking</p>
                        <p>{{ $booking->user->bookings->count() }} booking</p>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tukang</h6>
                </div>
                <div class="card-body">
                    @if($booking->tukang)
                        <div class="text-center mb-3">
                            <img class="img-profile rounded-circle" src="{{ $booking->tukang->profile_picture ? asset('storage/' . $booking->tukang->profile_picture) : asset('img/undraw_profile.svg') }}" width="100">
                            <h5 class="mt-3">{{ $booking->tukang->name }}</h5>
                        </div>

                        <div class="mb-3">
                            <p class="mb-1 font-weight-bold">Email</p>
                            <p>{{ $booking->tukang->email }}</p>
                        </div>

                        <div class="mb-3">
                            <p class="mb-1 font-weight-bold">No. Telepon</p>
                            <p>{{ $booking->tukang->phone ?? 'Tidak tersedia' }}</p>
                        </div>

                        <div class="mb-3">
                            <p class="mb-1 font-weight-bold">Spesialisasi</p>
                            <p>{{ $booking->tukang->specialization ?? 'Tidak tersedia' }}</p>
                        </div>

                        <div class="mb-3">
                            <p class="mb-1 font-weight-bold">Rating</p>
                            <p>
                                @php
                                    $rating = $booking->tukang->rating_avg ?? 0;
                                @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @elseif($i <= $rating + 0.5)
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                                ({{ number_format($rating, 1) }})
                            </p>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <div class="mb-3">
                                <i class="fas fa-user-slash fa-3x text-gray-300"></i>
                            </div>
                            <p>Belum ada tukang yang ditugaskan</p>
                            
                            @if($booking->status->status_code == 'WAITING_TUKANG_ASSIGNMENT')
                                <a href="{{ route('admin.bookings.assign', $booking->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-user-plus"></i> Tugaskan Tukang
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi</h6>
                </div>
                <div class="card-body">
                    @php
                        $statusCode = $booking->status->status_code;
                    @endphp
                    
                    @if($statusCode == 'WAITING_DP_VALIDATION')
                        <div class="mb-3">
                            <a href="{{ route('admin.payments.show', $booking->payments()->latest()->first()->id) }}" class="btn btn-warning btn-block">
                                <i class="fas fa-money-bill"></i> Validasi Pembayaran DP
                            </a>
                        </div>
                    @endif
                    
                    @if($statusCode == 'WAITING_TUKANG_ASSIGNMENT')
                        <div class="mb-3">
                            <a href="{{ route('admin.bookings.assign', $booking->id) }}" class="btn btn-info btn-block">
                                <i class="fas fa-user-cog"></i> Tugaskan Tukang
                            </a>
                        </div>
                    @endif
                    
                    @if($statusCode == 'WAITING_FINAL_VALIDATION')
                        <div class="mb-3">
                            <a href="{{ route('admin.payments.show', $booking->payments()->latest()->first()->id) }}" class="btn btn-warning btn-block">
                                <i class="fas fa-money-bill"></i> Validasi Pelunasan
                            </a>
                        </div>
                    @endif
                    
                    @if(!in_array($statusCode, ['COMPLETED', 'CANCELLED']))
                        <div class="mb-3">
                            <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#cancelBookingModal">
                                <i class="fas fa-times-circle"></i> Batalkan Booking
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Booking Modal -->
<div class="modal fade" id="cancelBookingModal" tabindex="-1" role="dialog" aria-labelledby="cancelBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelBookingModalLabel">Konfirmasi Pembatalan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin membatalkan booking ini?</p>
                    <div class="form-group">
                        <label for="cancel_reason">Alasan Pembatalan</label>
                        <textarea class="form-control" id="cancel_reason" name="cancel_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Batalkan Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
