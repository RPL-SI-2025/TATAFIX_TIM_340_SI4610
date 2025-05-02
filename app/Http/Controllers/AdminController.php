<?php

namespace App\Http\Controllers;

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

        return view('admin.dashboard', compact(
            'totalUsers', 'totalTukang', 'totalCustomer', 'totalAdmin',
            'totalServices', 'totalCategories', 'completedJobs', 'recentUsers'
        ));
    }

    public function users(Request $request)
    {
        $role = $request->query('role');
        $search = $request->query('search');
        $users = User::when($role, function($query, $role) {
                $query->role($role);
            })
            ->when($search, function($query, $search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.users', compact('users', 'role', 'search'));
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

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit_user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->phone = $request->phone;
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

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }
}
