<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminBookingController extends Controller
{
    /**
     * Display a listing of all bookings for admin
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'service', 'status', 'tukang']);
        
        // Filter by status if provided
        if ($request->has('status') && $request->status != 'all') {
            $query->whereHas('status', function($q) use ($request) {
                $q->where('status_code', $request->status);
            });
        }
        
        // Filter by date range if provided
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $bookings = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());
            
        // Get all statuses for filter dropdown
        $statuses = BookingStatus::all();
            
        return view('pages.admin.bookings.index', compact('bookings', 'statuses'));
    }

    /**
     * Display the specified booking details for admin
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'service', 'status', 'payments']);
        $tukangList = User::role('tukang')->get();
        
        return view('pages.admin.bookings.show', compact('booking', 'tukangList'));
    }

    /**
     * Show the form for assigning a tukang to a booking
     */
    public function assignForm(Booking $booking)
    {
        // Check if booking is in the correct status for assignment
        if ($booking->status->status_code !== 'dp_validated') {
            return redirect()->route('admin.bookings.show', $booking->id)
                ->with('error', 'Booking ini tidak dalam status yang tepat untuk penugasan tukang.');
        }
        
        // Get available tukangs (with role 'tukang' and verified)
        $tukangs = User::role('tukang')
            ->where('email_verified_at', '!=', null)
            ->where('status', 'active')
            ->get();
            
        return view('pages.admin.bookings.assign', compact('booking', 'tukangs'));
    }
    
    /**
     * Show the form for assigning a tukang to a booking (by ID)
     */
    public function assignFormById($id)
    {
        $booking = Booking::findOrFail($id);
        return $this->assignForm($booking);
    }
    
    /**
     * Assign a tukang to a booking
     */
    public function assignStore(Request $request, Booking $booking)
    {
        $request->validate([
            'tukang_id' => 'required|exists:users,id',
        ]);

        try {
            DB::beginTransaction();
            
            // Check if booking is in the correct status for assignment
            if ($booking->status->status_code !== 'dp_validated') {
                throw new \Exception('Booking ini tidak dalam status yang tepat untuk penugasan tukang.');
            }
            
            // Update booking with assigned tukang
            $booking->assigned_worker_id = $request->tukang_id;
            
            // Update status to waiting_tukang_response
            $assignedStatus = BookingStatus::where('status_code', 'waiting_tukang_response')->first();
            if (!$assignedStatus) {
                // Fallback to in_progress if waiting_tukang_response not found
                $assignedStatus = BookingStatus::where('status_code', 'in_progress')->first();
                if (!$assignedStatus) {
                    throw new \Exception('Status booking tidak ditemukan');
                }
                Log::warning('Status waiting_tukang_response tidak ditemukan, menggunakan in_progress sebagai fallback');
            }
            
            // Update both status_id and status_code
            $booking->status_id = $assignedStatus->id;
            $booking->status_code = $assignedStatus->status_code; // Explicitly set status_code
            $booking->assigned_at = Carbon::now();
            
            // Log the update
            Log::info('Booking #' . $booking->id . ' assigned to tukang ID ' . $request->tukang_id . ' with status ' . $assignedStatus->status_code);
            
            $booking->save();
            
            // Send notification
            if (method_exists($booking, 'sendStatusNotifications')) {
                $booking->sendStatusNotifications();
            }
            
            DB::commit();
            
            return redirect()->route('admin.bookings.show', $booking->id)
                ->with('success', 'Tukang berhasil ditugaskan dan notifikasi telah dikirim.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Tukang assignment failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal menugaskan tukang: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing a booking
     */
    public function edit(Booking $booking)
    {
        $booking->load(['user', 'service', 'status']);
        $statuses = BookingStatus::all();
        $services = Service::all();
        
        return view('pages.admin.bookings.edit', compact('booking', 'statuses', 'services'));
    }
    
    /**
     * Update the specified booking
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'status_id' => 'required|exists:booking_statuses,id',
            'notes' => 'nullable|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            $booking->service_id = $request->service_id;
            $booking->status_id = $request->status_id;
            $booking->notes = $request->notes;
            $booking->save();
            
            DB::commit();
            
            return redirect()->route('admin.bookings.show', $booking->id)
                ->with('success', 'Booking berhasil diperbarui');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking update failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal memperbarui booking: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        try {
            DB::beginTransaction();
            
            $request->validate([
                'status_id' => 'required|exists:booking_statuses,id',
            ]);

            $booking->status_id = $request->status_id;
            $booking->save();
            
            // Send notification
            if (method_exists($booking, 'sendStatusNotifications')) {
                $booking->sendStatusNotifications();
            }
            
            DB::commit();
            
            return redirect()->route('admin.bookings.show', $booking->id)
                ->with('success', 'Status booking berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking status update failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal memperbarui status booking. Silakan coba lagi.');
        }
    }
    
    /**
     * Remove the specified booking from storage.
     */
    public function destroy(Booking $booking)
    {
        try {
            DB::beginTransaction();
            
            // Delete related records if necessary
            // For example: $booking->payments()->delete();
            
            // Delete the booking
            $booking->delete();
            
            DB::commit();
            return redirect()->route('admin.bookings.index')
                ->with('success', 'Booking berhasil dihapus.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting booking: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus booking. Silakan coba lagi.');
        }
    }
    
    /**
     * Cancel a booking
     */
    public function cancelBooking(Request $request, Booking $booking)
    {
        $request->validate([
            'cancel_reason' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            
            // Update status to CANCELLED
            $cancelledStatus = BookingStatus::where('status_code', 'CANCELLED')->first();
            if (!$cancelledStatus) {
                throw new \Exception('Status booking tidak ditemukan');
            }
            
            $booking->status_id = $cancelledStatus->id;
            $booking->cancelled_at = Carbon::now();
            $booking->cancel_reason = $request->cancel_reason;
            $booking->cancelled_by = Auth::id();
            $booking->save();
            
            // Send notification
            if (method_exists($booking, 'sendStatusNotifications')) {
                $booking->sendStatusNotifications();
            }
            
            DB::commit();
            
            return redirect()->route('admin.bookings.show', $booking->id)
                ->with('success', 'Booking berhasil dibatalkan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking cancellation failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal membatalkan booking. Silakan coba lagi.');
        }
    }
}
