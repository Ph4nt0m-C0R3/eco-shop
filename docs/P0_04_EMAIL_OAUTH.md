# P0-04 — Google/GitHub sign-in and real email

Implemented: 12 October 2026. Scope is OAuth security plus the owner's request for real email delivery. P0-05 has not been started. Provider credentials remain in the ignored private `.env` file.

## Local preview and callback URLs

The owner confirmed that the existing OAuth applications use port **8000**. Start the isolated preview with:

```powershell
Set-Location E:\Eco_Shop
powershell -NoProfile -ExecutionPolicy Bypass -File scripts/serve-preview.ps1
```

Open **http://localhost:8000/login**. The canonical URL is localhost, including the port. Opening a social-login link from 127.0.0.1 redirects to localhost before starting OAuth so its session cookie reaches the callback.

| Provider | Registered callback URL |
|---|---|
| Google | `http://localhost:8000/auth/google/callback` |
| GitHub | `http://localhost:8000/auth/github/callback` |

`APP_URL=http://localhost:8000` is set in this workspace's private `.env`. Provider callbacks default to `APP_URL/auth/{provider}/callback`; optional `GOOGLE_REDIRECT_URI`/`GITHUB_REDIRECT_URI` must equal those full URLs. On staging/production, use the actual HTTPS domain and register matching callbacks in each provider's dashboard. Do not mix localhost and 127.0.0.1 or change the port halfway through sign-in. [Google redirect rules](https://developers.google.com/identity/protocols/oauth2/web-server), [GitHub OAuth app setup](https://docs.github.com/en/apps/oauth-apps/building-oauth-apps/creating-an-oauth-app).

Existing Google/GitHub client IDs and secrets were preserved. Never put a client secret in Blade, JavaScript, a commit, a screenshot or chat.

## Real SMTP delivery

The preview now respects `.env` mail settings and uses synchronous delivery locally, so it does not require a separate local queue worker. For deliberately offline UI testing, add `-OfflineMail` to the launcher; it explicitly uses the in-memory mail transport.

Configure these fields privately using the SMTP service you already have:

```dotenv
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=your-provider-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-provider-smtp-username
MAIL_PASSWORD="your-provider-smtp-password"
MAIL_FROM_ADDRESS="your-authorized-sender-address"
MAIL_FROM_NAME="EcoShop v2.0"
MAIL_TIMEOUT=15
MAIL_REQUIRE_TLS=true
```

Use `smtp` with STARTTLS on port 587 or `smtps` for implicit TLS on port 465, as your provider specifies. PHP must have a valid CA certificate bundle; do not disable certificate verification. If your provider requires an app password, use that instead of your account password. [Google app-password guidance](https://support.google.com/accounts/answer/185833). Sender permission/verification is controlled by the SMTP provider.

The mail configuration applies to password OTPs, customer/admin reset links, registration verification and existing order-confirmation mail. Verification notifications now also use encrypted queued payloads and dispatch after commit. SMTP delivery failures return generic customer-facing responses; authentication transcripts and OAuth tokens are not logged by these handlers.

Run the project-local PHP executable, rather than the old system PHP:

```powershell
& .\.tools\php-8.5.11\php.exe artisan integrations:check
& .\.tools\php-8.5.11\php.exe artisan integrations:check --smtp
```

The first command checks configuration without connecting or printing secrets. `--smtp` checks connection/TLS/authentication without sending email. To send one non-sensitive test, replace the recipient below with an inbox you control:

```powershell
& .\.tools\php-8.5.11\php.exe artisan mail:test "YOUR_REAL_INBOX_ADDRESS"
```

Transport acceptance does not prove inbox delivery: confirm receipt, including spam. `.test` fixture addresses cannot receive real messages. Register a separate local customer with an inbox you own, sign in, and verify its registration email before testing password OTPs. Do not change the audit fixtures to real recipients or send messages to customer data during testing.

After changing `.env`, restart the preview. Clear configuration cache with the project PHP executable if you previously cached it. A fresh clone needs its own development database; the ignored audit database is not in release artifacts.

On production, keep the durable queue configuration and supervise a worker. OTPs expire in five minutes; monitor delayed/failed jobs. This request does not complete P0-12 worker supervision or email-deliverability operations. Preserve APP_KEY so encrypted jobs/MFA secrets remain usable.

## OAuth behavior

- Only Google/GitHub customer sign-in routes are accepted; unsupported providers return 404. Staff use the existing admin password and mandatory MFA flow.
- OAuth uses Socialite's session-state verification, a provider/callback-bound ten-minute flow and one-time consumption. Missing, forged, expired, cancelled, replayed and cross-provider callbacks fail before provider requests. Starting a new flow replaces the previous one; restart sign-in if an old tab fails.
- Google must return a verified email claim. GitHub must return a verified primary address through its authenticated `/user/emails` API with `user:email`; a public profile email is insufficient. [Google claims](https://developers.google.com/identity/openid-connect/openid-connect), [GitHub email API](https://docs.github.com/en/rest/users/emails).
- Accounts match **provider plus provider ID**. Email collisions with local accounts, another provider or another provider ID fail without linking. Legacy social rows without a provider ID also fail closed; do not backfill identities merely by matching an email. Explicit account linking is not included in this phase.
- For an existing provider identity, signing in does not overwrite the shop email, role, credentials or avatar when provider details change. New customers have no local password, and manage their provider password with Google/GitHub.
- Successful sign-in regenerates the customer session and binds the account fingerprint. Admin authentication remains separate. Changing provider identity revokes old sessions/recovery grants.
- Provider access/refresh tokens are not stored; legacy `provider_token` values are hidden and no longer mass assignable. Removing historical token data requires a separately planned data migration.
- Callback responses use `no-store` and `no-referrer`, including production. Exceptions show generic errors and log only provider/exception type.

## Verification and remaining acceptance

The default test profile forces in-memory SQLite and an in-memory mail transport. The dedicated MySQL CI profile also exercises the new OAuth/mail tests; never point it at an application database.

```powershell
& .\.tools\php-8.5.11\php.exe artisan test
& .\.tools\php-8.5.11\php.exe scripts/check-blade.php
npm.cmd run build
npm.cmd run test:release
```

The full local suite passed: **124 tests / 946 assertions**, with three MySQL concurrency tests deliberately skipped in SQLite. Focused OAuth/mail/authentication/deployment checks passed: 37 tests / 392 assertions. All 144 compiled Blade templates, the Vite build and both release-policy tests passed. Browser checks passed for 16 EN/MM login/error cases at 320, 390, 768 and 1440 px: HTTP 200, no horizontal overflow, broken visible images or JavaScript errors. After switching to localhost:8000, both real providers reached their sign-in pages; Google's prior port-8002 redirect mismatch was resolved.

Hosted [branch CI](https://github.com/Ph4nt0m-C0R3/eco-shop/actions/runs/38269878787) and [PR CI](https://github.com/Ph4nt0m-C0R3/eco-shop/actions/runs/38269913259) passed for implementation commit `3cea3e18b842153912bf7ffc6f585ed6e3c1113b`: **66 MySQL/InnoDB tests / 728 assertions**, the full SQLite suite, cached bootstrap, Nginx syntax, dependency audits and production archive verification. The PR merge artifact contains 11,357 verified entries. [Draft PR #4](https://github.com/Ph4nt0m-C0R3/eco-shop/pull/4) is stacked on P0-03 and awaits owner review/merge.

The existing SMTP server rejected authentication during a connection-only check. SMTP credential correction and an actual inbox receipt remain pending. No test email was sent to an unspecified recipient. Provider sign-in pages verify initial redirects, not full account authorization: the owner must complete both real sign-in journeys with the intended accounts.

Manual acceptance:

1. Confirm `integrations:check --smtp` succeeds with updated SMTP credentials. Send `mail:test` to your own inbox and confirm receipt.
2. Register a local customer with a real inbox. Sign in and follow the signed verification link; verify resend and expired/altered-link rejection.
3. From the verified customer's password screen, request the emailed OTP in the same browser. Verify it and set a new 15–128-character password; ensure an old/used code fails. Test the email reset-link flow separately at `/forgot-password`.
4. From a logged-out customer window, complete Google login and GitHub login with different eligible addresses, then log out and repeat each. Check the resulting customer profile and existing-account identity matching. A local/password account with the same email intentionally cannot use OAuth.
5. While an admin is signed in, complete customer OAuth in the same browser. Customer/admin logout should remain independent; OAuth must never bypass admin MFA.
6. Repeat with the registered HTTPS staging domain and production cookies before launch. Physical-device, inbox-deliverability and full live-provider acceptance remain pending.

Implementation is complete; live email/account acceptance is pending as described above. P0-05 and later phases remain pending.
