<?php

namespace App\Http\Controllers\Tukang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $pendingBookings = Booking::where('assigned_worker_id', $tukang->id)
            ->whereHas('status', function($query) {
                $query->where('status_code', 'waiting_tukang_response');
            })
            ->with(['service', 'user', 'status'])
            ->latest()
            ->get();
        
        // Get active bookings (accepted and in progress)
        $activeBookings = Booking::where('assigned_worker_id', $tukang->id)
            ->whereHas('status', function($query) {
                $query->whereIn('status_code', ['in_progress', 'IN_PROGRESS']);
            })
            ->with(['service', 'user', 'status'])
            ->latest()
            ->get();
        
        // Get completed bookings (work completed, waiting payment or fully completed)
        $completedBookings = Booking::where('assigned_worker_id', $tukang->id)
            ->whereHas('status', function($query) {
                $query->whereIn('status_code', ['done', 'waiting_validation_pelunasan', 'completed']);
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
            ->where('assigned_worker_id', $tukang->id)
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
        
        try {
            DB::beginTransaction();
            
            $booking = Booking::where('id', $id)
                ->where('assigned_worker_id', $tukang->id)
                ->whereHas('status', function($query) {
                    $query->where('status_code', 'waiting_tukang_response');
                })
                ->firstOrFail();
            
            // Update booking status to in_progress
            $inProgressStatus = BookingStatus::where('status_code', 'in_progress')->first();
            if (!$inProgressStatus) {
                $inProgressStatus = BookingStatus::where('status_code', 'IN_PROGRESS')->firstOrFail();
            }
            $booking->status_id = $inProgressStatus->id;
            $booking->status_code = $inProgressStatus->status_code;
            $booking->accepted_at = Carbon::now();
            $booking->save();
            
            DB::commit();
            
            // Trigger notification if implemented
            // event(new BookingStatusChanged($booking));
            
            return redirect()->route('tukang.bookings.show', $booking->id)
                ->with('success', 'Penugasan berhasil diterima. Silakan mulai pekerjaan sesuai jadwal.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error accepting booking: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menerima penugasan. Silakan coba lagi.');
        }
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
        
        try {
            DB::beginTransaction();
            
            $booking = Booking::where('id', $id)
                ->where('assigned_worker_id', $tukang->id)
                ->whereHas('status', function($query) {
                    $query->where('status_code', 'waiting_tukang_response');
                })
                ->firstOrFail();
            
            // Reset assigned_worker_id and update status back to dp_validated
            // so admin can assign another tukang
            $dpValidatedStatus = BookingStatus::where('status_code', 'dp_validated')->first();
            if (!$dpValidatedStatus) {
                $dpValidatedStatus = BookingStatus::where('status_code', 'DP_VALIDATED')->firstOrFail();
            }
            $booking->status_id = $dpValidatedStatus->id;
            $booking->status_code = $dpValidatedStatus->status_code;
            $booking->assigned_worker_id = null;
            $booking->assigned_at = null;
            $booking->save();
            
            DB::commit();
            
            // Trigger notification if implemented
            // event(new BookingStatusChanged($booking));
            
            return redirect()->route('tukang.bookings.index')
                ->with('success', 'Penugasan berhasil ditolak. Admin akan menugaskan tukang lain.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting booking: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menolak penugasan. Silakan coba lagi.');
        }
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
        
        try {
            DB::beginTransaction();
            
            $booking = Booking::where('id', $id)
                ->where('assigned_worker_id', $tukang->id)
                ->whereHas('status', function($query) {
                    $query->whereIn('status_code', ['in_progress', 'IN_PROGRESS']);
                })
                ->firstOrFail();
            
            // Update booking status to done
            $doneStatus = BookingStatus::where('status_code', 'done')->first();
            if (!$doneStatus) {
                $doneStatus = BookingStatus::where('status_code', 'DONE')->firstOrFail();
            }
            $booking->status_id = $doneStatus->id;
            $booking->status_code = $doneStatus->status_code;
            $booking->completed_at = Carbon::now();
            
            // Save completion notes if provided
            if ($request->has('completion_notes')) {
                $booking->completion_notes = $request->completion_notes;
            }
            
            $booking->save();
            
            DB::commit();
            
            // Trigger notification if implemented
            // event(new BookingStatusChanged($booking));
            
            return redirect()->route('tukang.bookings.show', $booking->id)
                ->with('success', 'Pekerjaan berhasil ditandai selesai. Pelanggan akan melakukan pelunasan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error completing booking: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyelesaikan pekerjaan. Silakan coba lagi.');
        }
    }
}
