<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;

class RoleRedirect
{
    public static function handle()
    {
        $user = Auth::user();

        return match ($user->role) {
            'superadmin', 'admin' => redirect()->route('adminDashboard'),
            'user'               => redirect()->route('userHome'),
            default              => abort(403),
        };
    }
}
