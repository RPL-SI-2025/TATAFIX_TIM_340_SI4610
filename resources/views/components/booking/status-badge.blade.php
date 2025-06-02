@props(['status'])

@php
    // Pastikan status code selalu dalam format yang konsisten (lowercase)
    $statusCode = strtolower($status->status_code ?? $status);
    $displayName = $status->display_name ?? ucwords(str_replace('_', ' ', $statusCode));
    
    // Default values
    $badgeClass = 'secondary';
    $textClass = 'text-gray-800';
    $bgClass = 'bg-gray-100';
    
    // Pending/Waiting states
    if (in_array($statusCode, ['pending', 'waiting_dp', 'pending_dp', 'waiting_dp_validation', 'waiting_validation_dp'])) {
        $badgeClass = 'warning';
        $textClass = 'text-yellow-800';
        $bgClass = 'bg-yellow-100';
        $displayName = 'Menunggu Pembayaran DP';
    } 
    // DP Validated states
    elseif (in_array($statusCode, ['dp_validated', 'waiting_tukang_assignment', 'assigned'])) {
        $badgeClass = 'info';
        $textClass = 'text-blue-800';
        $bgClass = 'bg-blue-100';
        $displayName = 'DP Tervalidasi';
    } 
    // In Progress states
    elseif (in_array($statusCode, ['in_progress', 'in_process'])) {
        $badgeClass = 'primary';
        $textClass = 'text-indigo-800';
        $bgClass = 'bg-indigo-100';
        $displayName = 'Dalam Pengerjaan';
    } 
    // Done/Waiting Final Payment states
    elseif (in_array($statusCode, ['done', 'waiting_final_payment'])) {
        $badgeClass = 'primary';
        $textClass = 'text-indigo-800';
        $bgClass = 'bg-indigo-100';
        $displayName = 'Menunggu Pelunasan';
    } 
    // Waiting Final Validation states
    elseif (in_array($statusCode, ['waiting_final_validation', 'waiting_validation_pelunasan', 'validating_final_payment'])) {
        $badgeClass = 'warning';
        $textClass = 'text-yellow-800';
        $bgClass = 'bg-yellow-100';
        $displayName = 'Validasi Pelunasan';
    } 
    // Completed states
    elseif (in_array($statusCode, ['completed'])) {
        $badgeClass = 'success';
        $textClass = 'text-green-800';
        $bgClass = 'bg-green-100';
        $displayName = 'Selesai';
    } 
    // Cancelled/Rejected states
    elseif (in_array($statusCode, ['cancelled', 'canceled', 'rejected'])) {
        $badgeClass = 'danger';
        $textClass = 'text-red-800';
        $bgClass = 'bg-red-100';
        $displayName = $statusCode == 'rejected' ? 'Ditolak' : 'Dibatalkan';
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
