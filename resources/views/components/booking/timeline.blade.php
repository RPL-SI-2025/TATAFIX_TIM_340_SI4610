@props(['booking', 'style' => 'modern'])

@php
    // Definisikan tahapan utama tracking
    $mainSteps = [
        [
            'label' => 'Pesanan Dibuat',
            'icon' => 'fas fa-clipboard-check',
            'status_codes' => ['pending', 'waiting_dp_validation', 'dp_validated'],
            'date' => $booking->created_at
        ],
        [
            'label' => 'Tukang Ditugaskan',
            'icon' => 'fas fa-user-hard-hat',
            'status_codes' => ['waiting_tukang_assignment', 'assigned'],
            'date' => $booking->bookingLogs()
                ->whereHas('status', function($q) {
                    $q->whereIn('status_code', ['assigned']);
                })->first() ? $booking->bookingLogs()
                ->whereHas('status', function($q) {
                    $q->whereIn('status_code', ['assigned']);
                })->first()->created_at : null
        ],
        [
            'label' => 'Pengerjaan',
            'icon' => 'fas fa-tools',
            'status_codes' => ['in_process'],
            'date' => $booking->bookingLogs()
                ->whereHas('status', function($q) {
                    $q->where('status_code', 'in_process');
                })->first() ? $booking->bookingLogs()
                ->whereHas('status', function($q) {
                    $q->where('status_code', 'in_process');
                })->first()->created_at : null
        ],
        [
            'label' => 'Pelunasan',
            'icon' => 'fas fa-money-bill-wave',
            'status_codes' => ['waiting_final_payment', 'waiting_final_validation'],
            'date' => $booking->bookingLogs()
                ->whereHas('status', function($q) {
                    $q->whereIn('status_code', ['waiting_final_payment', 'waiting_final_validation']);
                })->first() ? $booking->bookingLogs()
                ->whereHas('status', function($q) {
                    $q->whereIn('status_code', ['waiting_final_payment', 'waiting_final_validation']);
                })->first()->created_at : null
        ],
        [
            'label' => 'Selesai',
            'icon' => 'fas fa-check-circle',
            'status_codes' => ['completed'],
            'date' => $booking->bookingLogs()
                ->whereHas('status', function($q) {
                    $q->where('status_code', 'completed');
                })->first() ? $booking->bookingLogs()
                ->whereHas('status', function($q) {
                    $q->where('status_code', 'completed');
                })->first()->created_at : null
        ]
    ];
    
    // Tentukan step aktif berdasarkan status booking saat ini
    $currentStatusCode = strtolower($booking->status->status_code);
    $activeStepIndex = 0;
    
    foreach ($mainSteps as $index => $step) {
        if (in_array($currentStatusCode, $step['status_codes'])) {
            $activeStepIndex = $index;
            break;
        }
    }
@endphp

@if($style === 'modern')
<div class="timeline-container">
    <div class="timeline-track">
        @foreach ($mainSteps as $index => $step)
            <div class="timeline-step {{ $index < $activeStepIndex ? 'completed' : ($index == $activeStepIndex ? 'active' : '') }}">
                <div class="timeline-step-icon">
                    <i class="{{ $step['icon'] }}"></i>
                </div>
                <div class="timeline-step-label">{{ $step['label'] }}</div>
                <div class="timeline-step-date">
                    @if ($step['date'])
                        {{ \Carbon\Carbon::parse($step['date'])->format('d M Y') }}
                    @else
                        -
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@elseif($style === 'vertical')
<div class="vertical-timeline">
    @foreach ($mainSteps as $index => $step)
        <div class="vertical-timeline-item {{ $index < $activeStepIndex ? 'completed' : ($index == $activeStepIndex ? 'active' : '') }}">
            <div class="vertical-timeline-marker">
                <div class="vertical-timeline-icon">
                    <i class="{{ $step['icon'] }}"></i>
                </div>
                @if($index < count($mainSteps) - 1)
                    <div class="vertical-timeline-line"></div>
                @endif
            </div>
            <div class="vertical-timeline-content">
                <h4 class="vertical-timeline-title">{{ $step['label'] }}</h4>
                <p class="vertical-timeline-date">
                    @if ($step['date'])
                        {{ \Carbon\Carbon::parse($step['date'])->format('d M Y') }}
                    @else
                        Menunggu
                    @endif
                </p>
            </div>
        </div>
    @endforeach
</div>
@endif

<style>
    /* Modern Timeline (Horizontal) */
    .timeline-container {
        margin: 30px 0;
    }
    
    .timeline-track {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 40px;
    }
    
    .timeline-track::before {
        content: '';
        position: absolute;
        top: 25px;
        left: 0;
        width: 100%;
        height: 4px;
        background-color: #e0e0e0;
        z-index: 1;
    }
    
    .timeline-step {
        position: relative;
        z-index: 2;
        text-align: center;
        width: 20%;
    }
    
    .timeline-step-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        color: white;
        font-size: 20px;
        transition: all 0.3s ease;
    }
    
    .timeline-step.active .timeline-step-icon {
        background-color: #fd7e14;
        box-shadow: 0 0 15px rgba(253, 126, 20, 0.5);
        transform: scale(1.1);
    }
    
    .timeline-step.completed .timeline-step-icon {
        background-color: #28a745;
        box-shadow: 0 0 10px rgba(40, 167, 69, 0.3);
    }
    
    .timeline-step-label {
        font-weight: 600;
        margin-bottom: 5px;
        color: #495057;
    }
    
    .timeline-step-date {
        font-size: 14px;
        color: #6c757d;
    }
    
    /* Vertical Timeline */
    .vertical-timeline {
        position: relative;
        max-width: 500px;
        margin: 0 auto;
    }
    
    .vertical-timeline-item {
        display: flex;
        margin-bottom: 30px;
    }
    
    .vertical-timeline-marker {
        position: relative;
        margin-right: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .vertical-timeline-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        z-index: 2;
    }
    
    .vertical-timeline-line {
        position: absolute;
        top: 40px;
        width: 2px;
        height: calc(100% + 30px);
        background-color: #e0e0e0;
        z-index: 1;
    }
    
    .vertical-timeline-item.active .vertical-timeline-icon {
        background-color: #fd7e14;
        box-shadow: 0 0 10px rgba(253, 126, 20, 0.5);
    }
    
    .vertical-timeline-item.completed .vertical-timeline-icon {
        background-color: #28a745;
    }
    
    .vertical-timeline-item.completed .vertical-timeline-line {
        background-color: #28a745;
    }
    
    .vertical-timeline-content {
        flex: 1;
        padding: 0 10px;
    }
    
    .vertical-timeline-title {
        margin: 0 0 5px;
        font-weight: 600;
    }
    
    .vertical-timeline-date {
        font-size: 14px;
        color: #6c757d;
        margin: 0;
    }
</style>
