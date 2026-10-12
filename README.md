# EcoShop

Laravel 12 / PHP 8.2+ shop with English and Myanmar storefronts, customer accounts, inventory reservations, orders and an admin interface. This checkout includes the stabilization work for the 2.0 development baseline.

## Local setup

1. Install PHP 8.2+ with PDO MySQL or SQLite, mbstring, fileinfo, cURL and OpenSSL; Composer; Node 22+.
2. Run `composer install` and `npm ci`.
3. Copy `.env.example` to `.env` only for a new installation. Set the application URL and database connection, then run `php artisan key:generate`.
4. Run `php artisan migrate`. Use `php artisan db:seed` only with a disposable development database. Seeders contain demonstration accounts; choose your own credentials for real installations.
5. Run `php artisan storage:link` and `npm run build`.
6. Start the application with `php artisan serve`; use `npm run dev` for Vite development updates.

Keep `/audit` out of deployment artifacts. It contains source snapshots, local test data, test tooling and audit evidence.

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
```

Tests force an in-memory SQLite database; they do not use the application database. Commerce tests cover ownership, duplicate/mismatched webhooks, reservation release, invalid methods/uploads, hidden products, stored pricing and private proofs. CI runs the suite, build and dependency audits.

The storefront uses local copies of its public fonts, CSS and JavaScript dependencies. EN/MM flag SVGs are in `public/global/img/flags`. Vite assets use Tailwind 4 for the legacy Breeze views; these views target modern browsers (Safari 16.4+, Chrome 111+, Firefox 128+). The main shop and admin styling use Bootstrap.

See [the baseline audit](audit/v2-baseline/REPORT.md) and [fix verification](audit/v2-fixes/VERIFICATION.md). Physical-device, live-provider and concurrent MySQL checks remain release verification steps.
