<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Menampilkan daftar layanan yang tersedia.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Service::with('category', 'provider')
            ->where('availbility', true);

        // Filter berdasarkan pencarian
        if ($request->search) {
            $query->where('title_service', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan kategori
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter berdasarkan harga minimum
        if ($request->min_price) {
            $query->where('base_price', '>=', $request->min_price);
        }

        // Filter berdasarkan harga maksimum
        if ($request->max_price) {
            $query->where('base_price', '<=', $request->max_price);
        }

        // Filter berdasarkan rating minimum
        if ($request->min_rating) {
            $query->where('rating_avg', '>=', $request->min_rating);
        }

        $services = $query->get();
        $categories = Category::all();

        return view('booking.index', compact('services', 'categories'));
    }

    /**
     * Menampilkan form booking.
     *
     * @param  int|null  $service
     * @return \Illuminate\View\View
     */
  
    public function create($service = null)
    {
        $selectedService = null;
        if ($service) {
            $selectedService = Service::findOrFail($service);
        }
        return view('booking.create', compact('selectedService'));

    }

    /**
     * Menyimpan data booking ke dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'service_id' => 'required|exists:services,id',
            'nama_pemesan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_handphone' => 'required|string|max:15',
            'tanggal_booking' => 'required|date|after:today',
            'waktu_booking' => 'required',
            'catatan_perbaikan' => 'required|string',
            'service_id' => 'required|exists:services,id'
        ]);

        Booking::create($validatedData);

        return redirect()->route('booking.index')->with('success', 'Booking berhasil disimpan!');

    }
}