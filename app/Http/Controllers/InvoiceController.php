<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function download($id)
    {
        // This will be implemented later
        return redirect()->back()->with('info', 'Download feature will be implemented soon.');
    }

    public function generateFromBooking(Booking $booking)
    {
        // This will be implemented later
        return redirect()->back()->with('info', 'Generate invoice feature will be implemented soon.');
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
