@extends('layouts.app')

@section('title', 'Daftar Penugasan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Daftar Penugasan Saya</h4>
                </div>
                <div class="card-body">
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

                    <ul class="nav nav-tabs mb-4" id="bookingTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="true">
                                Menunggu Konfirmasi <span class="badge badge-warning">{{ $pendingBookings->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="active-tab" data-toggle="tab" href="#active" role="tab" aria-controls="active" aria-selected="false">
                                Sedang Dikerjakan <span class="badge badge-primary">{{ $activeBookings->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="completed-tab" data-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="false">
                                Selesai <span class="badge badge-success">{{ $completedBookings->count() }}</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="bookingTabsContent">
                        <!-- Pending Assignments Tab -->
                        <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                            @if($pendingBookings->isEmpty())
                                <div class="alert alert-info">
                                    Tidak ada penugasan yang menunggu konfirmasi.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>ID Booking</th>
                                                <th>Layanan</th>
                                                <th>Pelanggan</th>
                                                <th>Tanggal & Waktu</th>
                                                <th>Alamat</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingBookings as $booking)
                                                <tr>
                                                    <td>{{ $booking->id }}</td>
                                                    <td>{{ $booking->service->title_service }}</td>
                                                    <td>{{ $booking->user->name }}</td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}<br>
                                                        <small>{{ $booking->waktu_booking }}</small>
                                                    </td>
                                                    <td>{{ $booking->alamat }}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="{{ route('tukang.bookings.show', $booking->id) }}" class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i> Detail
                                                            </a>
                                                            <form action="{{ route('tukang.bookings.accept', $booking->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="btn btn-sm btn-success">
                                                                    <i class="fas fa-check"></i> Terima
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('tukang.bookings.reject', $booking->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="fas fa-times"></i> Tolak
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <!-- Active Assignments Tab -->
                        <div class="tab-pane fade" id="active" role="tabpanel" aria-labelledby="active-tab">
                            @if($activeBookings->isEmpty())
                                <div class="alert alert-info">
                                    Tidak ada penugasan yang sedang dikerjakan.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>ID Booking</th>
                                                <th>Layanan</th>
                                                <th>Pelanggan</th>
                                                <th>Tanggal & Waktu</th>
                                                <th>Alamat</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($activeBookings as $booking)
                                                <tr>
                                                    <td>{{ $booking->id }}</td>
                                                    <td>{{ $booking->service->title_service }}</td>
                                                    <td>{{ $booking->user->name }}</td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}<br>
                                                        <small>{{ $booking->waktu_booking }}</small>
                                                    </td>
                                                    <td>{{ $booking->alamat }}</td>
                                                    <td><span class="badge badge-primary">{{ $booking->status->status_name }}</span></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="{{ route('tukang.bookings.show', $booking->id) }}" class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i> Detail
                                                            </a>
                                                            @if($booking->status->status_code == 'in_progress' || $booking->status->status_code == 'IN_PROGRESS')
                                                                <form action="{{ route('tukang.bookings.complete', $booking->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" class="btn btn-sm btn-success">
                                                                        <i class="fas fa-check-circle"></i> Selesaikan
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <!-- Completed Assignments Tab -->
                        <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                            @if($completedBookings->isEmpty())
                                <div class="alert alert-info">
                                    Tidak ada penugasan yang telah selesai.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>ID Booking</th>
                                                <th>Layanan</th>
                                                <th>Pelanggan</th>
                                                <th>Tanggal & Waktu</th>
                                                <th>Status</th>
                                                <th>Tanggal Selesai</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($completedBookings as $booking)
                                                <tr>
                                                    <td>{{ $booking->id }}</td>
                                                    <td>{{ $booking->service->title_service }}</td>
                                                    <td>{{ $booking->user->name }}</td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}<br>
                                                        <small>{{ $booking->waktu_booking }}</small>
                                                    </td>
                                                    <td>
                                                        @if($booking->status->status_code == 'COMPLETED')
                                                            <span class="badge badge-success">{{ $booking->status->status_name }}</span>
                                                        @elseif($booking->status->status_code == 'WAITING_FINAL_PAYMENT' || $booking->status->status_code == 'WAITING_FINAL_VALIDATION')
                                                            <span class="badge badge-warning">{{ $booking->status->status_name }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $booking->completed_at ? \Carbon\Carbon::parse($booking->completed_at)->format('d M Y H:i') : '-' }}</td>
                                                    <td>
                                                        <a href="{{ route('tukang.bookings.show', $booking->id) }}" class="btn btn-sm btn-info">
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
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Log tab counts for debugging
        console.log('Pending bookings: {{ $pendingBookings->count() }}');
        console.log('Active bookings: {{ $activeBookings->count() }}');
        console.log('Completed bookings: {{ $completedBookings->count() }}');
        
        // Completely replace Bootstrap's tab handling with our own
        $('#bookingTabs a').off().on('click', function (e) {
            e.preventDefault();
            
            // Remove active class from all tabs and tab panes
            $('#bookingTabs a').removeClass('active');
            $('.tab-pane').removeClass('show active');
            
            // Add active class to clicked tab
            $(this).addClass('active');
            
            // Show corresponding tab pane
            var tabId = $(this).attr('href');
            $(tabId).addClass('show active');
            
            // Update URL hash
            window.location.hash = tabId;
            
            // Log which tab was clicked
            console.log('Tab clicked: ' + tabId);
        });
        
        // Set initial active tab based on URL hash or default to first tab
        var hash = window.location.hash;
        if (hash && $(hash).length > 0) {
            $('#bookingTabs a[href="' + hash + '"]').click();
            console.log('Initial tab from hash: ' + hash);
        } else if ({{ $activeBookings->count() }} > 0) {
            // If there are active bookings, show that tab by default
            $('#active-tab').click();
            console.log('Default to active tab because there are active bookings');
        } else {
            // Otherwise show the first tab (pending)
            $('#pending-tab').click();
            console.log('Default to first tab');
        }
    });
</script>
@endpush
