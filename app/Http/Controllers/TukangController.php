<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tukang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TukangController extends Controller
{
    public function index()
    {
        $tukangs = Tukang::where('role_id', 3)->paginate(10);
        return view('tukang.index', compact('tukangs'));
    }

    public function create()
    {
        return view('tukang.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name'      => 'required|string|max:200',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'nullable|string|max:20',
            'address'   => 'nullable|string',
            'photo'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Menyimpan data tukang ke database
        $tukang = Tukang::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'address'   => $request->address,
            'role_id'   => 3, // role tukang
            'password'  => bcrypt('password123'), // default password
        ]);

        // Proses penyimpanan foto jika ada
        if ($request->hasFile('photo')) {
            try {
                $path = $request->file('photo')->store('photos', 'public');
                // Update path foto di database
                $tukang->update(['photo' => 'storage/' . $path]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal mengunggah foto: ' . $e->getMessage());
            }
        }

        return redirect()->route('tukang.index')->with('success', 'Tukang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $tukang = Tukang::findOrFail($id);
        return view('tukang.edit', compact('tukang'));
    }

    public function update(Request $request, $id)
    {
        $tukang = Tukang::findOrFail($id);

        // Validasi input
        $request->validate([
            'name'      => 'required|string|max:200',
            'email'     => 'required|email|unique:users,email,' . $id . ',user_id',
            'phone'     => 'nullable|string|max:20',
            'address'   => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

        ]);

        // Update data tukang
        $tukang->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'address'   => $request->address,
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',


        ]);

        // Proses update foto jika ada
        if ($request->hasFile('photo')) {
            try {
                // Hapus foto lama jika ada
                if ($tukang->photo && str_contains($tukang->photo, 'storage/')) {
                    $oldPath = str_replace('storage/', 'public/', $tukang->photo);
                    Storage::delete($oldPath);
                }

                // Simpan foto baru
                $path = $request->file('photo')->store('photos', 'public');
                // Update path foto di database
                $tukang->update(['photo' => 'storage/' . $path]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal mengunggah foto: ' . $e->getMessage());
            }
        }

        return redirect()->route('tukang.index')->with('success', 'Data tukang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tukang = Tukang::findOrFail($id);

        // Hapus foto jika ada
        if ($tukang->photo && str_contains($tukang->photo, 'storage/')) {
            $oldPath = str_replace('storage/', 'public/', $tukang->photo);
            Storage::delete($oldPath);
        }

        // Hapus data tukang
        $tukang->delete();

        return redirect()->route('tukang.index')->with('success', 'Tukang berhasil dihapus.');
    }
}
