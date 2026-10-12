<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\PasswordPolicy;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function create() { return view('auth.register'); }
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'], 'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => PasswordPolicy::rules(), 'phone' => ['required', 'string', 'min:8', 'max:30', 'regex:/^[0-9]+$/D'],
        ]);
        // The unique database constraint also handles simultaneous registrations.
        $user = User::firstOrCreate(['email' => $request->input('email')], [
            'name' => $request->input('name'), 'password' => Hash::make($request->input('password')),
            'phone' => $request->input('phone'), 'provider' => 'local',
        ]);
        if ($user->wasRecentlyCreated) event(new Registered($user));
        // Do not authenticate implicitly or reveal whether the address already exists.
        return redirect()->route('login')->with('status', __('auth-security.registration_received'));
    }
}
