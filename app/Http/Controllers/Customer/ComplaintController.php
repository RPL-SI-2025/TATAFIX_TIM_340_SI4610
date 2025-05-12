<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{

    /**
     * Menampilkan form pengaduan
     */
    public function create()
    {
        return view('pages.complaints.create');
    }

    /**
     * Menyimpan pengaduan baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'evidence_file' => 'required|file|mimes:jpg,png|max:2048',
        ], [
            'subject.required' => 'Title tidak boleh kosong',
            'description.required' => 'Deskripsi tidak boleh kosong',
            'evidence_file.required' => 'Harus mengunggah file pendukung',
            'evidence_file.mimes' => 'File harus berformat JPG atau PNG',
            'evidence_file.max' => 'Ukuran file maksimal 2MB',
        ]);

        // Upload file
        $path = $request->file('evidence_file')->store('complaints', 'public');

        // Simpan pengaduan
        $complaint = Complaint::create([
            'user_id' => Auth::id(),
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'evidence_file' => $path,
            'status' => 'menunggu_validasi', // Sesuai dengan enum di migration
        ]);

        return redirect()->route('complaints.success')
            ->with('success', 'Terima kasih! Pengaduan Anda telah berhasil dikirim.');
    }

    /**
     * Menampilkan halaman sukses
     */
    public function success()
    {
        return view('pages.complaints.success');
    }

    /**
     * Menampilkan daftar pengaduan user
     */
    public function index()
    {
        $complaints = Complaint::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.complaints.index', compact('complaints'));
    }

    /**
     * Menampilkan detail pengaduan
     */
    public function show($id)
    {
        $complaint = Complaint::where('user_id', Auth::id())
            ->findOrFail($id);

        return view('pages.complaints.show', compact('complaint'));
    }
}