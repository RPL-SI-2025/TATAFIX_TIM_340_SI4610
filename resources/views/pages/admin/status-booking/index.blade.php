@extends('layouts.admin')

@section('content')
<script>
    // Redirect to the new bookings management page
    window.location.href = '{{ route("admin.bookings.index") }}';
</script>
<div class="container mx-auto px-3 py-4 max-w-full">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold text-gray-800">Manajemen Status Booking</h1>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded mb-3 text-sm" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Booking Search Form --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-4">
        <div class="p-4 border-b border-gray-200">
            <form action="{{ route('admin.status-booking.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Customer</label>
                    <input type="text" 
                           name="customer_name" 
                           value="{{ request('customer_name') }}" 
                           placeholder="Cari nama customer..."
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2 px-3">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="booking_status" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2 px-3">
                        <option value="">Semua Status</option>
                        @foreach($allBookingStatuses as $status)
                            <option value="{{ $status->status_code }}" {{ request('booking_status') == $status->status_code ? 'selected' : '' }}>
                                {{ $status->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm transition-colors">
                        <i class="fas fa-search mr-1"></i>Cari
                    </button>
                    <a href="{{ route('admin.status-booking.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm transition-colors">
                        <i class="fas fa-sync-alt mr-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Booking List Table --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        {{-- Hapus div dengan h2 "Daftar Booking" --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Nama Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Layanan</th> {{-- Mengganti 'Email' menjadi 'Layanan' --}}
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Keterangan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Tanggal</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($bookings as $booking)
                        @php
                            $isFinalStatus = in_array($booking->status->status_code, ['COMPLETED', 'CANCELLED']);
                        @endphp
                        <tr class="hover:bg-gray-50 @if($isFinalStatus) {{ $booking->status->status_code == 'COMPLETED' ? 'bg-green-50' : 'bg-red-50' }} @endif">
                            <td class="px-4 py-3 text-sm font-medium {{ $isFinalStatus ? 'text-gray-700' : 'text-gray-900' }}">
                                {{ $booking->nama_pemesan ?? $booking->user->name }}
                            </td>
                            <td class="px-4 py-3 text-sm {{ $isFinalStatus ? 'text-gray-500' : 'text-gray-600' }}">
                                {{-- Menampilkan service_name --}}
                                {{-- Menggunakan relasi service untuk mendapatkan title_service --}}
                                {{ $booking->service ? $booking->service->title_service : 'N/A' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                    @switch($booking->status->status_code)
                                        @case('PENDING') bg-yellow-100 text-yellow-800 @break
                                        @case('CONFIRMED') bg-blue-100 text-blue-800 @break
                                        @case('ON_PROCESS') bg-purple-100 text-purple-800 @break
                                        @case('COMPLETED') bg-green-200 text-green-900 font-semibold @break
                                        @case('CANCELLED') bg-red-200 text-red-900 font-semibold @break
                                        @case('WAITING_DP') bg-orange-100 text-orange-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch">
                                    {{ $booking->status->status_code }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm {{ $isFinalStatus ? 'text-gray-500' : 'text-gray-600' }}">
                                {{ $booking->status->display_name }}
                            </td>
                            <td class="px-4 py-3 text-sm {{ $isFinalStatus ? 'text-gray-400' : 'text-gray-500' }}">
                                {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    @if($isFinalStatus)
                                        <span class="px-2 py-1 text-xs text-gray-500 italic">
                                            Status Final
                                        </span>
                                    @else
                                        <a href="{{ route('admin.status-booking.edit', $booking->id) }}" 
                                           class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs hover:bg-blue-200 transition-colors">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 text-sm">
                                <i class="fas fa-calendar-times text-2xl mb-2 block text-gray-300"></i>
                                <p>Tidak ada data booking</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
            <div class="px-4 py-2 border-t border-gray-200">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

{{-- CSRF Token untuk AJAX --}}
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Set CSRF token untuk semua AJAX request
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle status dropdown change
    $('.booking-status-dropdown').on('change', function() {
        const bookingId = $(this).data('booking-id');
        const newStatusId = $(this).val();
        const $dropdown = $(this);
        const originalValue = $dropdown.find('option[selected]').val();

        if (confirm('Apakah Anda yakin ingin mengubah status booking ini?')) {
            // Show loading state
            $dropdown.prop('disabled', true);
            $dropdown.addClass('opacity-50');
            
            $.ajax({
                url: /admin/status-booking/${bookingId}/update-status, // Sesuaikan dengan route yang baru
                method: 'PUT',
                data: { 
                    status_id: newStatusId 
                },
                success: function(response) {
                    // Update the current status data attribute
                    dropdown.data('current-status', newStatusId);
                    
                    // Update status badge in the table
                    updateStatusBadge(bookingId, response.status_code, response.status_display);
                    
                    // Show success notification
                    showNotification(Status berhasil diubah menjadi "${statusText}", 'success');
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseText);
                    
                    // Revert dropdown to original value
                    $dropdown.val(originalValue);
                    
                    let errorMsg = 'Gagal memperbarui status booking';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg += ': ' + xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        errorMsg += ': ' + xhr.responseText;
                    }
                    showAlert(errorMsg, 'error');
                },
                complete: function() {
                    // Re-enable dropdown
                    $dropdown.prop('disabled', false);
                    $dropdown.removeClass('opacity-50');
                }
            });
        } else {
            // Revert dropdown to original value if user cancels
            $dropdown.val(originalValue);
        }
    });

    // Helper function to show alerts
    function showAlert(message, type = 'info') {
        const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 
                          type === 'error' ? 'bg-red-100 border-red-400 text-red-700' : 
                          'bg-blue-100 border-blue-400 text-blue-700';
        
        const alertHtml = `
            <div class="${alertClass} border px-4 py-3 rounded mb-4 alert-dismissible" role="alert">
                <span class="block sm:inline">${message}</span>
                <button type="button" class="float-right ml-2 text-lg font-semibold leading-none" onclick="this.parentElement.remove()">&times;</button>
            </div>
        `;
        
        // Remove existing alerts
        $('.alert-dismissible').remove();
        
        // Insert alert at the top of the container
        $('.container').first().prepend(alertHtml);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            $('.alert-dismissible').fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    }
});
</script>
<div class="flex justify-end space-x-2 mt-4">
    <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm transition-colors"
            onclick="window.location.href='{{ route('admin.status-booking.index') }}'">
        <i class="fas fa-times mr-1"></i>Batal
    </button>
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm transition-colors">
        <i class="fas fa-save mr-1"></i>Simpan Perubahan
    </button>
</div>
@endpush

@push('scripts')
<script>
@push('scripts')
<script>
$(document).ready(function() {
    // Set CSRF token untuk semua AJAX request
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle save status button click
    $('.save-status-btn').on('click', function() {
        const btn = $(this);
        const bookingId = btn.data('booking-id');
        const dropdown = $(.booking-status-dropdown[data-booking-id="${bookingId}"]);
        const newStatusId = dropdown.val();
        const statusText = dropdown.find('option:selected').text();
        const currentStatusId = dropdown.data('current-status');
        
        // Check if status actually changed
        if (newStatusId == currentStatusId) {
            showAlert('Status tidak berubah', 'info');
            return;
        }
        
        // Show loading state
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Saving...');
        dropdown.prop('disabled', true);
        
        $.ajax({
            url: /admin/status-booking/${bookingId}/update-status,
            method: 'PUT',
            data: { 
                status_id: newStatusId 
            },
            success: function(response) {
                // Update the current status data attribute
                dropdown.data('current-status', newStatusId);
                
                // Update status badge in the table
                updateStatusBadge(bookingId, response.status_code, response.status_display);
                
                // Show success notification
                showAlert(Status berhasil diubah menjadi "${statusText}", 'success');
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr.responseText);
                
                // Revert dropdown to original value
                dropdown.val(currentStatusId);
                
                let errorMsg = 'Gagal mengubah status booking';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                showAlert(errorMsg, 'error');
            },
            complete: function() {
                // Reset button state
                btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Save');
                dropdown.prop('disabled', false);
            }
        });
    });
});
</script>
@endpush
@endpush