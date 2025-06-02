<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\CustomerBookingConfirmation;
use App\Notifications\PaymentVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class TestMailController extends Controller
{
    public function testBookingConfirmation()
    {
        // Find a booking to use for testing
        $booking = Booking::with(['service', 'service.provider', 'user'])->first();
        
        if (!$booking) {
            return response()->json(['message' => 'No booking found for testing'], 404);
        }
        
        // Send notification to the booking's user
        $user = $booking->user;
        
        if ($user) {
            $user->notify(new CustomerBookingConfirmation($booking));
            return response()->json([
                'message' => 'Booking confirmation notification sent to ' . $user->email,
                'booking_id' => $booking->id
            ]);
        }
        
        // If no user is associated with the booking, send to a test email
        Notification::route('mail', 'test@example.com')
            ->notify(new CustomerBookingConfirmation($booking));
            
        return response()->json([
            'message' => 'Booking confirmation notification sent to test@example.com',
            'booking_id' => $booking->id
        ]);
    }
    
    public function testPaymentVerification()
    {
        // Find a booking to use for testing
        $booking = Booking::with(['service', 'service.provider', 'user'])->first();
        
        if (!$booking) {
            return response()->json(['message' => 'No booking found for testing'], 404);
        }
        
        // Send notification to the booking's user
        $user = $booking->user;
        
        if ($user) {
            $user->notify(new PaymentVerificationNotification($booking));
            return response()->json([
                'message' => 'Payment verification notification sent to ' . $user->email,
                'booking_id' => $booking->id
            ]);
        }
        
        // If no user is associated with the booking, send to a test email
        Notification::route('mail', 'test@example.com')
            ->notify(new PaymentVerificationNotification($booking));
            
        return response()->json([
            'message' => 'Payment verification notification sent to test@example.com',
            'booking_id' => $booking->id
        ]);
    }
}
