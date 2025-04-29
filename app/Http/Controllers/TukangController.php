<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tukang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
            'password'  => bcrypt(Str::random(10)), // Generate a random password
        ]);

        // Proses penyimpanan foto jika ada
        if ($request->hasFile('photo')) {
            try {
                // Sanitasi nama file untuk mencegah konflik
                $photoFileName = Str::slug(pathinfo($request->file('photo')->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.' . $request->file('photo')->extension();
                $path = $request->file('photo')->storeAs('photos', $photoFileName, 'public');
                // Update path foto di database
                $tukang->update(['photo' => 'storage/' . $path]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal mengunggah foto: ' . $e->getMessage());
            }
        }

        // Menampilkan notifikasi sukses
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
        ]);

        // Proses update foto jika ada
        if ($request->hasFile('photo')) {
            try {
                // Hapus foto lama jika ada
                if ($tukang->photo && str_contains($tukang->photo, 'storage/')) {
                    $oldPath = str_replace('storage/', 'public/', $tukang->photo);
                    Storage::delete($oldPath);
                }

                // Sanitasi nama file foto
                $photoFileName = Str::slug(pathinfo($request->file('photo')->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.' . $request->file('photo')->extension();
                $path = $request->file('photo')->storeAs('photos', $photoFileName, 'public');
                // Update path foto di database
                $tukang->update(['photo' => 'storage/' . $path]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal mengunggah foto: ' . $e->getMessage());
            }
        }

        // Menampilkan notifikasi sukses
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

        // Menampilkan notifikasi sukses
        return redirect()->route('tukang.index')->with('success', 'Tukang berhasil dihapus.');
    }
}
