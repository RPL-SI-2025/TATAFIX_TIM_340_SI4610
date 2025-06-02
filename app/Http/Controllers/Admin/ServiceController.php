<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Category;
use App\Models\Booking;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * Menampilkan daftar layanan
     */
    public function index(Request $request)
    {
        $query = Service::with('category', 'provider');
        
        // Filter berdasarkan kategori jika ada
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filter berdasarkan pencarian jika ada
        if ($request->has('search') && $request->search) {
            $query->where('title_service', 'like', '%' . $request->search . '%');
        }
        
        $services = $query->orderBy('created_at', 'desc')->paginate(10);
        $categories = Category::all();
        
        return view('pages.admin.services.index', compact('services', 'categories'));
    }
    
    /**
     * Menampilkan form tambah layanan
     */
    public function create()
    {
        $categories = Category::all();
        return view('pages.admin.services.create', compact('categories'));
    }
    
    /**
     * Menyimpan layanan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_service' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,category_id',
            'base_price' => 'required|numeric|min:0',
            'label_unit' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'title_service.required' => 'Judul layanan tidak boleh kosong',
            'description.required' => 'Deskripsi tidak boleh kosong',
            'category_id.required' => 'Kategori harus dipilih',
            'category_id.exists' => 'Kategori tidak valid',
            'base_price.required' => 'Harga dasar tidak boleh kosong',
            'base_price.numeric' => 'Harga dasar harus berupa angka',
            'base_price.min' => 'Harga dasar tidak boleh negatif',
            'label_unit.required' => 'Label unit tidak boleh kosong',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ]);
        
        // Upload gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('services', 'public');
        }
        
        // Buat layanan baru
        Service::create([
            'provider_id' => 1, // Default admin sebagai provider
            'title_service' => $validated['title_service'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'base_price' => $validated['base_price'],
            'label_unit' => $validated['label_unit'],
            'availbility' => true,
            'image_url' => $imagePath,
        ]);
        
        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan berhasil ditambahkan');
    }
    
    /**
     * Menampilkan form edit layanan
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $categories = Category::all();
        
        return view('pages.admin.services.edit', compact('service', 'categories'));
    }
    
    /**
     * Mengupdate layanan
     */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        
        $validated = $request->validate([
            'title_service' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,category_id',
            'base_price' => 'required|numeric|min:0',
            'label_unit' => 'required|string|max:50',
            'availbility' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Upload gambar baru jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($service->image_url) {
                Storage::disk('public')->delete($service->image_url);
            }
            
            $imagePath = $request->file('image')->store('services', 'public');
            $service->image_url = $imagePath;
        }
        
        // Update data layanan
        $service->title_service = $validated['title_service'];
        $service->description = $validated['description'];
        $service->category_id = $validated['category_id'];
        $service->base_price = $validated['base_price'];
        $service->label_unit = $validated['label_unit'];
        $service->availbility = $validated['availbility'];
        $service->save();
        
        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan berhasil diperbarui');
    }
    
    /**
     * Menghapus layanan
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        
        // Cek apakah layanan digunakan dalam booking aktif
        $activeBookings = Booking::where('service_id', $id)
            ->whereHas('status', function($query) {
                $query->whereNotIn('status_code', ['completed', 'cancelled']);
            })
            ->exists();
        
        if ($activeBookings) {
            return redirect()->route('admin.services.index')
                ->with('error', 'Layanan tidak dapat dihapus karena sedang digunakan dalam booking aktif');
        }
        
        // Hapus gambar jika ada
        if ($service->image_url) {
            Storage::disk('public')->delete($service->image_url);
        }
        
        $service->delete();
        
        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan berhasil dihapus');
    }
}