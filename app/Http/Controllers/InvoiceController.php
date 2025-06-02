<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        // Get all invoices for the current user or all invoices for admin
        if (Auth::user()->hasRole('admin')) {
            $invoices = Invoice::orderBy('created_at', 'desc')->paginate(10);
        } else {
            $invoices = Invoice::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(10);
        }
        
        return view('pages.invoices.index', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        // Check if user is authorized to view this invoice
        if (Auth::id() !== $invoice->user_id && !Auth::user()->hasRole('admin')) {
            return redirect()->route('invoices.index')->with('error', 'Anda tidak memiliki akses untuk melihat invoice ini.');
        }
        
        return view('pages.invoices.show', compact('invoice'));
    }

    public function generateFromBooking(Booking $booking)
    {
        // Verifikasi akses
        if (Auth::id() !== $booking->user_id && !Auth::user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk membuat invoice ini.');
        }

        // Cek apakah invoice sudah ada
        $existingInvoice = Invoice::where('booking_id', $booking->id)->first();
        if ($existingInvoice) {
            return redirect()->route('invoices.show', $existingInvoice->id);
        }

        // Buat invoice baru
        $invoice = new Invoice();
        $invoice->booking_id = $booking->id;
        $invoice->user_id = $booking->user_id;
        $invoice->invoice_number = 'INV-' . date('Ymd') . '-' . str_pad($booking->id, 4, '0', STR_PAD_LEFT);
        $invoice->nama_pemesan = $booking->nama_pemesan;
        $invoice->jenis_layanan = $booking->service_name;
        $invoice->down_payment = $booking->dp_amount;
        $invoice->biaya_pelunasan = $booking->final_amount;
        $invoice->total = $booking->dp_amount + $booking->final_amount;
        $invoice->status = $booking->final_paid_at ? 'paid' : 'pending';
        $invoice->tanggal_invoice = now();
        $invoice->save();

        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Invoice berhasil dibuat.');
    }

    public function download($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        // Check if user is authorized to download this invoice
        if (Auth::id() !== $invoice->user_id && !Auth::user()->hasRole('admin')) {
            return redirect()->route('invoices.index')->with('error', 'Anda tidak memiliki akses untuk mengunduh invoice ini.');
        }
        
        // Load booking relation if not already loaded
        if (!$invoice->relationLoaded('booking')) {
            $invoice->load('booking');
        }
        
        // Generate PDF
        $pdf = PDF::loadView('pages.invoices.pdf', compact('invoice'));
        
        // Set paper size to A4
        $pdf->setPaper('a4');
        
        // Download PDF with filename based on invoice number
        return $pdf->download('Invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function markAsPaid($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        // Only admin can mark invoice as paid
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengubah status invoice.');
        }
        
        $invoice->status = 'paid';
        $invoice->save();
        
        return redirect()->back()->with('success', 'Status invoice berhasil diubah menjadi Lunas.');
    }
}
