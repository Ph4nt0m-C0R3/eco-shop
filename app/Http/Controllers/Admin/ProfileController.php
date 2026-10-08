<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // ===== ADMIN PROFILE =====
    public function profile()
    {
        return view('admin.profile.index');
    }



    // ===== UPDATE ADMIN PROFILE =====
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail(Auth::id());

        $user->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()
            ->route('admin#profile')
            ->with('success', 'Profile updated successfully.');

    }



    // ===== UPDATE ADMIN PROFILE AVATAR =====
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'profile' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Force Eloquent model
        $user = User::findOrFail(Auth::id());

        // Delete old avatar
        if ($user->profile && Storage::disk('public')->exists($user->profile)) {
            Storage::disk('public')->delete($user->profile);
        }

        // Store new avatar
        $path = $request->file('profile')->store('profiles', 'public');

        // Save new avatar
        $user->profile = $path;
        $user->save();

        return back()->with('success', 'Profile picture updated successfully.');
    }



    // ===== DELETE ADMIN PROFILE AVATAR =====
    public function deleteAvatar()
    {
        $user = User::findOrFail(Auth::id());

        if ($user->profile && Storage::disk('public')->exists($user->profile)) {
            Storage::disk('public')->delete($user->profile);
        }

        // Remove from DB
        $user->update([
            'profile' => null
        ]);

        return response()->json([
            'status' => 'success'
        ]);
    }
}
