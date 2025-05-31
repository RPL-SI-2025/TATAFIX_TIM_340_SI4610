<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingStatus;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class StatusBookingController extends Controller
{
    public function index(Request $request)
    {
        $status_code = $request->query('status_code');
        $display_name = $request->query('display_name');
        $customer_name = $request->query('customer_name');
        $booking_status = $request->query('booking_status');

        $bookingStatuses = BookingStatus::query()
            ->when($status_code, function ($query) use ($status_code) {
                $query->where('status_code', 'like', "%{$status_code}%");
            })
            ->when($display_name, function ($query) use ($display_name) {
                $query->where('display_name', 'like', "%{$display_name}%");
            })
            ->orderBy('id')
            ->paginate(10);
    
        $bookings = Booking::with(['user', 'status'])
            ->when($customer_name, function ($query) use ($customer_name) {
                $query->where(function($q) use ($customer_name) {
                    $q->where('nama_pemesan', 'like', "%{$customer_name}%")
                      ->orWhereHas('user', function($q) use ($customer_name) {
                          $q->where('name', 'like', "%{$customer_name}%");
                      });
                });
            })
            ->when($booking_status, function ($query) use ($booking_status) {
                $query->whereHas('status', function($q) use ($booking_status) {
                    $q->where('status_code', $booking_status);
                });
            })
            ->orderBy('created_at', 'desc')
            ->select('bookings.*')
            ->paginate(10);
    
        $allBookingStatuses = BookingStatus::all();

        return view('pages.admin.status-booking.index', compact('bookingStatuses', 'bookings', 'allBookingStatuses'));
    }

    public function edit($id)
    {
        $booking = Booking::with(['user', 'service', 'status'])->findOrFail($id);
        $allBookingStatuses = BookingStatus::all();
        return view('pages.admin.status-booking.edit', compact('booking', 'allBookingStatuses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|exists:booking_statuses,id', // Changed from status_code to status_id and added exists validation
        ]);

        $booking = Booking::findOrFail($id);
        $booking->update([
            'status_id' => $request->status_id,
        ]);

        return redirect()
            ->route('admin.status-booking.index')
            ->with('success', 'Status booking berhasil diperbarui');
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        // Get the current status code of the booking
        $currentStatusCode = $booking->status->status_code;

        // Prevent status change if the current status is COMPLETED or CANCELLED
        if (in_array($currentStatusCode, ['COMPLETED', 'CANCELLED'])) {
            return response()->json([
                'message' => 'Status booking tidak dapat diubah karena sudah ' . $currentStatusCode,
                'status_html' => view('pages.admin.status-booking._status_badge', ['status' => $booking->status])->render()
            ], 400); // Bad Request
        }

        $booking->update(['status_id' => $request->status_id]);

        $status = $booking->status;
        $statusHtml = view('pages.admin.status-booking._status_badge', compact('status'))->render();

        return response()->json([
            'message' => 'Status berhasil diperbarui',
            'status_html' => $statusHtml
        ]);
    }
}