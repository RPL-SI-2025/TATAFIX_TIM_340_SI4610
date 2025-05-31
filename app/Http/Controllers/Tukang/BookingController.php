<?php

namespace App\Http\Controllers\Tukang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingStatus;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of the tukang's bookings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tukang = Auth::user();
        
        // Get pending bookings (assigned to this tukang but not yet accepted)
        $pendingBookings = Booking::where('tukang_id', $tukang->id)
            ->whereHas('status', function($query) {
                $query->where('status_code', 'ASSIGNED');
            })
            ->with(['service', 'user', 'status'])
            ->latest()
            ->get();
        
        // Get active bookings (accepted and in process)
        $activeBookings = Booking::where('tukang_id', $tukang->id)
            ->whereHas('status', function($query) {
                $query->whereIn('status_code', ['IN_PROCESS']);
            })
            ->with(['service', 'user', 'status'])
            ->latest()
            ->get();
        
        // Get completed bookings (work completed, waiting payment or fully completed)
        $completedBookings = Booking::where('tukang_id', $tukang->id)
            ->whereHas('status', function($query) {
                $query->whereIn('status_code', ['WAITING_FINAL_PAYMENT', 'WAITING_FINAL_VALIDATION', 'COMPLETED']);
            })
            ->with(['service', 'user', 'status'])
            ->latest()
            ->get();
        
        return view('pages.tukang.bookings.index', compact('pendingBookings', 'activeBookings', 'completedBookings'));
    }

    /**
     * Display the specified booking.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tukang = Auth::user();
        
        $booking = Booking::where('id', $id)
            ->where('tukang_id', $tukang->id)
            ->with(['service.category', 'user', 'status', 'payments'])
            ->firstOrFail();
        
        return view('pages.tukang.bookings.show', compact('booking'));
    }

    /**
     * Accept a booking assignment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function accept($id)
    {
        $tukang = Auth::user();
        
        $booking = Booking::where('id', $id)
            ->where('tukang_id', $tukang->id)
            ->whereHas('status', function($query) {
                $query->where('status_code', 'ASSIGNED');
            })
            ->firstOrFail();
        
        // Update booking status to IN_PROCESS
        $inProcessStatus = BookingStatus::where('status_code', 'IN_PROCESS')->firstOrFail();
        $booking->status_id = $inProcessStatus->id;
        $booking->accepted_at = Carbon::now();
        $booking->save();
        
        // Trigger notification if implemented
        // event(new BookingStatusChanged($booking));
        
        return redirect()->route('tukang.bookings.show', $booking->id)
            ->with('success', 'Penugasan berhasil diterima. Silakan mulai pekerjaan sesuai jadwal.');
    }

    /**
     * Reject a booking assignment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject($id)
    {
        $tukang = Auth::user();
        
        $booking = Booking::where('id', $id)
            ->where('tukang_id', $tukang->id)
            ->whereHas('status', function($query) {
                $query->where('status_code', 'ASSIGNED');
            })
            ->firstOrFail();
        
        // Reset tukang_id and update status back to WAITING_TUKANG_ASSIGNMENT
        $waitingAssignmentStatus = BookingStatus::where('status_code', 'WAITING_TUKANG_ASSIGNMENT')->firstOrFail();
        $booking->status_id = $waitingAssignmentStatus->id;
        $booking->tukang_id = null;
        $booking->save();
        
        // Trigger notification if implemented
        // event(new BookingStatusChanged($booking));
        
        return redirect()->route('tukang.bookings.index')
            ->with('success', 'Penugasan berhasil ditolak. Admin akan menugaskan tukang lain.');
    }

    /**
     * Mark a booking as completed by tukang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function complete(Request $request, $id)
    {
        $tukang = Auth::user();
        
        $booking = Booking::where('id', $id)
            ->where('tukang_id', $tukang->id)
            ->whereHas('status', function($query) {
                $query->where('status_code', 'IN_PROCESS');
            })
            ->firstOrFail();
        
        // Update booking status to WAITING_FINAL_PAYMENT
        $waitingFinalPaymentStatus = BookingStatus::where('status_code', 'WAITING_FINAL_PAYMENT')->firstOrFail();
        $booking->status_id = $waitingFinalPaymentStatus->id;
        $booking->completed_at = Carbon::now();
        
        // Save completion notes if provided
        if ($request->has('completion_notes')) {
            $booking->completion_notes = $request->completion_notes;
        }
        
        $booking->save();
        
        // Trigger notification if implemented
        // event(new BookingStatusChanged($booking));
        
        return redirect()->route('tukang.bookings.show', $booking->id)
            ->with('success', 'Pekerjaan berhasil ditandai selesai. Pelanggan akan melakukan pelunasan pembayaran.');
    }
}
