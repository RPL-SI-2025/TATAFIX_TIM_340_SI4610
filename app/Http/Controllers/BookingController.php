<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;

class BookingController extends Controller
{
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

        return view('booking.index', compact('services', 'categories'));
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
            'service_id' => 'required|exists:services,id',
            'nama_pemesan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_handphone' => 'required|string|max:15',
            'tanggal_booking' => 'required|date|after:today',
            'waktu_booking' => 'required',
            'catatan_perbaikan' => 'required|string',
        ]);
    
        // Tambahkan user_id ke data booking
        $validatedData['user_id'] = auth()->id();
    
        // Simpan data ke dalam tabel bookings
        $booking = Booking::create($validatedData);
    
        // Redirect kembali ke halaman booking dengan pesan sukses
        return redirect()->route('booking.index')->with('success', 'Booking berhasil disimpan!');
    }
}