<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\BookingStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // Existing validation and access checks...
    
        try {
            DB::beginTransaction();
    
            // Retrieve the "Menunggu Konfirmasi" status
            $pendingStatus = BookingStatus::where('status_code', 'PENDING')->first();
    
            // Create booking with pending status
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'service_id' => $request->service_id,
                'nama_pemesan' => $request->nama_pemesan,
                'alamat' => $request->alamat,
                'no_handphone' => $request->no_handphone,
                'tanggal_booking' => $request->tanggal_booking,
                'waktu_booking' => $request->waktu_booking,
                'catatan_perbaikan' => $request->catatan_perbaikan,
                'status_id' => $pendingStatus->status_id, // Set initial status
            ]);
    
            // Trigger status notification (which will handle email sending)
            $booking->sendStatusNotifications();
    
            DB::commit();
    
            return redirect()->route('booking.index')
                ->with('success', 'Booking berhasil disimpan dan notifikasi telah dikirim!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking creation failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat booking. Silakan coba lagi.');
        }
    }

    // Method to update booking status with notifications
    public function updateBookingStatus(Booking $booking, $newStatusCode)
    {
        try {
            DB::beginTransaction();
    
            $newStatus = BookingStatus::where('status_code', $newStatusCode)->first();
    
            if (!$newStatus) {
                throw new \Exception('Status tidak valid');
            }
    
            $booking->status_id = $newStatus->status_id;
            $booking->save();
    
            // Trigger status change notifications
            $booking->sendStatusNotifications();
    
            DB::commit();
    
            return redirect()->back()
                ->with('success', 'Status booking berhasil diperbarui dan notifikasi telah dikirim.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking status update failed: ' . $e->getMessage());
    
            return redirect()->back()
                ->with('error', 'Gagal memperbarui status booking.');
        }
    }
}