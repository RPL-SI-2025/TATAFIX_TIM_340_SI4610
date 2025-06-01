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
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function accept(Booking $booking)
    {
        try {
            // Pastikan tukang hanya bisa menerima booking yang ditugaskan padanya
            if (Auth::id() != $booking->assigned_worker_id) {
                return redirect()->back()->with('error', 'Anda tidak berwenang untuk menerima penugasan ini.');
            }
            
            // Pastikan booking dalam status menunggu konfirmasi tukang
            if (strtolower($booking->status->status_code) != 'waiting_tukang_response') {
                return redirect()->back()->with('error', 'Booking ini tidak dalam status yang tepat untuk diterima.');
            }
            
            DB::beginTransaction();
            
            // Update status booking menjadi in_progress
            $inProgressStatus = BookingStatus::where('status_code', 'in_progress')->firstOrFail();
            $booking->status_id = $inProgressStatus->id;
            $booking->status_code = 'in_progress'; // Explicitly set status_code
            $booking->accepted_at = Carbon::now();
            
            // Log the update
            Log::info('Booking #' . $booking->id . ' accepted by tukang ID ' . Auth::id() . ' with status in_progress');
            
            $booking->save();
            
            DB::commit();
            
            return redirect()->route('tukang.bookings.show', $booking->id)
                ->with('success', 'Penugasan berhasil diterima. Silakan mulai pekerjaan sesuai jadwal.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error accepting booking: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menerima booking: ' . $e->getMessage());
        }
    }

    /**
     * Reject a booking assignment.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function reject(Booking $booking)
    {
        try {
            // Pastikan tukang hanya bisa menolak booking yang ditugaskan padanya
            if (Auth::id() != $booking->assigned_worker_id) {
                return redirect()->back()->with('error', 'Anda tidak berwenang untuk menolak penugasan ini.');
            }
            
            // Pastikan booking dalam status menunggu konfirmasi tukang
            if (strtolower($booking->status->status_code) != 'waiting_tukang_response') {
                return redirect()->back()->with('error', 'Booking ini tidak dalam status yang tepat untuk ditolak.');
            }
            
            DB::beginTransaction();
            
            // Reset assigned_worker_id and update status back to dp_validated
            // so admin can assign another tukang
            $dpValidatedStatus = BookingStatus::where('status_code', 'dp_validated')->firstOrFail();
            $booking->status_id = $dpValidatedStatus->id;
            $booking->status_code = 'dp_validated'; // Explicitly set status_code to lowercase
            $booking->assigned_worker_id = null;
            $booking->assigned_at = null;
            
            // Log the update
            Log::info('Booking #' . $booking->id . ' rejected by tukang ID ' . Auth::id() . '. Status reset to dp_validated');
            
            $booking->save();
            
            DB::commit();
            
            // Trigger notification if implemented
            // event(new BookingStatusChanged($booking));
            
            return redirect()->route('tukang.bookings.index')
                ->with('success', 'Penugasan berhasil ditolak. Admin akan menugaskan tukang lain.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting booking: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menolak penugasan: ' . $e->getMessage());
        }
    }

    /**
     * Mark a booking as completed.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function complete(Booking $booking)
    {
        try {
            // Pastikan tukang hanya bisa menyelesaikan booking yang ditugaskan padanya
            if (Auth::id() != $booking->assigned_worker_id) {
                return redirect()->back()->with('error', 'Anda tidak berwenang untuk menyelesaikan penugasan ini.');
            }
            
            // Pastikan booking dalam status sedang dikerjakan
            if (strtolower($booking->status->status_code) != 'in_progress') {
                return redirect()->back()->with('error', 'Booking ini tidak dalam status yang tepat untuk diselesaikan.');
            }
            
            DB::beginTransaction();
            
            // Update status to completed
            $completedStatus = BookingStatus::where('status_code', 'waiting_final_payment')->firstOrFail();
            $booking->status_id = $completedStatus->id;
            $booking->status_code = 'waiting_final_payment'; // Explicitly set status_code to lowercase
            $booking->completed_at = Carbon::now();
            
            // Log the update
            Log::info('Booking #' . $booking->id . ' marked as completed by tukang ID ' . Auth::id() . '. Status updated to waiting_final_payment');
            
            $booking->save();
            
            DB::commit();
            
            // Trigger notification if implemented
            // event(new BookingStatusChanged($booking));
            
            return redirect()->route('tukang.bookings.index')
                ->with('success', 'Penugasan berhasil diselesaikan. Pelanggan akan diminta untuk melakukan pembayaran akhir.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error completing booking: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyelesaikan penugasan: ' . $e->getMessage());
        }
    }
}
