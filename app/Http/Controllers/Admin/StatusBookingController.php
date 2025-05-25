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
            ->paginate(10);
    
        $allBookingStatuses = BookingStatus::all();

        return view('pages.admin.status-booking.index', compact('bookingStatuses', 'bookings', 'allBookingStatuses'));
    }

    public function edit($id)
    {
        $bookingStatus = BookingStatus::findOrFail($id);
        return view('pages.admin.status-booking.edit', compact('bookingStatus'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status_code' => 'required|string|max:50',
            'display_name' => 'required|string|max:100',
        ]);

        $bookingStatus = BookingStatus::findOrFail($id);
        $bookingStatus->update([
            'status_code' => $request->status_code,
            'display_name' => $request->display_name,
        ]);

        return redirect()
            ->route('admin.status-booking.index')
            ->with('success', 'Status booking berhasil diperbarui');
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status_id' => $request->status_id]);

        $status = $booking->status;
        $statusHtml = view('pages.admin.status-booking._status_badge', compact('status'))->render();

        return response()->json([
            'message' => 'Status berhasil diperbarui',
            'status_html' => $statusHtml
        ]);
    }
}