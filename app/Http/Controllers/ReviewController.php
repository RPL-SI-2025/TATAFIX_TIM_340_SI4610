<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display the review form for a specific booking.
     */
    public function index()
    {
        return view('pages.review.index');
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        
        // Check if the booking belongs to the authenticated user
        if (Auth::id() !== $booking->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);
        
        // Create the review
        Review::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'feedback' => $validated['feedback'],
            'status' => 'active',
        ]);
        
        return redirect()->route('bookings.show', $booking->id)
            ->with('success', 'Terima kasih! Feedback Anda telah berhasil dikirim.');
    }
    
    /**
     * For testing purposes - displays the review form without validation
     */
    public function test()
    {
        // This is just for testing the view without needing a real booking
        $booking = new \stdClass();
        $booking->id = 1;
        $booking->user_id = Auth::id();
        $booking->service = new \stdClass();
        $booking->service->title_service = "Bersih Rumah";
        $booking->service->duration = "2 Jam";
        $booking->service->label_unit = "pengerjaan";
        $booking->service->image = "cleaning.jpg";
        $booking->total_price = 350000;
        $booking->customer_name = "Keyra Renatha";
        $booking->phone_number = "082378203123";
        $booking->address = "Jalan Buah Batu No. 123, Buah Batu";
        $booking->city = "Kota Bandung, Jawa Barat";
        $booking->postal_code = "40265";
        $booking->notes = "-";
        $booking->created_at = now()->subDays(6);
        $booking->start_date = now()->subDays(3);
        $booking->finish_date = now()->subDays(1);
        $booking->completed_date = now();
        
        return view('pages.review.index', compact('booking'));
    }
}