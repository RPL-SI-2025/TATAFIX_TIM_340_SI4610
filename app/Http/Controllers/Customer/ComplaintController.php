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

            'evidence_file' => 'required|file|mimes:jpg,png|max:5048',

        ], [
            'title.required' => 'Judul tidak boleh kosong',
            'description.required' => 'Deskripsi tidak boleh kosong',
            'evidence_file.required' => 'Harus mengunggah file pendukung',
            'evidence_file.mimes' => 'File harus berformat JPG atau PNG',
            'evidence_file.max' => 'Ukuran file maksimal 5MB',

        ]);

        $path = $request->file('attachment')->store('complaints', 'public');

        Complaint::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'evidence_file' => $path,
            'status' => 'menunggu_validasi',
        ]);


        return redirect()->route('complaints.success')

            ->with('success', 'Terima kasih! Pengaduan Anda telah berhasil dikirim.');
    }

    /**
     * Menampilkan halaman sukses pengaduan
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
<<<<<<< HEAD
}
=======
}
>>>>>>> origin/main
