<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Service;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori
     */
    public function index(Request $request)
    {
        $query = Category::query();
        
        // Filter berdasarkan pencarian jika ada
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        $categories = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('pages.admin.categories.index', compact('categories'));
    }
    
    /**
     * Menampilkan form tambah kategori
     */
    public function create()
    {
        return view('pages.admin.categories.create');
    }
    
    /**
     * Menyimpan kategori baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama kategori tidak boleh kosong',
            'name.unique' => 'Nama kategori sudah digunakan',
            'name.max' => 'Nama kategori maksimal 255 karakter',
        ]);
        
        // Buat kategori baru
        Category::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }
    
    /**
     * Menampilkan form edit kategori
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        
        return view('pages.admin.categories.edit', compact('category'));
    }
    
    /**
     * Mengupdate kategori
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id . ',category_id',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama kategori tidak boleh kosong',
            'name.unique' => 'Nama kategori sudah digunakan',
            'name.max' => 'Nama kategori maksimal 255 karakter',
        ]);
        
        // Update data kategori
        $category->name = $validated['name'];
        $category->description = $validated['description'] ?? null;
        $category->save();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }
    
    /**
     * Menghapus kategori
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Cek apakah kategori digunakan dalam layanan
        $usedInServices = Service::where('category_id', $id)->exists();
        
        if ($usedInServices) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena sedang digunakan dalam layanan');
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}