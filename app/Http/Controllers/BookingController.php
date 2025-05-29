<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\BookingStatus;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display a listing of services for booking
     */
    public function index(Request $request)
    {
        $query = Service::with('category', 'provider')->where('availbility', true);

        if ($request->filled('search')) {
            $query->where('title_service', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        if ($request->filled('rating')) {
            $query->where('rating_avg', '>=', $request->rating);
        }

        $services = $query->paginate(10);
        $categories = Category::all();

        return view('pages.booking.index', compact('services', 'categories'));
    }

    public function store(Request $request)
    {
        // Periksa apakah user sudah login, email terverifikasi, dan memiliki role yang sesuai
        if (!auth()->check() || !auth()->user()->hasVerifiedEmail() || 
            !(auth()->user()->hasRole('customer') || auth()->user()->hasRole('admin'))) {
            return redirect()->route('booking.index')->with('error', 'Anda tidak memiliki akses untuk melakukan booking!');
        }

        // Validasi input dari pengguna
        $validatedData = $request->validate([
            'service_id' => 'required|exists:services,service_id',
            'nama_pemesan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_handphone' => 'required|string|max:15',
            'tanggal_booking' => 'required|date|after:today',
            'waktu_booking' => 'required',
            'catatan_perbaikan' => 'nullable|string',
        ]);

        // Set status awal booking (PENDING)
        $pendingStatus = BookingStatus::where('status_code', 'PENDING')->first();

        // Tambahkan user_id dan status ke data booking
        $validatedData['user_id'] = Auth::id();
        $validatedData['status_id'] = $pendingStatus->id;

        // Simpan data ke dalam tabel bookings
        $booking = Booking::create($validatedData);

        // Redirect ke halaman detail booking
        return redirect()->route('booking.success', $booking->id)
            ->with('success', 'Booking berhasil disimpan!');
        try {
            DB::beginTransaction();

            // Tambahkan user_id ke data booking
            $validatedData['user_id'] = auth()->id();
    
            $pendingStatus = BookingStatus::where('status_code', 'PENDING')->first();
            
            if (!$pendingStatus) {
                throw new \Exception('Status booking tidak ditemukan');
            }

            // Set status_id
            $validatedData['status_id'] = $pendingStatus->status_id;
    
            $booking = Booking::create($validatedData);
    
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

    public function userBooking(Booking $booking)
    {
        // Pastikan user hanya bisa melihat booking miliknya
        if (Auth::id() !== $booking->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('pages.booking.user-booking', compact('booking'));
    }

    public function showStatus($id)
    {
        // Pastikan user hanya bisa melihat booking miliknya
        $booking = Booking::where('user_id', Auth::id())
            ->findOrFail($id);

        return view('pages.booking.user-booking', compact('booking'));
    }

    public function userBookingHistory()
    {
        $bookings = Booking::where('user_id', Auth::id())->get();

        return view('pages.booking.user-booking-history', compact('bookings'));
    }

    public function userBookingHistoryDetail(Booking $booking)
    {
        return view('pages.booking.user-booking-history-detail', compact('booking'));
    }
}
