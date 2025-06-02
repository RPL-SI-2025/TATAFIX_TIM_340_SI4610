<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StatusBookingController extends Controller
{
    /**
     * Display a listing of booking statuses
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'service', 'status']);
        
        // Filter by customer name if provided
        if ($request->has('customer_name') && $request->customer_name) {
            $query->where('nama_pemesan', 'like', '%' . $request->customer_name . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->customer_name . '%');
                  });
        }
        
        // Filter by status if provided
        if ($request->has('booking_status') && $request->booking_status) {
            $query->whereHas('status', function($q) use ($request) {
                $q->where('status_code', $request->booking_status);
            });
        }
        
        $bookings = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());
            
        // Get all statuses for filter dropdown
        $allBookingStatuses = BookingStatus::all();
            
        return view('pages.admin.status-booking.index', compact('bookings', 'allBookingStatuses'));
    }

    /**
     * Store a newly created booking status
     */
    public function store(Request $request)
    {
        $request->validate([
            'status_code' => 'required|string|max:50|unique:booking_statuses',
            'display_name' => 'required|string|max:100',
            'color_code' => 'nullable|string|max:20',
            'requires_action' => 'boolean',
            'next_status' => 'nullable|string|max:50',
        ]);

        try {
            DB::beginTransaction();
            
            $status = new BookingStatus();
            $status->status_code = $request->status_code;
            $status->display_name = $request->display_name;
            $status->color_code = $request->color_code;
            $status->requires_action = $request->requires_action ?? false;
            $status->next_status = $request->next_status;
            $status->save();
            
            DB::commit();
            
            return redirect()->route('admin.status-booking.index')
                ->with('success', 'Status booking berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating booking status: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal menambahkan status booking. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Show the form for editing a booking status
     */
    public function edit($id)
    {
        $booking = Booking::with(['user', 'service', 'status'])->findOrFail($id);
        $allBookingStatuses = BookingStatus::all();
        
        return view('pages.admin.status-booking.edit', compact('booking', 'allBookingStatuses'));
    }

    /**
     * Update the specified booking status
     */
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $request->validate([
            'status_id' => 'required|exists:booking_statuses,id',
            'notes' => 'nullable|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            $booking->status_id = $request->status_id;
            $booking->notes = $request->notes;
            $booking->save();
            
            // Send notification if method exists
            if (method_exists($booking, 'sendStatusNotifications')) {
                $booking->sendStatusNotifications();
            }
            
            DB::commit();
            
            return redirect()->route('admin.status-booking.index')
                ->with('success', 'Status booking berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking status update failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal memperbarui status booking. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            $request->validate([
                'status_id' => 'required|exists:booking_statuses,id',
            ]);

            $booking->status_id = $request->status_id;
            $booking->save();
            
            // Send notification if method exists
            if (method_exists($booking, 'sendStatusNotifications')) {
                $booking->sendStatusNotifications();
            }
            
            DB::commit();
            
            return redirect()->route('admin.status-booking.edit', $booking->id)
                ->with('success', 'Status booking berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking status update failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal memperbarui status booking. Silakan coba lagi.');
        }
    }
}