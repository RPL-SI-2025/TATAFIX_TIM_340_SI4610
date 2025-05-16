<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Category;
use App\Models\BookingStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();
        $categories = Category::all();
        
        // Filter by search
        if ($request->has('search') && $request->search) {
            $query->where('title_service', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }
        
        // Filter by price range
        if ($request->has('min_price') && $request->min_price) {
            $query->where('base_price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && $request->max_price) {
            $query->where('base_price', '<=', $request->max_price);
        }
        
        // Filter by rating
        if ($request->has('min_rating') && $request->min_rating) {
            $query->where('rating_avg', '>=', $request->min_rating);
        }
        
        // Filter by service_id if provided (for direct linking)
        if ($request->has('service_id') && $request->service_id) {
            $query->where('service_id', $request->service_id);
        }
        
        $services = $query->where('availbility', true)
                         ->orderBy('rating_avg', 'desc')
                         ->paginate(9);
        
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