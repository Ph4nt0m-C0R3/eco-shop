<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // ===== USER PROFILE PAGE =====
    public function index()
    {
        return view('user.profile.index');
    }

    // ===== UPDATE USER PROFILE INFO =====
    public function update(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'phone'    => 'nullable|string|max:30',
            'address'  => 'nullable|string|max:255',
        ]);

        /** @var \App\Models\User $user */
        $user = User::findOrFail(Auth::id());

        $user->update([
            'name'     => $request->name,
            'nickname' => $request->nickname,
            'phone'    => $request->phone,
            'address'  => $request->address,
        ]);

        return redirect()
            ->route('user.profile')
            ->with('success', 'Profile updated successfully.');

    }

    // ===== UPDATE USER AVATAR =====
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'profile' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

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

    // ===== DELETE USER AVATAR =====
    public function deleteAvatar()
    {
        $user = User::findOrFail(Auth::id());

        if (
            $user->profile &&
            !str_starts_with($user->profile, 'http') &&
            Storage::disk('public')->exists($user->profile)
        ) {
            Storage::disk('public')->delete($user->profile);
        }

        $user->update([
            'profile' => null
        ]);

        return response()->json([
            'status' => 'success'
        ]);
    }
}
