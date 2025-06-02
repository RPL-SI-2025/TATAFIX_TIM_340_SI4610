@props(['status'])

@php
    $statusCode = strtolower($status->status_code ?? $status);
    $displayName = $status->display_name ?? ucwords(str_replace('_', ' ', $statusCode));
    
    $badgeClass = 'secondary';
    
    if (in_array($statusCode, ['pending', 'waiting_dp_validation', 'waiting_final_validation'])) {
        $badgeClass = 'warning';
        $textClass = 'text-yellow-800';
        $bgClass = 'bg-yellow-100';
    } elseif (in_array($statusCode, ['waiting_tukang_assignment', 'assigned'])) {
        $badgeClass = 'info';
        $textClass = 'text-blue-800';
        $bgClass = 'bg-blue-100';
    } elseif (in_array($statusCode, ['in_process', 'waiting_final_payment'])) {
        $badgeClass = 'primary';
        $textClass = 'text-indigo-800';
        $bgClass = 'bg-indigo-100';
    } elseif ($statusCode == 'completed') {
        $badgeClass = 'success';
        $textClass = 'text-green-800';
        $bgClass = 'bg-green-100';
    } elseif (in_array($statusCode, ['cancelled', 'rejected'])) {
        $badgeClass = 'danger';
        $textClass = 'text-red-800';
        $bgClass = 'bg-red-100';
    }
@endphp

{{-- Bootstrap Badge Style --}}
@if(isset($bootstrap) && $bootstrap)
<span {{ $attributes->merge(['class' => "badge badge-$badgeClass"]) }}>
    {{ $displayName }}
</span>
@else
{{-- Tailwind Badge Style --}}
<span {{ $attributes->merge(['class' => "px-3 py-1 rounded-full text-sm font-medium $textClass $bgClass"]) }}>
    {{ $displayName }}
</span>
@endif
