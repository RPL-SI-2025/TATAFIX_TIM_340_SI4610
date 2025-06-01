@extends('layouts.app')

@section('title', $service->title_service)

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Service Detail -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h2 class="card-title mb-3">{{ $service->title_service }}</h2>
                    
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge badge-primary mr-2">{{ $service->category->name }}</span>
                        <div class="text-warning">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($service->rating_avg))
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                            <span class="text-muted ml-1">({{ $service->rating_count }} ulasan)</span>
                        </div>
                    </div>
                    
                    @if($service->image)
                    <div class="service-image mb-4">
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title_service }}" class="img-fluid rounded">
                    </div>
                    @endif
                    
                    <div class="service-description mb-4">
                        <h4>Deskripsi Layanan</h4>
                        <p>{!! nl2br(e($service->description)) !!}</p>
                    </div>
                    
                    <div class="service-details mb-4">
                        <h4>Detail Layanan</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Harga Dasar</th>
                                <td>Rp {{ number_format($service->base_price, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Estimasi Waktu</th>
                                <td>{{ $service->estimated_time }}</td>
                            </tr>
                            <tr>
                                <th>Penyedia</th>
                                <td>{{ $service->provider->name ?? 'TataFix' }}</td>
                            </tr>
                        </table>
                    </div>
                    
                    @if(!empty($service->included_services))
                    <div class="included-services mb-4">
                        <h4>Layanan yang Termasuk</h4>
                        <ul class="list-group">
                            @foreach(explode(',', $service->included_services) as $included)
                                <li class="list-group-item">
                                    <i class="fas fa-check text-success mr-2"></i> {{ trim($included) }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    @if(!empty($service->excluded_services))
                    <div class="excluded-services mb-4">
                        <h4>Layanan yang Tidak Termasuk</h4>
                        <ul class="list-group">
                            @foreach(explode(',', $service->excluded_services) as $excluded)
                                <li class="list-group-item">
                                    <i class="fas fa-times text-danger mr-2"></i> {{ trim($excluded) }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <div class="service-reviews mb-4">
                        <h4>Ulasan Pelanggan</h4>
                        @if($service->reviews->isEmpty())
                            <div class="alert alert-info">Belum ada ulasan untuk layanan ini.</div>
                        @else
                            @foreach($service->reviews as $review)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h5 class="mb-0">{{ $review->user->name }}</h5>
                                            <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                                        </div>
                                        <div class="text-warning mb-2">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <p class="mb-0">{{ $review->comment }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Booking Layanan</h5>
                </div>
                <div class="card-body">
                    <div class="price-box mb-3 text-center">
                        <h3 class="mb-0">Rp {{ number_format($service->base_price, 0, ',', '.') }}</h3>
                        <small class="text-muted">Harga dasar, dapat berubah sesuai kondisi</small>
                    </div>
                    
                    <div class="booking-actions">
                        <a href="{{ route('booking.create', $service->service_id) }}" class="btn btn-primary btn-block btn-lg mb-3">
                            <i class="fas fa-calendar-check mr-2"></i> Booking Sekarang
                        </a>
                        
                        <a href="https://wa.me/+6281234567890?text=Saya%20tertarik%20dengan%20layanan%20{{ urlencode($service->title_service) }}" 
                           class="btn btn-success btn-block" target="_blank">
                            <i class="fab fa-whatsapp mr-2"></i> Tanya via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
            
            @if($relatedServices->isNotEmpty())
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Layanan Terkait</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($relatedServices as $relatedService)
                            <a href="{{ route('services.show', $relatedService->service_id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $relatedService->title_service }}</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-tag mr-1"></i> {{ $relatedService->category->name }}
                                        </small>
                                    </div>
                                    <span class="badge badge-primary badge-pill">
                                        Rp {{ number_format($relatedService->base_price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
