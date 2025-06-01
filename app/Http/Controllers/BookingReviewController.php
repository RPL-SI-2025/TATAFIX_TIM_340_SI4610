<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingReviewController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('status', 'COMPLETED')
            ->whereNull('rating')
            ->get();
        return view('pages.booking.review-list', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        return view('pages.booking.review', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'required|string|max:1000'
        ]);

        $booking->update([
            'rating' => $validated['rating'],
            'feedback' => $validated['feedback']
        ]);

        return redirect()->route('review.index')
            ->with('success', 'Review berhasil disimpan!');
    }
}