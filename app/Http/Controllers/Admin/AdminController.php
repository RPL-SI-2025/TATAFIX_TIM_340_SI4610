<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Service;
use App\Models\Category;

class AdminController extends Controller
{
    public function index()
    {
        // Statistik
        $totalUsers = User::count();
        $totalTukang = User::role('tukang')->count();
        $totalCustomer = User::role('customer')->count();
        $totalAdmin = User::role('admin')->count();
        $totalServices = Service::count();
        $totalCategories = Category::count();
        $completedJobs = 1254; // Dummy, ganti dengan query jika ada tabel jobs

        // User terbaru
        $recentUsers = User::with('roles')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('pages.admin.dashboard', compact(
            'totalUsers', 'totalTukang', 'totalCustomer', 'totalAdmin',
            'totalServices', 'totalCategories', 'completedJobs', 'recentUsers'
        ));
    }

    
    public function verifyTukang($id)
    {
        $user = User::findOrFail($id);
        
        // Pastikan user adalah tukang
        if (!$user->hasRole('tukang')) {
            return redirect()->back()->with('error', 'Hanya tukang yang dapat diverifikasi.');
        }
        
        $user->is_verified = true;
        $user->save();
        
        return redirect()->route('admin.users')->with('success', 'Tukang berhasil diverifikasi!');
    }
    
    // Modifikasi method users untuk menambahkan filter verified
    public function users(Request $request)
    {
        $role = $request->query('role');
        $search = $request->query('search');
        $status = $request->query('status');
        $verified = $request->query('verified');
        
        $users = User::when($role, function($query, $role) {
                $query->role($role);
            })
            ->when($search, function($query, $search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->when($status, function($query, $status) {
                $query->where('status', $status);
            })
            ->when($verified !== null && $role === 'tukang', function($query) use ($verified) {
                $query->where('is_verified', $verified == '1');
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return view('pages.admin.users.index', compact('users', 'role', 'search', 'status', 'verified'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,tukang',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => 'active',
        ]);
        $user->assignRole($request->role);

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Cek apakah user yang sedang diedit adalah admin dan user yang login juga admin
        if ($user->hasRole('admin') && $user->id != auth()->id() && $request->has('status') && $request->status == 'inactive') {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan user dengan peran admin lainnya.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:active,inactive',
            'is_verified' => 'nullable|boolean',
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->status = $request->status;
        
        // Update status verifikasi jika user adalah tukang
        if ($user->hasRole('tukang') && $request->has('is_verified')) {
            $user->is_verified = $request->is_verified;
        }
        
        // Hapus foto jika diminta
        if ($request->has('delete_photo') && $user->photo) {
            Storage::disk('public')->delete($user->photo);
            $user->photo = null;
        }
        
        // Upload foto baru jika ada
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $photoPath = $request->file('photo')->store('photos', 'public');
            $user->photo = $photoPath;
        }
        
        $user->save();
        
        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        
        // Cek apakah user yang akan dinonaktifkan adalah admin dan user yang login juga admin
        if ($user->hasRole('admin') && $user->id != auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengubah status user dengan peran admin lainnya.');
        }
        
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();
        
        $statusText = $user->status === 'active' ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.users')->with('success', "User berhasil {$statusText}!");
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('pages.admin.users.edit', compact('user'));
    }
}
