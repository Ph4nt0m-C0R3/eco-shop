# P0-03 — authentication and recovery hardening

Started: 12 October 2026. Implementation is under verification. This phase covers local/password authentication, OTP/email recovery, session revocation and mandatory admin MFA. P0-04 OAuth changes and later phases remain pending.

## Passwords and recovery

New passwords use one policy across registration, admin creation, profile changes and all reset paths: 15–128 characters, including spaces and UTF-8 passphrases, with a small common-password deny list. Argon2id avoids bcrypt's 72-byte boundary. Existing bcrypt hashes remain usable and upgrade on successful local login. No existing password is forcibly changed. [NIST password guidance](https://pages.nist.gov/800-63-4/sp800-63b.html), [Laravel authentication](https://laravel.com/framework/docs/13.x/authentication).

Password challenges use cryptographic six-digit codes stored as hashes. Each challenge is bound to the user, current email/security fingerprint and requesting browser session. Verification consumes the code and creates a hashed server grant that expires with the original five-minute deadline. Reset consumes the grant inside the same transaction as the password change.

- At most five failed attempts per ten-minute window, retained across resends.
- One-minute resend cooldown and at most five sends per account per hour.
- User-row locks serialize issuance, failed attempts and grant consumption; exceptions for failed codes are raised after the attempt transaction commits.
- A resend invalidates the preceding code and any verified grant. Login, logout, account/email/password changes and browser-session changes invalidate old bindings.

Customer/admin password brokers use separate token tables, a 15-minute lifetime and a 60-second cooldown. Role/provider constraints apply to issuance **and** consumption, including superadmins. A token cannot be used at the other portal or reset a social account. Reset verification and consumption are serialized under the account lock. [Laravel password reset guidance](https://laravel.com/framework/docs/13.x/passwords).

Login failures use the same response for missing accounts, wrong passwords, social accounts and the wrong portal. Missing accounts also consume login limits. Public recovery and registration return a generic response without disclosing account eligibility; registration no longer signs in automatically. It sends verification for newly created accounts and directs everyone to sign in or recover an existing account.

Recovery notifications use encrypted queued payloads and dispatch after commit. Run a queue worker promptly: OTPs expire after five minutes. Local preview uses synchronous delivery to an in-memory mail transport. Real email delivery, monitoring and worker supervision remain live acceptance/P0-12 requirements.

## Session security

The customer cookie is stable across storefront, account and recovery routes. The distinct admin cookie is scoped to `/admin`; the selected cookie/guard is configured **before** StartSession. Separate `web` and `admin` guards prevent customer/admin login and logout from overwriting each other. Cookies retain the production Secure/HttpOnly/SameSite=Lax policy from P0-02.

Sessions carry an account fingerprint. Password/email/role/MFA changes rotate the security version and remember token, invalidate outstanding recovery grants and revoke stored database sessions. Middleware rejects stale fingerprints for other session drivers too. Password changes preserve the current customer session after ID rotation; staff must reconfirm MFA. Old sessions without the new binding must sign in again. Password and recovery secrets are excluded from flashed input; sensitive pages use `Cache-Control: private, no-store`.

## Mandatory admin MFA

Admins and superadmins must enroll a TOTP authenticator after their password login and verify it before entering any privileged screen/action, including direct AJAX requests. Enrollment requires the current password and a code from the proposed authenticator; replacing an authenticator also requires recent MFA. Secrets are encrypted with the existing APP_KEY and excluded from user serialization. Preserve the APP_KEY securely.

The pinned `pragmarx/google2fa` library verifies six-digit/30-second codes with a one-step clock window. The last accepted step is locked/persisted to prevent reuse across simultaneous sessions. Five failed attempts cause a five-minute account-wide wait, retained across logout/login. Admin remember-me sign-in is disabled. [Google2FA library](https://github.com/antonioribeiro/google2fa).

Ten high-entropy recovery codes are displayed once after enrollment/replacement. Only hashes are stored, inside encrypted data; consumption is atomic and single use. A staff member with a lost phone can sign in using one recovery code, then replace the authenticator using their password and that recent verification. Regenerating codes requires the password and a fresh TOTP; it revokes other sessions and old codes. EN/MM screens are provided.

If both the authenticator and recovery codes are lost, a trusted host operator must verify staff identity through an independent channel before running:

```sh
php artisan admin:mfa-reset staff@example.com --reason="Independent identity verification completed"
```

The command confirms interactively, logs the account ID/reason, revokes sessions/recovery grants and requires enrollment at the next password login. `--force` is for an already authorized noninteractive operator workflow. Do not include credentials or personal documents in the reason. No real account has been reset during implementation.

## Migration and release

Back up the database and preserve APP_KEY before deploying. Migration `2026_10_12_000002_harden_authentication.php` adds security/MFA fields and the challenge table and invalidates transient plaintext legacy OTPs/reset links. It does not modify commerce records. Existing accounts require sign-in; existing staff enroll MFA before accessing admin pages. New password hashes/MFA state require this upgraded code/configuration; do not drop the MFA columns or roll back to a bcrypt-only verifier after enrollment. Rehearse an upgrade/rollback before a real release under P0-16.

Use the P0-02 runtime, rebuild assets, clear stale caches, migrate, rebuild configuration/routes/views and restart PHP/queue workers. Configure a real mail transport and encrypted persistent sessions, then test password recovery and staff enrollment on staging.

## Verification

Results are being recorded after local/browser/MySQL/CI checks. The dedicated `phpunit.mysql.xml` profile is restricted to the disposable `ecoshop_security_test` CI database; never point tests at application data. Ordinary tests continue to force in-memory SQLite. MySQL concurrency coverage here is for authentication only; pricing/inventory concurrency remains with its later plan items.

Hosting, genuine TLS, real email delivery and physical-device acceptance remain pending. P0-03 implementation can be completed with isolated tests without marking the shop ready to take live payments.
