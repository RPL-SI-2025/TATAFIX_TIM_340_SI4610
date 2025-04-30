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
        $validatedData = $request->validate([
            'nama_pemesan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_handphone' => 'required|string|max:15',
            'tanggal_booking' => 'required|date|after:today',
            'waktu_booking' => 'required',
            'catatan_perbaikan' => 'required|string',
        ]);

        Booking::create($validatedData);

        return redirect()->route('booking')->with('success', 'Booking berhasil disimpan!');
    }
}