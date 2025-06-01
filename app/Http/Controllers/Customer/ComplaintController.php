<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;

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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'attachment' => 'required|file|mimes:jpg,jpeg,png|max:5048',
        ], [
            'title.required' => 'Judul tidak boleh kosong',
            'description.required' => 'Deskripsi tidak boleh kosong',
            'attachment.required' => 'Harus mengunggah file pendukung',
            'attachment.mimes' => 'File harus berformat JPG atau PNG',
            'attachment.max' => 'Ukuran file maksimal 5MB',
        ]);

        $path = $request->file('attachment')->store('complaints', 'public');

        Complaint::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'evidence_file' => $path,
            'status' => 'menunggu_validasi',
        ]);

        return redirect()->route('customer.complaints.success')
                         ->with('success', 'Terima kasih! Pengaduan Anda telah berhasil dikirim.');
    }

    /**
     * Menampilkan halaman sukses pengaduan
     */
    public function success()
    {
        return view('pages.complaints.success');
    }
}
