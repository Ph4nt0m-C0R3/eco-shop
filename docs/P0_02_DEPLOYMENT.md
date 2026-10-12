# P0-02 — supported runtime and deployment environment

Started: 12 October 2026. Application implementation is under verification. Hosting/domain provisioning and live acceptance remain pending because the project has neither yet.

Scope: PHP 8.5/Laravel 13 compatibility, deployment security/configuration, optimized bootstrap and removal of the storefront navigation version badge. P0-03 and F1–F4 remain future upgrades.

## Runtime

The application now requires PHP 8.5 and Laravel 13. The locally verified Windows runtime is PHP 8.5.11, installed only in ignored `.tools/php-8.5.11`; XAMPP and the system PATH are unchanged. The official runtime archive matched SHA-256 `0ea96e0d2b9b737a6036f05cf4e95c49313faa6d0f27bd97edb2742503f0c043`. Composer resolves Laravel 13.35.0, Tinker 3 and PHPUnit 12 with compatible dependencies. [Official PHP downloads](https://www.php.net/downloads.php?os=windows), [Laravel 13 upgrade guide](https://laravel.com/framework/docs/13.x/upgrade).

Required PHP extensions include cURL, DOM/XML, fileinfo, mbstring, OpenSSL, PDO MySQL and ZIP; SQLite supports isolated local tests. The project-local `php.ini` enables these and uses the existing trusted XAMPP CA bundle. CI pins PHP 8.5.11 and the Node version in `.nvmrc`.

Windows commands use the project-local runtime explicitly:

```powershell
& ./.tools/php-8.5.11/php.exe artisan test
& ./.tools/php-8.5.11/php.exe scripts/check-blade.php
& ./.tools/php-8.5.11/php.exe C:/ProgramData/ComposerSetup/bin/composer.phar install --prefer-dist
powershell -NoProfile -ExecutionPolicy Bypass -File scripts/serve-preview.ps1
```

The preview script uses the existing isolated audit database on localhost:8002. A fresh clone needs its own configured development database and a supported PHP installation, then `php artisan serve`. The older XAMPP PHP 8.2 executable cannot run this upgraded branch.

## Production configuration

The proposed deployment profile is Linux, Nginx/PHP-FPM 8.5 and MySQL/InnoDB. `deploy/production.env.example`, `deploy/nginx.conf` and `deploy/php-production.ini` are reference files requiring real hosting values before use. The application serves through `public/index.php`; the Nginx configuration denies hidden files and execution of other PHP/uploaded files.

- Production requests require the exact host from the HTTPS `APP_URL`. Plain HTTP browsing redirects to that domain; insecure writes are rejected. Debug mode, insecure cookies or an invalid production URL cause a generic unavailable response.
- Forwarded protocol/IP/port headers are accepted only from configured proxy IPs/CIDRs. Forwarded hosts and wildcard trust are ignored. Direct Nginx TLS needs no `TRUSTED_PROXIES` entries; configure actual load-balancer addresses if one is introduced.
- Session cookies are Secure, HttpOnly and SameSite=Lax in the production profile. Session serialization is JSON; users may need to sign in again after the upgrade. The existing `APP_KEY` is preserved.
- Responses use nosniff, SAMEORIGIN, a restrictive referrer policy and HSTS without subdomain/preload commitments. Nginx supplies equivalent headers for static/error responses. Production PHP disables displayed errors and logs them privately.
- Keep the actual environment/secrets outside source control. Provide a dedicated database account restricted to the shop schema. Use a separate migration role where feasible; grant the runtime role only its required data operations. Use database TLS when the deployment requires a remote connection.
- Public uploads and private proofs require persistent storage, private files outside the web root and tightly scoped write permissions. Grant application writes to `storage/` and `bootstrap/cache/`; application code, secrets and releases should be owned/provisioned by the deployer. Avoid world-writable permissions. Certificate private keys stay restricted to the host services.

Production settings can be checked without printing passwords or keys:

```sh
php scripts/check-production.php
```

This deliberately rejects the development profile and incomplete example values. It validates configuration; it does not certify TLS, database grants, service supervision or storage permissions on a machine that has not been provisioned.

## Optimized deployment bootstrap

After configuring the real private environment and persistent storage:

```sh
composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
php scripts/check-production.php
php artisan config:cache
php artisan route:cache
php artisan view:cache
php scripts/check-cached-bootstrap.php
```

Use the protected release workflow and a backup/migration rehearsal before applying updates to real data. Restart PHP-FPM and long-lived workers to load a release. This phase does not run database migrations or change actual customer data. A duplicate customer GET/POST `login` name was corrected to `login`/`login.store` so route caching succeeds; role restrictions remain covered by tests.

CI validates cached bootstrap and Nginx syntax with a disposable certificate; `scripts/check-nginx.sh` never starts the reference server. Live certificate/host validation is still required. [Laravel deployment guidance](https://laravel.com/framework/docs/13.x/deployment), [trusted proxy guidance](https://laravel.com/framework/docs/13.x/requests#configuring-trusted-proxies).

## Navigation adjustment

The customer-facing navigation keeps the EcoShop logo and accessible name without a `v2.0` badge. Version branding remains in page titles, footer, authentication, emails and admin navigation as requested.

## Verification and remaining acceptance

Results will be recorded after local/browser/CI checks pass.

- [ ] Confirm the domain and hosting platform; install a supported patched PHP/FPM runtime and matching staging environment.
- [ ] Provision genuine TLS, database credentials/grants, private environment and persistent storage.
- [ ] Substitute Nginx/FPM paths and the real domain; run readiness/syntax checks on the host.
- [ ] Verify real HTTPS redirects, cookie/proxy behavior, OAuth/payment callback URLs and generic error pages on staging.
- [ ] Complete queue/email, backup/restore and live-provider gates with their later production-plan items.

P0-02's live-host acceptance stays unchecked until hosting exists. Local implementation and its verification can be completed independently.
