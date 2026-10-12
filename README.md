# EcoShop v2.0

Laravel 13 / PHP 8.5+ shop with English and Myanmar storefronts, customer accounts, inventory reservations, orders and an admin interface. Version 2 upgrades are implemented one plan item at a time. See the [production plan](docs/V2_PRODUCTION_PLAN.md) and [P0-01 release workflow](docs/P0_01_VERSION_CONTROL.md).

## Local setup

1. Install patched PHP 8.5+ with PDO MySQL/SQLite, DOM/XML, mbstring, fileinfo, cURL, OpenSSL and ZIP; Composer; the Node version in `.nvmrc` (24.18.0).
2. Run `composer install` and `npm ci`.
3. Copy `.env.example` to `.env` only for a new installation. Set the application URL and database connection, then run `php artisan key:generate`.
4. Run `php artisan migrate`. Use `php artisan db:seed` only with a disposable development database. Seeders contain demonstration accounts; choose your own credentials for real installations.
5. Run `php artisan storage:link` and `npm run build`.
6. Start the application with `php artisan serve`; use `npm run dev` for Vite development updates.

Keep `/audit` out of deployment artifacts. It contains source snapshots, local test data, test tooling and audit evidence.

On this Windows workspace, use `.\php.cmd artisan config:cache` (or any other PHP command) to select PHP 8.5.11 instead of XAMPP's PHP 8.2. For bare `php` in a PowerShell terminal, run `Set-ExecutionPolicy -Scope Process Bypass` and dot-source `. .\scripts\use-php.ps1`. This selects the project PHP only for that terminal. The isolated preview at localhost:8000 uses separate caches and needs network access for Google/GitHub and SMTP. See [runtime and terminal setup](docs/P0_02_DEPLOYMENT.md).

## Applying this update to an existing installation

Back up the database and uploaded files, then install the updated lock files, build assets and run:

```sh
php artisan migrate
php artisan payments:privatize-proofs
php artisan optimize:clear
```

The migration adds a saved MMK exchange rate, reservation timestamps and a unique provider-event ledger. Existing Stripe orders awaiting payment receive a reservation deadline; expiry checks verify provider status before releasing stock. Existing totals are preserved. Old orders without a saved rate use the ratio of their recorded subtotals where possible.

The proof command verifies each copy before removing its public version. It handles a physical Windows `public/storage` copy as well as linked storage. Admins view proofs through the authorized order route. It does not remove unrelated upload files.

## Payments, queues and schedule

- Set `STRIPE_SECRET` and `STRIPE_WEBHOOK_SECRET` for the same test/live environment. Configure Stripe to POST to `/api/stripe/webhook` for `checkout.session.completed`, `checkout.session.async_payment_succeeded`, `checkout.session.expired` and `checkout.session.async_payment_failed`.
- A browser success/cancel return never confirms payment. A matching signed webhook confirms the order and commits stock once. Admin approval cannot override a Stripe payment.
- Card settings currently support USD and MMK. Both use two decimal minor units for Stripe charges; MMK storefront totals retain the shop's existing whole-kyat/50-kyat rounding rules.
- Run `php artisan queue:work --tries=3` for confirmation emails. Queue jobs are created after the payment transaction commits. Configure a mail transport; use `MAIL_MAILER=log` or `array` for isolated development.
- Run the Laravel scheduler every minute (`php artisan schedule:work` locally, `schedule:run` from cron/task scheduler in deployment). `orders:expire-reservations` checks and expires abandoned provider sessions, then releases unpaid reservations. Provider failures leave reservations intact for retry.
- Payment proofs accept JPEG, PNG or WebP images up to 5 MB and are stored on the private local disk.
- Configure HTTPS social URLs (`facebook_url`, `twitter_url`, `instagram_url`, `youtube_url`, `linkedin_url`) in system settings if needed; links are hidden when no valid destination is configured.

## Verification

```sh
php artisan test
npm run build
composer audit
npm audit
php scripts/check-blade.php
npm run check:repository
npm run test:release
```

Tests force an in-memory SQLite database; they do not use the application database. Commerce tests cover ownership, duplicate/mismatched webhooks, reservation release, invalid methods/uploads, hidden products, stored pricing and private proofs. CI runs the suite, build and dependency audits.

The storefront uses local copies of its public fonts, CSS and JavaScript dependencies. EN/MM flag SVGs are in `public/global/img/flags`. Vite assets use Tailwind 4 for the legacy Breeze views; these views target modern browsers (Safari 16.4+, Chrome 111+, Firefox 128+). The main shop and admin styling use Bootstrap.

See the tracked [stabilization baseline](docs/STABILIZATION_BASELINE.md). Detailed audit evidence remains locally under the ignored `audit/` directory. Physical-device, live-provider and concurrent MySQL checks remain release verification steps.

## Version control and releases

The stabilized pre-v2 source is tagged `baseline-stabilized-2026-10-12`. Configure the local pre-commit guard with `git config core.hooksPath .githooks`. Implement each plan item in its own reviewed branch and require the CI `check` job before merging.

Build deployment artifacts in an isolated clean checkout after the checks pass: `composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader`, then `npm run release:package`. The verified archive includes production dependencies and Vite assets, records its source commit/file hashes and excludes secrets, audit/test data, demo seeders, runtime caches and customer uploads. See the [P0-01 instructions](docs/P0_01_VERSION_CONTROL.md) for complete verification and rollback steps. P0-02 provides the runtime/deployment templates; live-host acceptance and later production gates remain pending.

## P0-02 deployment environment

See [runtime and deployment notes](docs/P0_02_DEPLOYMENT.md) for the PHP 8.5.11 Windows commands, isolated preview script, production Nginx/PHP/environment templates, secure proxy/cookie configuration and readiness checks. Hosting and a real domain are still needed for live acceptance. The storefront navigation version badge has been removed; other requested version branding remains.

## P0-03 account security

See [authentication and recovery notes](docs/P0_03_AUTHENTICATION.md) for mandatory admin authenticator enrollment, one-time recovery codes, account-bound OTPs, session revocation and the 15–128 character passphrase policy. Apply the authentication migration to a backed-up database before starting this version. Existing sessions require sign-in; staff enroll MFA at their next password login. Preserve APP_KEY and run a queue worker promptly for encrypted recovery notifications. Production requires encrypted sessions and distinct portal cookies.

CI uses a dedicated disposable MySQL database for the authentication tests, including simultaneous OTP/reset/MFA requests. The default test command still forces in-memory SQLite; the separate profile runs with `php vendor/bin/phpunit --configuration=phpunit.mysql.xml` only against its dedicated test database. Physical devices, real email delivery and a live deployment remain acceptance work.

## P0-04 Google/GitHub sign-in and real email

See [email and OAuth setup](docs/P0_04_EMAIL_OAUTH.md). The isolated preview now defaults to **http://localhost:8000** to match the owner's registered OAuth callbacks, and respects the private SMTP configuration. Use `-OfflineMail` for an in-memory preview instead. Social sign-in verifies browser state, verified email claims and provider IDs; it does not automatically link existing accounts by email. Staff continue to use password and MFA.

Run `php artisan integrations:check --smtp` to authenticate with SMTP without sending mail. Run `php artisan mail:test "YOUR_REAL_INBOX_ADDRESS"` to send one explicit test to your own inbox. Keep credentials in the ignored `.env`. The owner confirmed SMTP test receipt in Spam and both real Google/GitHub sign-ins on localhost:8000 after the callback/network repair on 12 October 2026. PHP 8.5 terminal/cache commands are verified. HTTPS staging, Inbox placement and full email journeys remain pending. P0-05 has not been started.
