# EcoShop stabilized baseline

Recorded before v2 implementation on 12 October 2026. Local rollback tag: `baseline-stabilized-2026-10-12` (commit `1b89518`).

- Laravel 12.69.3 and patched locked dependencies; production frontend build passed.
- 44 PHP tests passed with 176 assertions; 138 compiled Blade templates passed syntax checks.
- 848 responsive cases across 46 routes, 320–1920px, passed without server/JavaScript errors, broken-image cases or page-level horizontal overflow.
- Product 17 passed 12 direct browser cases: guest/customer, EN/MM, phone/tablet/desktop; review modal, rating and message interactions verified.
- Ownership/payment-event checks, locked order transitions, stored totals/exchange rates and private payment proofs are implemented.
- Local flags/assets, mobile cart/menu/filter behavior, profile and product/form template regressions were repaired.

The original detailed audit evidence and SQLite fixtures remain under the local ignored `audit/` directory. They are excluded from Git and deployment artifacts. This sanitized record is tracked so future clean checkouts retain the baseline summary.

These checks used isolated SQLite data and local Chrome. Live payments, production MySQL concurrency, email delivery, real devices, backups and deployment recovery remain production-plan gates.
