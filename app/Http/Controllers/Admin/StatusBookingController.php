<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookingStatus;

class StatusBookingController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $bookings = BookingStatus::query()
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('customer', 'like', "%{$search}%");
            })
            ->orderByDesc('created_at')
            ->get();

        return view('pages.admin.status-booking.index', compact('bookings', 'status', 'search'));
    }

    public function edit($id)
    {
 
        $booking = BookingStatus::findOrFail($id);

        return view('pages.admin.status-booking.edit', compact('booking'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'status' => 'required|in:Pending,Selesai,Dibatalkan'
        ]);

        $booking = BookingStatus::findOrFail($id);

        if ($booking->status === $request->status) {
            return redirect()->route('admin.status-booking');
        }

        $booking->status = $request->status;
        $booking->save();
        return redirect()->route('admin.status-booking')->with('success', 'Status booking berhasil diperbarui.');
    }
}
