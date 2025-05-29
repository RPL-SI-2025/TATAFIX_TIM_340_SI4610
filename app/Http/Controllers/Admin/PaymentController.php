<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\BookingStatus;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['booking', 'booking.service', 'booking.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('pages.admin.payments.index', compact('payments'));
    }
    
    public function show(Payment $payment)
    {
        $payment->load(['booking', 'booking.service', 'booking.user', 'booking.status']);
        
        return view('pages.admin.payments.show', compact('payment'));
    }
    
    public function validate(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:validated,rejected',
            'notes' => 'nullable|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Update payment status
            $payment->status = $request->status;
            $payment->admin_notes = $request->notes;
            $payment->save();
            
            // Update booking status based on payment validation
            if ($request->status === 'validated') {
                // If payment is validated, update booking status to "Dikonfirmasi"
                $confirmedStatus = BookingStatus::where('status_code', 'CONFIRMED')->first();
                $payment->booking->status_id = $confirmedStatus->id;
            } else {
                // If payment is rejected, update booking status back to "Menunggu Pembayaran"
                $waitingPaymentStatus = BookingStatus::where('status_code', 'WAITING_PAYMENT')->first();
                $payment->booking->status_id = $waitingPaymentStatus->id;
            }
            
            $payment->booking->save();
            
            // Send notification
            $payment->booking->sendStatusNotifications();
            
            DB::commit();
            
            return redirect()->route('admin.payments.index')
                ->with('success', 'Validasi pembayaran berhasil dilakukan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment validation failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal memvalidasi pembayaran. Silakan coba lagi.');
        }
    }
}