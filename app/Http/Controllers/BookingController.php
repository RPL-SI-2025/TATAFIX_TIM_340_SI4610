<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $services = Service::with('category')->get();
        
        return view('booking.index', compact('categories', 'services'));
    }

    public function create(Service $service)
    {
        return view('booking.create', compact('service'));
    }

    public function store(Request $request)
    {
        // Validasi input dari pengguna
        $validatedData = $request->validate([
            'nama_pemesan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_handphone' => 'required|string|max:15',
            'catatan_perbaikan' => 'required|string',
        ]);

        // Simpan data ke dalam tabel bookings
        Booking::create($validatedData);

        // Redirect kembali ke halaman booking dengan pesan sukses
        return redirect()->route('booking')->with('success', 'Booking berhasil disimpan!');
    }
}