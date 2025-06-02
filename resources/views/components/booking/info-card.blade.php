@props(['booking', 'showActions' => true])

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-8">
                <div class="d-flex">
                    <img src="{{ $booking->service->image_url ?? asset('images/default-service.jpg') }}" 
                         alt="{{ $booking->service->title_service }}" 
                         class="booking-image me-3" 
                         style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px;">
                    <div>
                        <h5 class="fw-bold mb-1">{{ $booking->service->title_service }}</h5>
                        <p class="text-muted mb-2">{{ $booking->service->category->name ?? 'Kategori' }}</p>
                        <p class="mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>
                            {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}
                            <span class="mx-2">|</span>
                            <i class="fas fa-clock me-2"></i>
                            {{ $booking->waktu_booking }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <h5 class="fw-bold text-primary mb-2">Rp{{ number_format($booking->service->base_price, 0, ',', '.') }}</h5>
                <x-booking.status-badge :status="$booking->status" bootstrap />
            </div>
        </div>
        
        @if($showActions)
        <hr class="my-3">
        <div class="d-flex justify-content-end">
            <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-sm btn-primary me-2">
                <i class="fas fa-eye me-1"></i> Detail
            </a>
            <a href="{{ route('booking.tracking', $booking->id) }}" class="btn btn-sm btn-info me-2">
                <i class="fas fa-map-marker-alt me-1"></i> Tracking
            </a>
            <a href="{{ route('invoices.generate', $booking->id) }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-file-invoice me-1"></i> Invoice
            </a>
        </div>
        @endif
    </div>
</div>

<style>
    .booking-image {
        transition: transform 0.3s ease;
    }
    
    .card:hover .booking-image {
        transform: scale(1.05);
    }
</style>
