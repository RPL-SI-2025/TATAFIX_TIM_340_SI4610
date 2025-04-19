<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Menampilkan halaman booking dengan daftar layanan.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Service::with('category', 'provider')->where('availbility', true);

        if ($request->has('search')) {
            $query->where('title_service', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        if ($request->has('rating')) {
            $query->where('rating_avg', '>=', $request->rating);
        }

        $services = $query->paginate(10);
        $categories = Category::all();

        return view('booking.index', compact('services', 'categories'));
    }

    /**
     * Menyimpan data booking ke dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input dari pengguna
        $validatedData = $request->validate([
            'service_id' => 'required|exists:services,id',
            'nama_pemesan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'tanggal_booking' => 'required|date|after:today',
            'waktu_booking' => 'required|date_format:H:i',
            'catatan' => 'nullable|string'
        ]);

        // Buat booking baru
        $booking = Booking::create($validatedData);

        return redirect()->route('booking.success', $booking->id)
            ->with('success', 'Booking berhasil dibuat!');
    }
}