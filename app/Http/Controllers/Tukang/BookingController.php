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
        Log::info('Tukang ID ' . $tukang->id . ' accessing bookings index');
        
        // Get all bookings assigned to this tukang, regardless of status
        $bookings = Booking::where('assigned_worker_id', $tukang->id)
            ->with([
                'service', 
                'service.category',
                'user', 
                'status'
            ])
            ->latest()
            ->get();
        
        Log::info('Total bookings assigned to tukang: ' . $bookings->count());
        
        // Log detailed booking information for debugging
        $bookingsDetails = $bookings->map(function($booking) {
            return [
                'id' => $booking->id,
                'service' => $booking->service->title_service ?? 'Unknown',
                'status_id' => $booking->status_id,
                'status_code' => $booking->status_code,
                'status_name' => $booking->status->display_name ?? 'Unknown',
                'tanggal_booking' => $booking->tanggal_booking
            ];
        });
        
        Log::info('Bookings details:', $bookingsDetails->toArray());
        
        return view('pages.tukang.bookings.index', compact('bookings'));
    }
    
    /**
     * Display the specified booking details.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        $tukang = Auth::user();
        
        // Verify that this booking is assigned to the authenticated tukang
        if ($booking->assigned_worker_id != $tukang->id) {
            Log::warning('Tukang ID ' . $tukang->id . ' attempted to view booking ID ' . $booking->id . ' which is not assigned to them');
            return redirect()->route('tukang.bookings.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat booking ini.');
        }
        
        Log::info('Tukang ID ' . $tukang->id . ' viewing booking ID ' . $booking->id);
        
        // Load related data
        $booking->load(['service.category', 'user', 'status', 'payments']);
        
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
            
            // Update status to done (selesai).
            // Status ini sesuai dengan yang ada di BookingStatusSeeder
            $completedStatus = BookingStatus::where('status_code', 'done')->firstOrFail();
            $booking->status_id = $completedStatus->id;
            $booking->status_code = 'done'; // Explicitly set status_code to lowercase
            $booking->completed_at = Carbon::now();
            
            // Log the update
            Log::info('Booking #' . $booking->id . ' marked as completed by tukang ID ' . Auth::id() . '. Status updated to done');
            
            $booking->save();
            
            DB::commit();
            
            // Trigger notification if implemented
            // event(new BookingStatusChanged($booking));
            
            return redirect()->route('tukang.bookings.index')
                ->with('success', 'Penugasan berhasil diselesaikan. Pelanggan akan diminta untuk melakukan pelunasan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error completing booking: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyelesaikan penugasan: ' . $e->getMessage());
        }
    }
}
