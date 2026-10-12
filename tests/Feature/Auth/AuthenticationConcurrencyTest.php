<?php

namespace Tests\Feature\Auth;

use App\Models\PasswordChallenge;
use App\Models\User;
use App\Support\AccountSecurity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;
use Symfony\Component\Process\Process;
use Tests\TestCase;

class AuthenticationConcurrencyTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        // Check BEFORE DatabaseMigrations can touch a database.
        if (getenv('P0_03_MYSQL') !== 'true') {
            $this->markTestSkipped('Run the dedicated disposable MySQL security profile in CI.');
        }
        parent::setUp();
        $this->assertSame('ecoshop_security_test', config('database.connections.mysql.database'));
    }

    private function challenge(User $user, bool $verified = false): PasswordChallenge
    {
        return PasswordChallenge::create([
            'user_id' => $user->id, 'email' => $user->email, 'account_hash' => AccountSecurity::fingerprint($user),
            'session_hash' => hash('sha256', str_repeat('c', 40)), 'code_hash' => $verified ? null : Hash::make('345678'),
            'grant_hash' => $verified ? hash('sha256', 'isolated-concurrency-grant') : null,
            'expires_at' => now()->addMinutes(5), 'sent_at' => now(), 'attempts_at' => now(),
            'send_window_at' => now(), 'send_count' => 1, 'verified_at' => $verified ? now() : null,
        ]);
    }

    private function parallel(string $mode, User $user, int $challengeId = 0, string $input = ''): array
    {
        $directory = storage_path('framework/testing/auth-'.bin2hex(random_bytes(8)));
        mkdir($directory, 0700, true);
        $db = config('database.connections.mysql');
        $env = ['APP_ENV' => 'testing', 'DB_CONNECTION' => 'mysql', 'DB_HOST' => $db['host'], 'DB_PORT' => (string) $db['port'],
            'DB_DATABASE' => $db['database'], 'DB_USERNAME' => $db['username'], 'DB_PASSWORD' => $db['password'],
            'SESSION_DRIVER' => 'array', 'CACHE_STORE' => 'array', 'QUEUE_CONNECTION' => 'sync', 'MAIL_MAILER' => 'array',
            'ARGON_MEMORY' => '1024', 'ARGON_TIME' => '1'];
        $processes = [];
        try {
            foreach (['one', 'two'] as $marker) {
                $process = new Process([PHP_BINARY, base_path('tests/Support/auth-concurrency-worker.php'), $mode,
                    (string) $user->id, (string) $challengeId, $directory, $marker, $input], base_path(), $env);
                $process->setTimeout(15);
                $process->start();
                $processes[] = $process;
            }
            $deadline = microtime(true) + 10;
            while ((!is_file($directory.'/one.ready') || !is_file($directory.'/two.ready')) && microtime(true) < $deadline) usleep(10000);
            $this->assertFileExists($directory.'/one.ready');
            $this->assertFileExists($directory.'/two.ready');
            file_put_contents($directory.'/go', 'go');
            $results = [];
            foreach ($processes as $process) {
                $process->wait();
                $this->assertTrue($process->isSuccessful(), $process->getErrorOutput());
                $results[] = trim($process->getOutput());
            }
            return $results;
        } finally {
            foreach ($processes as $process) if ($process->isRunning()) $process->stop();
            foreach (['go', 'one.ready', 'two.ready'] as $file) if (is_file($directory.'/'.$file)) unlink($directory.'/'.$file);
            rmdir($directory);
        }
    }

    public function test_parallel_invalid_otps_cannot_lose_attempt_increments(): void
    {
        $user = User::factory()->create();
        $row = $this->challenge($user);
        $this->assertSame(['succeeded', 'succeeded'], $this->parallel('attempts', $user, $row->id));
        $this->assertSame(5, $row->fresh()->attempts);
    }
    public function test_parallel_verified_resets_consume_a_grant_once(): void
    {
        $user = User::factory()->create();
        $row = $this->challenge($user, true);
        $results = $this->parallel('reset', $user, $row->id, 'isolated-concurrency-grant');
        sort($results);
        $this->assertSame(['rejected', 'succeeded'], $results);
        $this->assertSame(2, $user->fresh()->auth_version);
        $this->assertNotNull($row->fresh()->consumed_at);
    }
    public function test_parallel_mfa_requests_cannot_reuse_one_totp(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $secret = (new Google2FA)->generateSecretKey(32);
        $user->forceFill(['two_factor_secret' => $secret, 'two_factor_confirmed_at' => now()])->save();
        $code = (new Google2FA)->getCurrentOtp($secret);
        $results = $this->parallel('mfa', $user, 0, $code);
        sort($results);
        $this->assertSame(['rejected', 'succeeded'], $results);
    }
}
