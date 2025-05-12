<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $complaints = Complaint::with('user')
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($search, function ($query, $search) {
                $query->where('subject', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.admin.complaints.index', compact('complaints', 'status', 'search'));
    }

    public function show($id)
    {
        $complaint = Complaint::with(['user', 'validator'])->findOrFail($id);
        return view('pages.admin.complaints.show', compact('complaint'));
    }

    public function validate(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);

        // Cek apakah pengaduan masih dalam status menunggu validasi
        if (!$complaint->canBeValidated()) {
            return redirect()->back()->with('error', 'Pengaduan ini sudah divalidasi sebelumnya.');
        }

        // Cek apakah pengaduan memiliki bukti jika status valid
        if ($request->status === 'valid' && !$complaint->hasEvidence()) {
            return redirect()->back()->with('error', 'Pengaduan tanpa bukti tidak dapat divalidasi sebagai valid.');
        }

        $request->validate([
            'status' => 'required|in:valid,tidak_valid',
            'admin_notes' => 'required|string|min:10',
        ]);

        $complaint->status = $request->status;
        $complaint->admin_notes = $request->admin_notes;
        $complaint->validated_by = Auth::id();
        $complaint->validated_at = now();
        $complaint->save();

        return redirect()->route('admin.complaints.index')
            ->with('success', 'Pengaduan berhasil divalidasi.');
    }
}
