<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\AccountSecurity;
use App\Support\PasswordPolicy;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function update(Request $request)
    {
        abort_unless($request->user()->provider === 'local', 403);
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'string', 'max:128', 'current_password'],
            'password' => PasswordPolicy::rules(),
        ]);
        $user = AccountSecurity::changePassword($request->user(), $validated['password']);
        AccountSecurity::keepCurrentSession($request, $user);
        return back()->with('status', 'password-updated');
    }
}
