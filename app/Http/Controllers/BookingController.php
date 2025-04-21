<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Menampilkan form booking.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('booking');
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