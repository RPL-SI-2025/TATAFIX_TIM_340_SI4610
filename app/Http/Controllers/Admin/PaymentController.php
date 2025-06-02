<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\Invoice;
use App\Models\User;
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
        
        // Debug log untuk melihat status booking saat ini
        \Log::info('Current booking status: ' . json_encode([
            'booking_id' => $payment->booking->id,
            'status_id' => $payment->booking->status_id,
            'status_code' => $payment->booking->status->status_code,
            'display_name' => $payment->booking->status->display_name
        ]));
        
        // Update booking status based on payment validation
        if ($request->status === 'validated') {
            // Determine next status based on current booking status
            $currentStatusCode = $payment->booking->status->status_code;
            $confirmedStatus = null;
            
            if ($currentStatusCode == 'waiting_validation_dp') {
                // If DP payment is validated, update booking status to "dp_validated"
                $confirmedStatus = BookingStatus::where('status_code', 'dp_validated')->first();
                if ($confirmedStatus) {
                    // Update booking status_id
                    $payment->booking->status_id = $confirmedStatus->id;
                    
                    // Log the status update
                    \Log::info('Booking #' . $payment->booking->id . ' status updated to dp_validated after DP validation');
                } else {
                    \Log::error('Could not find dp_validated status in database');
                    throw new \Exception('Status booking dp_validated tidak ditemukan');
                }
            } else if ($currentStatusCode == 'waiting_validation_pelunasan') {
                // If final payment is validated, update booking status to "completed"
                $confirmedStatus = BookingStatus::where('status_code', 'completed')->first();
                if ($confirmedStatus) {
                    $payment->booking->status_id = $confirmedStatus->id;
                    
                    // Log the status update
                    \Log::info('Booking #' . $payment->booking->id . ' status updated to completed after final payment validation');
                } else {
                    \Log::error('Could not find completed status in database');
                    throw new \Exception('Status booking completed tidak ditemukan');
                }
            } else {
                // Fallback to current status if unexpected status
                $confirmedStatus = $payment->booking->status;
                \Log::warning('Unexpected booking status during payment validation: ' . $currentStatusCode);
            }
        } else {
            // If payment is rejected, update booking status based on current booking status
            $currentStatusCode = $payment->booking->status->status_code;
            $waitingPaymentStatus = null;
            
            if ($currentStatusCode == 'waiting_validation_dp') {
                // If DP payment is rejected, set back to "pending"
                $waitingPaymentStatus = BookingStatus::where('status_code', 'pending')->first();
                if ($waitingPaymentStatus) {
                    $payment->booking->status_id = $waitingPaymentStatus->id;
                    
                    // Log the status update
                    \Log::info('Booking #' . $payment->booking->id . ' status set back to pending after DP rejection');
                } else {
                    \Log::error('Could not find pending status in database');
                    throw new \Exception('Status booking pending tidak ditemukan');
                }
            } else if ($currentStatusCode == 'waiting_validation_pelunasan') {
                // If final payment is rejected, set back to "done"
                $waitingPaymentStatus = BookingStatus::where('status_code', 'done')->first();
                if ($waitingPaymentStatus) {
                    $payment->booking->status_id = $waitingPaymentStatus->id;
                    
                    // Log the status update
                    \Log::info('Booking #' . $payment->booking->id . ' status set back to done after final payment rejection');
                } else {
                    \Log::error('Could not find done status in database');
                    throw new \Exception('Status booking done tidak ditemukan');
                }
            } else {
                // Fallback to current status if unexpected status
                $waitingPaymentStatus = $payment->booking->status;
                \Log::warning('Unexpected booking status during payment rejection: ' . $currentStatusCode);
            }
        }
        
        // Save the booking with updated status
        $payment->booking->save();
        
        // Debug log untuk melihat status booking setelah update
        \Log::info('Updated booking status: ' . json_encode([
            'booking_id' => $payment->booking->id,
            'status_id' => $payment->booking->status_id,
            'status_code' => $payment->booking->status->status_code,
            'display_name' => $payment->booking->status->display_name
        ]));
        
        // Generate invoice automatically after payment validation
        if ($request->status === 'validated') {
            try {
                // Check if invoice already exists for this booking
                $existingInvoice = Invoice::where('booking_id', $payment->booking->id)->first();
                
                if (!$existingInvoice) {
                    // Create new invoice for DP payment
                    if ($currentStatusCode == 'waiting_validation_dp') {
                        $invoice = $this->createInvoice($payment->booking, 'dp');
                        if ($invoice) {
                            \Log::info('DP Invoice generated for booking #' . $payment->booking->id);
                        } else {
                            \Log::warning('Failed to create DP invoice for booking #' . $payment->booking->id);
                        }
                    }
                } else {
                    // Update existing invoice for final payment
                    try {
                        $existingInvoice->status = 'paid';
                        $existingInvoice->save();
                        \Log::info('Existing invoice updated for booking #' . $payment->booking->id);
                    } catch (\Exception $e) {
                        \Log::error('Failed to update existing invoice: ' . $e->getMessage());
                    }
                    
                    // Create final invoice
                    $invoice = $this->createInvoice($payment->booking, 'final');
                    if ($invoice) {
                        \Log::info('Final Invoice generated for booking #' . $payment->booking->id);
                    } else {
                        \Log::warning('Failed to create final invoice for booking #' . $payment->booking->id);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Invoice generation failed: ' . $e->getMessage());
                // Continue even if invoice generation fails
            }
        }
        
        // Send notification if the method exists
        if (method_exists($payment->booking, 'sendStatusNotifications')) {
            try {
                $payment->booking->sendStatusNotifications();
            } catch (\Exception $e) {
                \Log::error('Notification sending failed: ' . $e->getMessage());
                // Continue even if notification fails
            }
        }
        
        DB::commit();
        
        return redirect()->route('admin.payments.index')
            ->with('success', 'Validasi pembayaran berhasil dilakukan.');
            
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Payment validation failed: ' . $e->getMessage() . '\nStack trace: ' . $e->getTraceAsString());
        
        return redirect()->back()
            ->with('error', 'Gagal memvalidasi pembayaran: ' . $e->getMessage());
    }
}
    
    /**
     * Create a new invoice for a booking
     * 
     * @param Booking $booking
     * @param string $type - 'dp' or 'final'
     * @return Invoice|null
     */
    private function createInvoice(Booking $booking, $type = 'dp')
    {
        try {
            // Get the customer data from User model
            $customer = User::find($booking->user_id);
            if (!$customer) {
                throw new \Exception('Customer not found with ID: ' . $booking->user_id);
            }
            
            // Generate invoice number
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad($booking->id, 4, '0', STR_PAD_LEFT);
            if ($type === 'final') {
                $invoiceNumber .= '-FINAL';
            }
            
            // Set status based on type
            $status = ($type === 'dp') ? 'paid_dp' : 'paid';
            
            // Use direct DB insertion to bypass model validation issues
            $invoiceId = DB::table('invoices')->insertGetId([
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'invoice_number' => $invoiceNumber,
                'nama_pemesan' => $customer->name ?? 'Customer',
                'no_handphone' => $customer->phone ?? '',  // This is nullable in the database
                'alamat' => $customer->address ?? 'Alamat tidak tersedia',  // This is required
                'jenis_layanan' => $booking->service->name ?? 'Layanan',
                'down_payment' => $booking->dp_amount ?? 0,
                'biaya_pelunasan' => $booking->final_amount ?? 0,
                'total' => ($booking->dp_amount ?? 0) + ($booking->final_amount ?? 0),
                'status' => $status,
                'tanggal_invoice' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            \Log::info('Invoice created successfully via DB: ' . json_encode([
                'invoice_id' => $invoiceId,
                'invoice_number' => $invoiceNumber,
                'customer_name' => $customer->name ?? 'Customer',
                'type' => $type
            ]));
            
            // Return the created invoice
            return Invoice::find($invoiceId);
            
        } catch (\Exception $e) {
            \Log::error('Failed to create invoice: ' . $e->getMessage() . '\nStack trace: ' . $e->getTraceAsString());
            // Don't throw the exception, just log it and return null
            return null;
        }
    }
}