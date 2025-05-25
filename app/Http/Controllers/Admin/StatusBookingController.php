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
        $customer_name = $request->query('customer_name'); // Tambahkan parameter pencarian nama customer

        $bookingStatuses = BookingStatus::query()
            ->when($status_code, function ($query) use ($status_code) {
                $query->where('status_code', 'like', "%{$status_code}%");
            })
            ->when($display_name, function ($query) use ($display_name) {
                $query->where('display_name', 'like', "%{$display_name}%");
            })
            ->orderBy('id')
            ->paginate(10);
    
        // Tambahkan query untuk mendapatkan data bookings dengan filter nama customer
        $bookings = Booking::with(['user', 'service.provider', 'status', 'payments'])
            ->when($customer_name, function ($query) use ($customer_name) {
                $query->where('nama_pemesan', 'like', "%{$customer_name}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    
        // Ambil semua status booking untuk dropdown
        $allBookingStatuses = BookingStatus::all();

        return view('pages.admin.status-booking.index', compact('bookingStatuses', 'bookings', 'allBookingStatuses'));
    }

    /**
     * Update status booking via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status_id' => 'required|exists:booking_statuses,id',
        ]);

        $booking->status_id = $request->status_id;
        $booking->save();

        return response()->json(['message' => 'Status booking updated successfully.']);
    }

    public function edit($id)
    {
        $bookingStatus = BookingStatus::findOrFail($id);
        return view('pages.admin.status-booking.edit', compact('bookingStatus'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status_code' => 'required|unique:booking_statuses,status_code,'.$id,
            'display_name' => 'required'
        ]);

        $bookingStatus = BookingStatus::findOrFail($id);
        $bookingStatus->status_code = $request->status_code;
        $bookingStatus->display_name = $request->display_name;
        $bookingStatus->save();

        return redirect()->route('admin.status-booking.index')
            ->with('success', 'Status booking berhasil diperbarui.');
    }

    public function create()
    {
        // Fungsi create dinonaktifkan
        return redirect()->route('admin.status-booking.index')
            ->with('error', 'Pembuatan status baru tidak diizinkan.');
    }

    public function store(Request $request)
    {
        // Fungsi store dinonaktifkan
        return redirect()->route('admin.status-booking.index')
            ->with('error', 'Pembuatan status baru tidak diizinkan.');
    }
}
