@extends('layouts.app')

@section('title', 'Layanan ' . $category->name)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('services.index') }}">Layanan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Layanan {{ $category->name }}</h2>
            </div>
            
            @if($services->isEmpty())
                <div class="alert alert-info">
                    Belum ada layanan tersedia untuk kategori ini.
                </div>
            @else
                <div class="row">
                    @foreach($services as $service)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                @if($service->image)
                                    <img src="{{ asset('storage/' . $service->image) }}" class="card-img-top" alt="{{ $service->title_service }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-tools fa-3x text-muted"></i>
                                    </div>
                                @endif
                                
                                <div class="card-body">
                                    <h5 class="card-title">{{ $service->title_service }}</h5>
                                    
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge badge-primary mr-2">{{ $category->name }}</span>
                                        <div class="text-warning">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= round($service->rating_avg))
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    
                                    <p class="card-text text-muted">
                                        {{ \Illuminate\Support\Str::limit($service->description, 100) }}
                                    </p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <h5 class="mb-0 text-primary">Rp {{ number_format($service->base_price, 0, ',', '.') }}</h5>
                                        <a href="{{ route('services.show', $service->service_id) }}" class="btn btn-outline-primary">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $services->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
