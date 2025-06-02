@props(['booking'])

<div class="card border-0 shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">Riwayat Status</h5>
    </div>
    <div class="card-body p-4">
        @if($booking->bookingLogs && $booking->bookingLogs->count() > 0)
            <div class="status-logs">
                @foreach($booking->bookingLogs->sortByDesc('created_at') as $log)
                    <div class="status-log">
                        <div class="status-log-dot"></div>
                        <div class="status-log-content">
                            <h6 class="mb-1">{{ $log->status->display_name ?? ucwords(str_replace('_', ' ', $log->status->status_code)) }}</h6>
                            <p class="mb-0">{{ $log->notes ?? 'Tidak ada catatan' }}</p>
                            <small class="status-log-date">{{ $log->created_at->format('d M Y H:i') }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-muted py-3">Belum ada riwayat status</p>
        @endif
    </div>
</div>

<style>
    .status-logs {
        position: relative;
    }
    
    .status-log {
        position: relative;
        padding-left: 30px;
        padding-bottom: 20px;
        border-left: 2px solid #e0e0e0;
    }
    
    .status-log:last-child {
        border-left: 2px solid transparent;
    }
    
    .status-log-dot {
        position: absolute;
        left: -10px;
        top: 0;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #fd7e14;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #fd7e14;
    }
    
    .status-log-content {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-left: 15px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .status-log-content:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    
    .status-log-date {
        font-size: 14px;
        color: #6c757d;
    }
</style>
