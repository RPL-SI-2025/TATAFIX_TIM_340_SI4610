<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\BookingStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Display form for down payment
     */
    public function showDpForm(Booking $booking)
    {
        // Pastikan user hanya bisa melihat booking miliknya
        if (Auth::id() !== $booking->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Pastikan booking dalam status yang tepat untuk pembayaran DP
        // Berdasarkan log, status awal booking adalah 'pending' (lowercase)
        $pendingStatus = BookingStatus::where('status_code', 'pending')->first();
            
        if (!$pendingStatus) {
            // Fallback ke status lain jika 'pending' tidak ditemukan
            $pendingStatus = BookingStatus::where('status_code', 'WAITING_DP')
                ->orWhere('status_code', 'PENDING_DP')
                ->first();
            
            if (!$pendingStatus) {
                \Log::error('Status booking untuk pembayaran DP tidak ditemukan', [
                    'available_statuses' => BookingStatus::pluck('status_code')->toArray()
                ]);
                return redirect()->route('booking.show', $booking->id)
                    ->with('error', 'Status booking tidak valid untuk pembayaran DP.');
            }
        }
        
        // Log status yang digunakan untuk debugging
        \Log::info('Status yang digunakan untuk pembayaran DP', [
            'status_code' => $pendingStatus->status_code,
            'status_id' => $pendingStatus->id,
            'booking_status_id' => $booking->status_id
        ]);
        
        if ($booking->status_id !== $pendingStatus->id) {
            return redirect()->route('booking.show', $booking->id)
                ->with('error', 'Booking ini tidak dalam status yang tepat untuk pembayaran DP.');
        }

        return view('pages.payment.dp.form', compact('booking'));
    }

    /**
     * Process down payment
     */
    public function processDp(Request $request, Booking $booking)
    {
        // Validasi input
        $request->validate([
            'payment_method' => 'required|in:bank_transfer',
            'amount' => 'required|numeric',
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'payment_notes' => 'nullable|string|max:500',
        ]);
        
        // Validasi jumlah DP harus 50% dari total biaya
        $expectedDpAmount = round($booking->service->base_price * 0.5);
        if ($request->amount != $expectedDpAmount) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jumlah DP harus 50% dari total biaya yaitu Rp ' . number_format($expectedDpAmount, 0, ',', '.'));
        }

        // Pastikan user hanya bisa membayar booking miliknya
        if (Auth::id() !== $booking->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Pastikan booking dalam status yang tepat untuk pembayaran DP
        // Berdasarkan log, status awal booking adalah 'pending' (lowercase)
        $pendingStatus = BookingStatus::where('status_code', 'pending')->first();
            
        if (!$pendingStatus) {
            // Fallback ke status lain jika 'pending' tidak ditemukan
            $pendingStatus = BookingStatus::where('status_code', 'WAITING_DP')
                ->orWhere('status_code', 'PENDING_DP')
                ->first();
            
            if (!$pendingStatus) {
                \Log::error('Status booking untuk pembayaran DP tidak ditemukan', [
                    'available_statuses' => BookingStatus::pluck('status_code')->toArray()
                ]);
                return redirect()->route('booking.show', $booking->id)
                    ->with('error', 'Status booking tidak valid untuk pembayaran DP.');
            }
        }
        
        // Log status yang digunakan untuk debugging
        \Log::info('Status yang digunakan untuk proses pembayaran DP', [
            'status_code' => $pendingStatus->status_code,
            'status_id' => $pendingStatus->id,
            'booking_status_id' => $booking->status_id
        ]);
        if ($booking->status_id !== $pendingStatus->id) {
            return redirect()->route('booking.show', $booking->id)
                ->with('error', 'Booking ini tidak dalam status yang tepat untuk pembayaran DP.');
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
                // Catatan: kolom payment_type tidak ada di tabel payments
                // Kita akan menggunakan jumlah pembayaran untuk membedakan DP dan pelunasan
            ]);

            // Update status booking menjadi "Menunggu Validasi DP"
            // Coba semua kemungkinan status code untuk validasi DP berdasarkan seeder
            $waitingDpValidationStatus = BookingStatus::where('status_code', 'waiting_validation_dp')
                ->orWhere('status_code', 'VALIDATING_DP')
                ->orWhere('status_code', 'WAITING_DP_VALIDATION')
                ->first();
                
            if (!$waitingDpValidationStatus) {
                // Log semua status yang tersedia untuk debugging
                $availableStatuses = BookingStatus::pluck('status_code')->toArray();
                \Log::error('Status booking untuk validasi DP tidak ditemukan', [
                    'available_statuses' => $availableStatuses
                ]);
                
                // Jika tidak ada status yang cocok, gunakan status pertama yang mengandung kata "validasi" atau "validation"
                $waitingDpValidationStatus = BookingStatus::where('status_code', 'like', '%validat%')
                    ->orWhere('display_name', 'like', '%validasi%')
                    ->first();
                    
                if (!$waitingDpValidationStatus) {
                    throw new \Exception('Status booking untuk validasi DP tidak ditemukan');
                }
            }
            
            // Debug status yang digunakan
            \Log::info('Menggunakan status booking: ' . $waitingDpValidationStatus->status_code, [
                'status_id' => $waitingDpValidationStatus->id,
                'display_name' => $waitingDpValidationStatus->display_name
            ]);
            
            $booking->status_id = $waitingDpValidationStatus->id;
            $booking->save();

            // Kirim notifikasi
            if (method_exists($booking, 'sendStatusNotifications')) {
                $booking->sendStatusNotifications();
            }

            DB::commit();

            return redirect()->route('payment.success', $booking->id)
                ->with('success', 'Bukti pembayaran DP berhasil diunggah. Status booking telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('DP payment processing failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memproses pembayaran DP. Silakan coba lagi.');
        }
    }

    /**
     * Display form for final payment
     */
    public function showFinalForm(Booking $booking)
    {
        // Pastikan user hanya bisa melihat booking miliknya
        if (Auth::id() !== $booking->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Dapatkan semua status yang valid untuk pelunasan
        $validStatuses = BookingStatus::where('status_code', 'waiting_pelunasan')
            ->orWhere('status_code', 'WAITING_FINAL_PAYMENT')
            ->orWhere('status_code', 'dp_validated')
            ->orWhere('status_code', 'done') // Tambahkan status 'done' untuk mengizinkan pelunasan setelah tukang selesai
            ->get();
            
        if ($validStatuses->isEmpty()) {
            // Log semua status yang tersedia untuk debugging
            $availableStatuses = BookingStatus::pluck('status_code')->toArray();
            \Log::error('Status booking untuk pelunasan tidak ditemukan', [
                'available_statuses' => $availableStatuses
            ]);
            
            return redirect()->route('booking.show', $booking->id)
                ->with('error', 'Status booking tidak valid untuk pelunasan.');
        }
        
        // Dapatkan semua ID status yang valid
        $validStatusIds = $validStatuses->pluck('id')->toArray();
        
        // Log status yang digunakan untuk debugging
        \Log::info('Status yang digunakan untuk pelunasan', [
            'valid_status_codes' => $validStatuses->pluck('status_code')->toArray(),
            'valid_status_ids' => $validStatusIds,
            'booking_status_id' => $booking->status_id,
            'booking_status_code' => $booking->status_code
        ]);
        
        // Periksa apakah status booking saat ini valid untuk pelunasan
        if (!in_array($booking->status_id, $validStatusIds)) {
            return redirect()->route('booking.show', $booking->id)
                ->with('error', 'Booking ini tidak dalam status yang tepat untuk pelunasan.');
        }

        return view('pages.payment.final.form', compact('booking'));
    }

    /**
     * Process final payment
     */
    public function processFinal(Request $request, Booking $booking)
    {
        // Validasi input
        $request->validate([
            'payment_method' => 'required|in:bank_transfer',
            'amount' => 'required|numeric',
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'payment_notes' => 'nullable|string|max:500',
        ]);
        
        // Hitung sisa pembayaran yang diharapkan (50% dari total biaya)
        // Asumsikan pembayaran pertama adalah DP
        $dpPayment = $booking->payments()->orderBy('created_at', 'asc')->first();
        $dpAmount = $dpPayment ? $dpPayment->amount : 0;
        $expectedFinalAmount = $booking->service->base_price - $dpAmount;
        
        // Log informasi pembayaran untuk debugging
        \Log::info('Informasi pembayaran pelunasan', [
            'booking_id' => $booking->id,
            'total_price' => $booking->service->base_price,
            'dp_amount' => $dpAmount,
            'expected_final_amount' => $expectedFinalAmount,
            'submitted_amount' => $request->amount
        ]);
        
        // Validasi jumlah pelunasan harus sesuai dengan sisa pembayaran
        if ($request->amount != $expectedFinalAmount) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jumlah pelunasan harus sesuai dengan sisa pembayaran yaitu Rp ' . number_format($expectedFinalAmount, 0, ',', '.'));
        }

        // Pastikan user hanya bisa membayar booking miliknya
        if (Auth::id() !== $booking->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Dapatkan semua status yang valid untuk pelunasan
        $validStatuses = BookingStatus::where('status_code', 'waiting_pelunasan')
            ->orWhere('status_code', 'WAITING_FINAL_PAYMENT')
            ->orWhere('status_code', 'dp_validated')
            ->orWhere('status_code', 'done') // Tambahkan status 'done' untuk mengizinkan pelunasan setelah tukang selesai
            ->get();
            
        if ($validStatuses->isEmpty()) {
            // Log semua status yang tersedia untuk debugging
            $availableStatuses = BookingStatus::pluck('status_code')->toArray();
            \Log::error('Status booking untuk pelunasan tidak ditemukan', [
                'available_statuses' => $availableStatuses
            ]);
            
            return redirect()->route('booking.show', $booking->id)
                ->with('error', 'Status booking tidak valid untuk pelunasan.');
        }
        
        // Dapatkan semua ID status yang valid
        $validStatusIds = $validStatuses->pluck('id')->toArray();
        
        // Periksa apakah status booking saat ini valid untuk pelunasan
        if (!in_array($booking->status_id, $validStatusIds)) {
            return redirect()->route('booking.show', $booking->id)
                ->with('error', 'Booking ini tidak dalam status yang tepat untuk pelunasan.');
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
                // Catatan: kolom payment_type tidak ada di tabel payments
            ]);

            // Update status booking menjadi "Menunggu Validasi Pelunasan"
            // Coba semua kemungkinan status code untuk validasi pelunasan berdasarkan seeder
            $waitingFinalValidationStatus = BookingStatus::where('status_code', 'waiting_validation_pelunasan')
                ->orWhere('status_code', 'VALIDATING_FINAL_PAYMENT')
                ->orWhere('status_code', 'WAITING_FINAL_VALIDATION')
                ->first();
                
            if (!$waitingFinalValidationStatus) {
                // Log semua status yang tersedia untuk debugging
                $availableStatuses = BookingStatus::pluck('status_code')->toArray();
                \Log::error('Status booking untuk validasi pelunasan tidak ditemukan', [
                    'available_statuses' => $availableStatuses
                ]);
                
                // Jika tidak ada status yang cocok, gunakan status pertama yang mengandung kata "validasi" atau "validation"
                $waitingFinalValidationStatus = BookingStatus::where('status_code', 'like', '%validat%')
                    ->orWhere('display_name', 'like', '%validasi%')
                    ->orWhere('status_code', 'like', '%final%')
                    ->orWhere('display_name', 'like', '%pelunasan%')
                    ->first();
                    
                if (!$waitingFinalValidationStatus) {
                    throw new \Exception('Status booking untuk validasi pelunasan tidak ditemukan');
                }
            }
            
            // Debug status yang digunakan
            \Log::info('Menggunakan status booking untuk pelunasan: ' . $waitingFinalValidationStatus->status_code, [
                'status_id' => $waitingFinalValidationStatus->id,
                'display_name' => $waitingFinalValidationStatus->display_name
            ]);
            
            $booking->status_id = $waitingFinalValidationStatus->id;
            $booking->save();

            // Kirim notifikasi
            if (method_exists($booking, 'sendStatusNotifications')) {
                $booking->sendStatusNotifications();
            }

            DB::commit();

            return redirect()->route('payment.success', $booking->id)
                ->with('success', 'Bukti pelunasan berhasil diunggah. Status booking telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Final payment processing failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memproses pelunasan. Silakan coba lagi.');
        }
    }

    /**
     * Display payment success page
     */
    public function success(Booking $booking)
    {
        // Pastikan user hanya bisa melihat booking miliknya
        if (Auth::id() !== $booking->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $payment = $booking->payments()->latest()->first();
        
        return view('pages.payment.success', compact('booking', 'payment'));
    }

    /**
     * Display payment status page
     */
    public function status(Booking $booking)
    {
        // Pastikan user hanya bisa melihat booking miliknya
        if (Auth::id() !== $booking->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $payments = $booking->payments()->orderBy('created_at', 'desc')->get();
        
        return view('pages.payment.status', compact('booking', 'payments'));
    }
}
