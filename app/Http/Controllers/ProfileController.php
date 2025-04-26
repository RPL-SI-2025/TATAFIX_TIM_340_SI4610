<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        // Using dummy user for development
        $user = Auth::user();
        
        if (!$user) {
            $user = User::create([
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'phone' => '081234567890',
                'profile_image' => null,
                'password' => bcrypt('password'),
                'role_id' => 2, // Assuming 2 is for customer role
            ]);
        }
        
        return view('profile.show', compact('user'));
    }

    /**
     * Show the form for editing the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        // Using dummy user for development
        $user = Auth::user();
        
        if (!$user) {
            $user = User::create([
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'phone' => '081234567890',
                'profile_image' => null,
                'password' => bcrypt('password'),
                'role_id' => 2, // Assuming 2 is for customer role
            ]);
        }
        
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            $user = User::create([
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'phone' => '081234567890',
                'profile_image' => null,
                'password' => bcrypt('password'),
                'role_id' => 2, // Assuming 2 is for customer role
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['nullable', 'string', 'regex:/^[0-9]+$/', 'max:20'],
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi',
            'phone.regex' => 'Format nomor telepon tidak valid',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $user->profile_image = $path;
        }
        
        $user->name = $validated['name'];
        $user->phone = $validated['phone'] ?? null;
        $user->save();

        return redirect()->route('profile.edit')
            ->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Show the form for changing the user's password.
     *
     * @return \Illuminate\View\View
     */
    public function changePasswordForm()
    {
        // Gunakan user yang sedang login
        $user = Auth::user();
        return view('profile.change-password', compact('user'));
    }

    /**
     * Handle the password change request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        // Gunakan user yang sedang login
        $user = \App\Models\User::find(Auth::id());
        
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password baru minimal 6 karakter',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok',
        ]);

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama salah')->withInput();
        }

        // Update password
        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Password berhasil diubah');
    }

    /**
     * Show the form for resetting password (forgot password).
     *
     * @return \Illuminate\View\View
     */
    public function resetPasswordForm()
    {
        return view('auth.reset-password');
    }

    /**
     * Handle the reset password request (forgot password).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'username' => 'required', // Ganti sesuai field unik user Anda, misal 'nim' atau 'email'
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'username.required' => 'Username/NIM wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password baru minimal 6 karakter',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok',
        ]);

        // Ganti pencarian user sesuai field unik Anda
        $user = \App\Models\User::where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors(['username' => 'User tidak ditemukan']);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }
}
