<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        // Use the authenticated user instead of the first user
        $user = Auth::user();
        
        if (!$user) {
            abort(404, 'User not found');
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
        // Use the authenticated user instead of the first user
        $user = Auth::user();
        
        if (!$user) {
            abort(404, 'User not found');
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Use the authenticated user instead of the first user
        $user = Auth::user();
    
        if (!$user) {
            abort(404, 'User not found');
        }
    
        // Handle profile image upload
        // In the update method
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            // Store new image
            $path = $request->file('profile_image')->store('profile-images', 'public');
            
            // Update the user's profile_image field
            User::where('id', $user->id)->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?? null,
                'profile_image' => $path
            ]);
        } else {
            // Update without changing the profile image
            User::where('id', $user->id)->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?? null
            ]);
        }
    
        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated successfully');
    }
}
