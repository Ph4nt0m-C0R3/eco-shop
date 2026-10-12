<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResetAdminMfa extends Command
{
    protected $signature = 'admin:mfa-reset {email} {--reason=} {--force}';
    protected $description = 'Operator recovery after verifying staff identity; revoke MFA, remember tokens and sessions';
    public function handle(): int
    {
        if (!is_string($this->option('reason')) || strlen(trim($this->option('reason'))) < 10) {
            $this->error('Provide a meaningful --reason after verifying the staff member through an independent channel.');
            return self::FAILURE;
        }
        if (!$this->option('force') && !$this->confirm('Independent identity verification is complete; invalidate all sessions and require MFA enrollment?')) return self::FAILURE;
        $id = DB::transaction(function () {
            $user = User::where('email', $this->argument('email'))->whereIn('role', ['admin', 'superadmin'])->lockForUpdate()->firstOrFail();
            $user->forceFill(['two_factor_secret' => null, 'two_factor_confirmed_at' => null,
                'two_factor_recovery_codes' => null, 'two_factor_last_step' => null,
                'two_factor_attempts' => 0, 'two_factor_attempts_at' => null, 'auth_version' => $user->auth_version + 1])->save();
            return $user->id;
        }, 3);
        Log::warning('Operator reset admin MFA', ['user_id' => $id, 'reason' => substr(trim($this->option('reason')), 0, 250)]);
        $this->info('MFA reset. Existing sessions and recovery links are invalid; the next password login requires enrollment.');
        return self::SUCCESS;
    }
}
