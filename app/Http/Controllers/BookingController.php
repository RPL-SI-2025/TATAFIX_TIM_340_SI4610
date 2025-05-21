<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\BookingStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // Add this new index method
    public function index()
    {
        // Get all services with pagination
        $services = Service::with('category')
            ->when(request('search'), function($query) {
                return $query->where('title_service', 'like', '%' . request('search') . '%');
            })
            ->when(request('category_id'), function($query) {
                return $query->where('category_id', request('category_id'));
            })
            ->when(request('min_price'), function($query) {
                return $query->where('base_price', '>=', request('min_price'));
            })
            ->when(request('max_price'), function($query) {
                return $query->where('base_price', '<=', request('max_price'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(9);
        
        // Get all categories for the filter dropdown
        $categories = \App\Models\Category::all();
        
        // Return the view with both services and categories
        return view('pages.booking.index', compact('services', 'categories'));
    }
    
    public function store(Request $request)
    {
        // Existing validation and access checks...
    
        try {
            DB::beginTransaction();
    
            $pendingStatus = BookingStatus::where('status_code', 'PENDING')->first();
    
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'service_id' => $request->service_id,
                'nama_pemesan' => $request->nama_pemesan,
                'alamat' => $request->alamat,
                'no_handphone' => $request->no_handphone,
                'tanggal_booking' => $request->tanggal_booking,
                'waktu_booking' => $request->waktu_booking,
                'catatan_perbaikan' => $request->catatan_perbaikan,
                'status_id' => $pendingStatus->status_id, 
            ]);
    
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