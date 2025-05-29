<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\BookingStatus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function showPaymentForm(Booking $booking)
    {
        // Cek apakah booking milik user yang login
        if ($booking->user_id !== auth()->id()) {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'Anda tidak memiliki akses ke booking ini.');
        }

        // Cek apakah status booking adalah "Menunggu Pembayaran"
        $waitingPaymentStatus = BookingStatus::where('status_code', 'WAITING_PAYMENT')->first();
        if ($booking->status_id !== $waitingPaymentStatus->id) {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'Booking ini tidak dalam status menunggu pembayaran.');
        }

        return view('pages.customer.payments.form', compact('booking'));
    }

    public function processPayment(Request $request, Booking $booking)
    {
        // Validasi input
        $request->validate([
            'payment_method' => 'required|in:bank_transfer,e-wallet',
            'amount' => 'required|numeric|min:1',
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'payment_notes' => 'nullable|string|max:500',
        ]);

        // Cek apakah booking milik user yang login
        if ($booking->user_id !== auth()->id()) {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'Anda tidak memiliki akses ke booking ini.');
        }

        // Cek apakah status booking adalah "Menunggu Pembayaran"
        $waitingPaymentStatus = BookingStatus::where('status_code', 'WAITING_PAYMENT')->first();
        if ($booking->status_id !== $waitingPaymentStatus->id) {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'Booking ini tidak dalam status menunggu pembayaran.');
        }

        try {
            DB::beginTransaction();

            // Upload bukti pembayaran
            $proofPath = null;
            if ($request->hasFile('proof_of_payment')) {
                $proofPath = $request->file('proof_of_payment')->store('payment_proofs', 'public');
            }

            // Buat record pembayaran
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => $request->payment_method,
                'amount' => $request->amount,
                'status' => 'pending',
                'proof_of_payment' => $proofPath,
                'payment_notes' => $request->payment_notes,
            ]);

            // Update status booking menjadi "Menunggu Validasi"
            $validationStatus = BookingStatus::where('status_code', 'WAITING_VALIDATION')->first();
            $booking->status_id = $validationStatus->id;
            $booking->save();

            // Kirim notifikasi
            $booking->sendStatusNotifications();

            DB::commit();

            return redirect()->route('customer.payments.success', $booking->id)
                ->with('success', 'Bukti pembayaran berhasil diunggah. Status booking telah diperbarui menjadi Menunggu Validasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment processing failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memproses pembayaran. Silakan coba lagi.');
        }
    }

    public function paymentSuccess(Booking $booking)
    {
        // Cek apakah booking milik user yang login
        if ($booking->user_id !== auth()->id()) {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'Anda tidak memiliki akses ke booking ini.');
        }

        return view('pages.customer.payments.success', compact('booking'));
    }

    public function paymentStatus(Booking $booking)
    {
        // Cek apakah booking milik user yang login
        if ($booking->user_id !== auth()->id()) {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'Anda tidak memiliki akses ke booking ini.');
        }

        $payment = $booking->payments()->latest()->first();

        return view('pages.customer.payments.status', compact('booking', 'payment'));
    }
}