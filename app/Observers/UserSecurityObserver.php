<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSecurityObserver
{
    public function updating(User $user): void
    {
        if ($user->isDirty(['password', 'email', 'role', 'two_factor_secret', 'auth_version'])) {
            $user->auth_version = (int) $user->getOriginal('auth_version') + 1;
            $user->remember_token = Str::random(60);
            if ($user->isDirty('password')) $user->password_updated_at = now();
            if ($user->isDirty('email')) $user->email_verified_at = null;
        }
    }

    public function updated(User $user): void
    {
        if (!$user->wasChanged(['password', 'email', 'role', 'two_factor_secret', 'auth_version'])) return;
        foreach (['password_reset_tokens', 'admin_password_reset_tokens'] as $table) {
            DB::table($table)->whereIn('email', [$user->email, $user->getOriginal('email')])->delete();
        }
        DB::table('password_challenges')->where('user_id', $user->id)->update([
            'code_hash' => null, 'grant_hash' => null, 'consumed_at' => now(),
        ]);
        if (config('session.driver') === 'database') {
            DB::connection(config('session.connection'))->table(config('session.table'))->where('user_id', $user->id)->delete();
        }
    }
}
