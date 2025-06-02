<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use App\Notifications\PaymentVerificationNotification;
use App\Services\NotificationService;

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
            // Simpan catatan admin sebagai log saja, karena tidak ada kolom admin_notes
            \Log::info('Admin notes for payment #' . $payment->id . ': ' . $request->notes);
            $payment->save();
            
            // Update booking status based on payment validation
            if ($request->status === 'validated') {
                // Determine next status based on current booking status
                $currentStatusCode = $payment->booking->status->status_code;
                
                if ($currentStatusCode == 'waiting_validation_dp') {
                    // If DP payment is validated, update booking status to "dp_validated"
                    $confirmedStatus = BookingStatus::where('status_code', 'dp_validated')->first();
                    if ($confirmedStatus) {
                        // Update booking status_id and status_code
                        $payment->booking->status_id = $confirmedStatus->id;
                        $payment->booking->status_code = 'dp_validated';
                        
                        // Log the status update
                        \Log::info('Booking #' . $payment->booking->id . ' status updated to dp_validated after DP validation');
                    }
                } else if ($currentStatusCode == 'waiting_validation_pelunasan') {
                    // If final payment is validated, update booking status to "completed"
                    $confirmedStatus = BookingStatus::where('status_code', 'completed')->first();
                    if ($confirmedStatus) {
                        $payment->booking->status_id = $confirmedStatus->id;
                        $payment->booking->status_code = 'completed';
                        
                        // Log the status update
                        \Log::info('Booking #' . $payment->booking->id . ' status updated to completed after final payment validation');
                    }
                } else {
                    // Fallback to current status if unexpected status
                    $confirmedStatus = $payment->booking->status;
                    \Log::warning('Unexpected booking status during payment validation: ' . $currentStatusCode);
                }
                
                if (!$confirmedStatus) {
                    throw new \Exception('Status booking tidak ditemukan');
                }
            } else {
                // If payment is rejected, update booking status based on current booking status
                $currentStatusCode = $payment->booking->status->status_code;
                
                if ($currentStatusCode == 'waiting_validation_dp') {
                    // If DP payment is rejected, set back to "pending"
                    $waitingPaymentStatus = BookingStatus::where('status_code', 'pending')->first();
                    if ($waitingPaymentStatus) {
                        $payment->booking->status_id = $waitingPaymentStatus->id;
                        $payment->booking->status_code = 'pending';
                        
                        // Log the status update
                        \Log::info('Booking #' . $payment->booking->id . ' status set back to pending after DP rejection');
                    }
                } else if ($currentStatusCode == 'waiting_validation_pelunasan') {
                    // If final payment is rejected, set back to "done"
                    $waitingPaymentStatus = BookingStatus::where('status_code', 'done')->first();
                    if ($waitingPaymentStatus) {
                        $payment->booking->status_id = $waitingPaymentStatus->id;
                        $payment->booking->status_code = 'done';
                        
                        // Log the status update
                        \Log::info('Booking #' . $payment->booking->id . ' status set back to done after final payment rejection');
                    }
                } else {
                    // Fallback to current status if unexpected status
                    $waitingPaymentStatus = $payment->booking->status;
                    \Log::warning('Unexpected booking status during payment rejection: ' . $currentStatusCode);
                }
                
                if (!$waitingPaymentStatus) {
                    throw new \Exception('Status booking tidak ditemukan');
                }
            }
            
            $payment->booking->save();
            
            // Generate invoice automatically after payment validation
            if ($request->status === 'validated') {
                // Check if invoice already exists for this booking
                $existingInvoice = Invoice::where('booking_id', $payment->booking->id)->first();
                
                if (!$existingInvoice) {
                    // Create new invoice for DP payment
                    if ($currentStatusCode == 'waiting_validation_dp') {
                        $this->createInvoice($payment->booking, 'dp');
                        \Log::info('DP Invoice generated for booking #' . $payment->booking->id);
                    }
                } else if ($currentStatusCode == 'waiting_validation_pelunasan') {
                    // Update existing invoice for final payment
                    $existingInvoice->status = 'paid';
                    $existingInvoice->save();
                    
                    // Create final invoice
                    $this->createInvoice($payment->booking, 'final');
                    \Log::info('Final Invoice generated for booking #' . $payment->booking->id);
                }
            }
            
            // Send notification
            $payment->booking->sendStatusNotifications();
            
            // Send payment verification notification to customer if payment is validated
            if ($request->status === 'validated' || $request->status === 'rejected') {
                $user = $payment->booking->user;
                if ($user) {
                    // Email notification
                    if ($request->status === 'validated') {
                        $user->notify(new PaymentVerificationNotification($payment->booking));
                    }
                    
                    // In-app notification
                    $notificationService = new NotificationService();
                    $notificationService->createPaymentVerification($user, $payment->booking, $request->status);
                    
                    // Log notification sent
                    \Log::info('Payment verification notification sent', [
                        'booking_id' => $payment->booking->id,
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'status' => $request->status
                    ]);
                }
            }
            
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
    
    /**
     * Create a new invoice for a booking
     * 
     * @param Booking $booking
     * @param string $type - 'dp' or 'final'
     * @return Invoice
     */
    private function createInvoice(Booking $booking, $type = 'dp')
    {
        $invoice = new Invoice();
        $invoice->booking_id = $booking->id;
        $invoice->user_id = $booking->user_id;
        $invoice->invoice_number = 'INV-' . date('Ymd') . '-' . str_pad($booking->id, 4, '0', STR_PAD_LEFT);
        if ($type === 'final') {
            $invoice->invoice_number .= '-FINAL';
        }
        
        $invoice->nama_pemesan = $booking->nama_pemesan;
        $invoice->jenis_layanan = $booking->service->name;
        $invoice->down_payment = $booking->dp_amount;
        $invoice->biaya_pelunasan = $booking->final_amount;
        $invoice->total = $booking->dp_amount + $booking->final_amount;
        
        // Set status based on type
        if ($type === 'dp') {
            $invoice->status = 'paid_dp';
        } else {
            $invoice->status = 'paid';
        }
        
        $invoice->tanggal_invoice = now();
        $invoice->save();
        
        return $invoice;
    }
}