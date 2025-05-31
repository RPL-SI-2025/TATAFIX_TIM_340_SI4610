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
    // index method removed - using ServiceController@index instead

    /**
     * Show the form for creating a new booking
     */
    public function create(Service $service)
    {
        return view('pages.booking.create', compact('service'));
    }

    /**
     * Store a newly created booking in storage
     */
    public function store(Request $request)
    {
        // Debug: Log semua input yang diterima
        \Log::info('Booking request received', $request->all());
        
        // Periksa apakah user sudah login, email terverifikasi, dan memiliki role yang sesuai
        if (!auth()->check() || !auth()->user()->hasVerifiedEmail() || 
            !(auth()->user()->hasRole('customer') || auth()->user()->hasRole('admin'))) {
            \Log::warning('User tidak memiliki akses untuk booking', [
                'user_id' => auth()->id(),
                'verified' => auth()->check() ? auth()->user()->hasVerifiedEmail() : false,
                'roles' => auth()->check() ? auth()->user()->getRoleNames() : []
            ]);
            return redirect()->route('services.index')->with('error', 'Anda tidak memiliki akses untuk melakukan booking!');
        }

        try {
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
            
            \Log::info('Validation passed', $validatedData);
            
            DB::beginTransaction();

            // Ambil data service untuk mendapatkan service_name
            $service = Service::where('service_id', $validatedData['service_id'])->first();
            if (!$service) {
                throw new \Exception('Service tidak ditemukan');
            }
            
            // Buat data booking sesuai dengan kolom yang ada di tabel
            $bookingData = [
                'user_id' => auth()->id(),
                'service_id' => $validatedData['service_id'],
                'nama_pemesan' => $validatedData['nama_pemesan'],
                'service_name' => $service->title_service,
                'tanggal_booking' => $validatedData['tanggal_booking'],
                'waktu_booking' => $validatedData['waktu_booking'],
                'catatan_perbaikan' => $validatedData['catatan_perbaikan'] ?? null
            ];
            
            // Simpan data tambahan di session untuk digunakan di halaman payment
            session([
                'booking_alamat' => $validatedData['alamat'],
                'booking_no_handphone' => $validatedData['no_handphone']
            ]);

            // Set status awal booking (pending)
            // Sesuai dengan flow yang diberikan, status awal adalah 'pending'
            $pendingStatus = BookingStatus::where('status_code', 'pending')->first();
            
            if (!$pendingStatus) {
                \Log::error('Status booking "pending" tidak ditemukan', [
                    'available_statuses' => BookingStatus::pluck('status_code')->toArray()
                ]);
                throw new \Exception('Status booking "pending" tidak ditemukan. Pastikan BookingStatusSeeder telah dijalankan.');
            }

            // Set status_id dan status_code
            $bookingData['status_id'] = $pendingStatus->id;
            $bookingData['status_code'] = $pendingStatus->status_code;
            
            \Log::info('Creating booking with data', $bookingData);

            $booking = Booking::create($bookingData);
            \Log::info('Booking created successfully', ['booking_id' => $booking->id]);

            // Kirim notifikasi jika method tersedia
            if (method_exists($booking, 'sendStatusNotifications')) {
                $booking->sendStatusNotifications();
            }

            DB::commit();

            // Redirect ke halaman pembayaran DP
            \Log::info('Redirecting to payment form', ['booking_id' => $booking->id]);
            return redirect()->route('payment.dp.form', $booking->id)
                ->with('success', 'Booking berhasil dibuat! Silakan lakukan pembayaran DP.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat booking: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking)
    {
        // Pastikan user hanya bisa melihat booking miliknya
        if (Auth::id() !== $booking->user_id && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $booking->load(['service', 'status', 'payments']);
        
        return view('pages.booking.show', compact('booking'));
    }

    /**
     * Display booking success page after creation
     */
    public function success(Booking $booking)
    {
        // Pastikan user hanya bisa melihat booking miliknya
        if (Auth::id() !== $booking->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('pages.booking.success', compact('booking'));
    }

    /**
     * Display user's booking history
     */
    public function history()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['service', 'status'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.booking.history', compact('bookings'));
    }
}
