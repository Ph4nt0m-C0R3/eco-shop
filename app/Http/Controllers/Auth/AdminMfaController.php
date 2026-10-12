<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AdminMfa;
use App\Support\AccountSecurity;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

class AdminMfaController extends Controller
{
    public function setup(Request $request)
    {
        $user = $request->user();
        abort_unless($user->isAdmin(), 403);
        if ($user->two_factor_confirmed_at && !AdminMfa::verified($request, $user, 300)) return redirect()->route('admin.mfa.challenge');
        if (!$user->two_factor_confirmed_at && $request->session()->get('auth.password_confirmed_at', 0) < now()->subMinutes(10)->timestamp) {
            return app(AuthenticatedSessionController::class)->destroy($request);
        }
        $setup = $request->session()->get('mfa_setup', []);
        if (!isset($setup['expires'], $setup['identity']) || $setup['expires'] <= now()->timestamp
            || !hash_equals(AccountSecurity::fingerprint($user), $setup['identity'])) {
            $setup = ['secret' => (new Google2FA)->generateSecretKey(32), 'identity' => AccountSecurity::fingerprint($user), 'expires' => now()->addMinutes(5)->timestamp];
            $request->session()->put('mfa_setup', $setup);
        }
        return view('admin.auth.mfa.setup', ['secret' => $setup['secret']]);
    }

    public function enroll(Request $request, AdminMfa $mfa)
    {
        $request->validate(['password' => ['required', 'string', 'max:128'], 'code' => ['required', 'string', 'max:64']]);
        $codes = $mfa->enroll($request, $request->string('password')->toString(), $request->string('code')->toString());
        return redirect()->route('admin.mfa.security')->with('recovery_codes', $codes)->with('success', __('auth-security.mfa_enabled'));
    }

    public function challenge(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403);
        if (!$request->user()->two_factor_confirmed_at) return redirect()->route('admin.mfa.setup');
        return view('admin.auth.mfa.challenge');
    }

    public function verify(Request $request, AdminMfa $mfa)
    {
        $request->validate(['code' => ['nullable', 'string', 'max:64'], 'recovery_code' => ['nullable', 'string', 'max:64']]);
        $mfa->challenge($request, (string) $request->input('code', ''), (string) $request->input('recovery_code', ''));
        return redirect()->route('adminDashboard');
    }

    public function security(Request $request)
    {
        abort_unless(AdminMfa::verified($request, $request->user()), 403);
        return view('admin.auth.mfa.security');
    }

    public function regenerate(Request $request, AdminMfa $mfa)
    {
        $request->validate(['password' => ['required', 'string', 'max:128'], 'code' => ['required', 'string', 'max:64']]);
        $codes = $mfa->regenerateRecovery($request, $request->string('password')->toString(), $request->string('code')->toString());
        return redirect()->route('admin.mfa.security')->with('recovery_codes', $codes)->with('success', __('auth-security.codes_replaced'));
    }
}
