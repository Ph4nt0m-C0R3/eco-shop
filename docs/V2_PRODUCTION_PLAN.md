# EcoShop v2.0 production upgrade note

Recorded: 12 October 2026. Status: implementation started; upgrades are delivered one item at a time. Production deployment remains pending.

## Implementation progress

| Item | Status | Evidence / next step |
|---|---|---|
| P0-01 — version control and reproducible builds | Completed — 12 October 2026 | [Completion record](P0_01_VERSION_CONTROL.md); clean checkout and hosted CI passed; main protection enabled; [PR #1](https://github.com/Ph4nt0m-C0R3/eco-shop/pull/1) awaits owner review/merge |
| EcoShop v2.0 branding | Completed — 12 October 2026 | Shared storefront/admin titles, navigation/footer, authentication and order emails; 19 browser/viewport checks passed |
| P0-02–P0-17 and F1–F4 | Pending | P0-02 is the next production requirement; start only when requested |

Scope confirmed by the project owner: Livewire, product variants, advanced search and real-time updates are included in the v2.0 release. Their F1–F4 acceptance gates below must pass alongside P0-01–P0-17 before releasing v2.

The v2 objective is a shop that can accept orders reliably, protect customer data, recover from failures, and be operated by an admin without developer intervention. Complete the launch requirements below before accepting real customer payments. This note is the current production plan; [future-improvements.txt](../future-improvements.txt) remains the broader feature backlog.

## Current baseline

| Area | Current evidence | Remaining production work |
|---|---|---|
| Application | Laravel 12.69.3; local PHP 8.2.12; Blade storefront/admin | Supported, patched runtime and a framework upgrade schedule |
| UI | EN/MM flags served locally; repaired product details and edit forms; 848 responsive checks passed | Real iOS/Android and browser checks, accessibility, full bilingual coverage |
| Automated checks | 44 PHP tests / 176 assertions; 138 compiled Blade templates checked; CI configuration added | Actual repository/CI activation, MySQL concurrency tests, browser journeys, static analysis |
| Commerce | Ownership checks, signed/idempotent Stripe events, locked payment transitions, saved totals/rate, private payment proofs | Exact pricing, full inventory safeguards, refunds, reconciliation, audit history |
| Operations | Queue/scheduler instructions and `/up` health route exist | Provisioning, supervised services, alerts, backups, deployment and restore drills |
| Data | Preview uses an isolated SQLite database; tests use in-memory SQLite | Rehearse against the selected production database; migrate real data safely |

The previous checks establish a development baseline. Live payments, concurrent MySQL transactions, production email, recovery procedures and physical devices have not been verified. Source: [stabilization verification](STABILIZATION_BASELINE.md).

## Decisions needed before implementation

| Decision | Proposed starting point | Must be confirmed |
|---|---|---|
| Hosting | Managed Linux deployment, or one Linux application server with Nginx/PHP-FPM and supervised workers | Budget, operator, region, domain and staging environment |
| Runtime | Latest patched PHP 8.5; Laravel 13 compatibility upgrade in a separate change; Node 24 LTS for builds | Hosting support and compatibility of the locked packages |
| Database | Supported MySQL/InnoDB release; same engine and relevant configuration in staging/CI | Hosting database service, backup facilities and connection limits |
| Interactive UI | Livewire 4 with its Alpine integration; Blade rendering and Vite assets | Target-version compatibility, component migration boundaries and existing JavaScript integrations |
| Advanced search | Laravel Scout with Meilisearch as the initial candidate | EN/MM relevance and tokenization trial, index capacity, hosting cost and supported engine/client versions |
| Real-time delivery | Laravel Reverb/Echo with supervised broadcast workers; Redis for shared queues/locks where needed | WebSocket-capable hosting, TLS proxy, allowed origins, connection limits and recovery behavior |
| Variants | Product families with purchasable SKU variants and variant-owned stock | Initial option types, SKU format, price/image rules and legacy-product mapping |
| Payments | Enable only methods verified for the actual merchant account | Merchant country, settlement bank, supported currencies, COD/offline-payment process |
| Shipping and pricing | Explicit zones, delivery fees, tax treatment and USD/MMK rounding rules | Selling countries, delivery partner, returns policy and rate source |
| Capacity and recovery | Measure expected peak traffic and define recovery targets | Peak shoppers/orders, acceptable downtime and acceptable data loss |
| Launch ownership | Named owner for each release requirement | Person responsible for payments, fulfilment, support, security and outages |

Runtime upgrades need planning now: PHP 8.2 security support ends on 31 December 2026; PHP 8.5 has security support through 31 December 2029. Laravel 12 security support ends on 24 February 2027; target Laravel 13 for the v2 lifecycle after compatibility testing. A launch on patched Laravel 12 needs a scheduled upgrade before that deadline. [PHP support policy](https://www.php.net/supported-versions.php), [Laravel support policy](https://laravel.com/framework/docs/12.x/releases).

Use the [Laravel 13 upgrade guide](https://laravel.com/framework/docs/13.x/upgrade) to review authentication/request-forgery protection, cache/session serialization, queue behavior and package/test compatibility. Node 24 and 22 are currently LTS branches; choose and pin one tested build runtime. [Node release policy](https://nodejs.org/en/about/previous-releases).

## P0 — required before production launch

Each item needs an owner, implementation evidence and a passing acceptance check. Completed items are recorded in the progress table; unchecked gates still require implementation or production evidence.

| ID | Required upgrade or work | Acceptance criteria |
|---|---|---|
| P0-01 | **Version control and reproducible builds.** Establish the Git repository, capture the repaired baseline, protect release branches and use reviewed changes. Commit lock files. Exclude audit databases, source snapshots, credentials and demo accounts from deployable artifacts. | A clean checkout builds and tests in CI; a tagged release can be reproduced and rolled back. No audit/test fixture or secret is published. |
| P0-02 | **Supported deployment environment.** Patch PHP and dependencies; serve only `public/` through HTTPS. Configure trusted proxies, `APP_ENV=production`, `APP_DEBUG=false`, the real application URL, secure/HttpOnly cookies and least-privilege filesystem/database access. Manage secrets outside the repository. | Staging matches production configuration; secrets/debug pages are inaccessible; OAuth/payment redirects use the real HTTPS domain; configuration/route/view caching passes. |
| P0-03 | **Authentication and recovery hardening.** Repair OTP generation/storage/verification: cryptographic randomness, hashed secrets, expiry-bound single use, atomic attempts, resend cooldown and account/session binding. Remove the 15-character password cap in favor of a tested passphrase policy. Add admin MFA, secure reset/session invalidation and consistent account-enumeration protection. | Tests cover expired/replayed OTPs, resend/reset loops, brute force, account switching, password changes, recovery and admin MFA. Authentication continues to work with production cookies/proxies. |
| P0-04 | **OAuth security.** Review state verification, allowlisted providers, provider-ID account matching, trusted email-verification claims, account-linking rules and session regeneration. Current web OAuth uses `stateless()` and matches existing accounts by email/provider. | Forged state, mismatched provider IDs and untrusted email claims cannot sign in as another account. Callback URLs and separate admin/customer sessions are tested. |
| P0-05 | **Authorization and safe responses.** Add resource/action policies for orders, proofs, users, products, settings and exports. Enforce them in every controller or future component. Return only necessary fields in JSON; review endpoints currently serialize a loaded `User` relation. | A role/ownership matrix passes through direct HTTP requests. Review/API responses do not disclose emails, addresses, phone numbers, provider tokens or staff-only fields. |
| P0-06 | **Abuse and upload protection.** Extend throttling to registration, OTP send/verify/resend, password recovery, contact/newsletter, reviews and sensitive mutations. Apply size/type/dimension limits to every upload, re-encode uploaded images where appropriate, and prohibit executable uploads. | Repeated abuse is limited; malformed/oversized files are rejected; uploaded files cannot execute; proof URLs remain private. Existing login throttling is retained and tested. |
| P0-07 | **Exact pricing and currency rules.** Centralize pricing/tax/shipping/FX arithmetic using integer minor units or exact decimal arithmetic. Define provider-specific exponents and rounding. Snapshot applied rates/fees/taxes/line totals; reject missing, zero or inactive rates in admin pricing as well as checkout. | Checkout, provider amount, receipt, report and refund agree for both supported currencies, boundary values and historic orders. Migration/backfill preserves existing recorded totals. |
| P0-08 | **Inventory correctness across all writers.** Move admin stock adjustments, reserve/commit/release/returns into a transaction-aware service. Prevent stock below reserved quantity; enforce invariants, deterministic lock order and deadlock retries. Add an adjustment/movement ledger and reconciliation. | MySQL tests with simultaneous last-item purchases, overlapping multi-item carts, admin edits and webhook/cancel races cannot oversell or release/commit twice. Every adjustment records actor/reason. |
| P0-09 | **Order and payment recovery.** Formalize allowed status transitions and payment attempts. Handle failed, delayed, duplicate and reordered callbacks. Add a reconciliation job/admin view for unresolved payments, stale reservations and provider/local mismatches. Preserve idempotency of stock and notifications. | Provider outages and missing callbacks are recoverable without duplicate fulfilment. Pending/paid/failed states agree with the provider; alerts identify unresolved mismatches. |
| P0-10 | **Merchant/payment eligibility and refunds.** Verify the actual account, supported presentment/settlement currencies and live webhook configuration. Define offline-proof approval, COD collection, cancellation and full-refund operations. Record refunds and disputes separately from fulfilment and stock returns. | End-to-end staging provider tests pass; an authorized operator can execute and reconcile a full refund using a documented process. Duplicate refunds are prevented and stock disposition is explicit. |
| P0-11 | **Retention of commerce records.** Review destructive operations and cascading deletes. User deletion currently cascades into orders; product/category removal can affect pending stock commitments. Prefer archival/anonymization with retention rules and guards on reserved/ordered products. | Customer/admin/product/category removal cannot erase required financial history or strand paid/pending orders. Retention behavior is tested and documented. |
| P0-12 | **Reliable workers, scheduler and notifications.** Supervise queue workers with retries/timeouts, monitor failed jobs, run the scheduler every minute and restart workers on deployment. Preserve durable notification intent if queue dispatch fails after payment commits. Configure authenticated production email and sender DNS. | Workers recover after restart; missed notifications can be retried safely; failed jobs/scheduler gaps alert an operator; verification/reset/order emails reach real inboxes. |
| P0-13 | **Storage, backups and recovery.** Provide persistent storage for product images and private proofs. Back up database, uploads and encryption/secrets recovery material securely off-server; define retention and restoration access. | Restore a backup into an isolated environment and prove orders, images and authorized private proofs are intact within the agreed recovery targets. A deploy/restart cannot lose uploads. |
| P0-14 | **Monitoring and operational audit.** Track errors, latency, checkout failures, payment mismatches, queue age, scheduler heartbeat, disk capacity and database health. Add request/order correlation IDs and audit events for payment/refund/stock/settings/admin changes. Redact secrets and personal data. | Alerts reach a named operator; `/up` is complemented by dependency/worker checks; one sample incident can be traced from request to order/provider event and recovered using a runbook. |
| P0-15 | **Release tests and performance.** Retain PHP/Blade/build/audit checks; add supported-runtime and MySQL jobs, browser commerce/admin journeys, static analysis and code style. Test EN/MM, representative physical devices, accessibility and measured peak-load scenarios. | Critical journeys pass, including product detail, reset/login, cart, all enabled payments, refunds and admin stock changes. Agreed latency/query/error budgets pass under the expected peak load. |
| P0-16 | **Migration, release and rollback procedure.** Rehearse fresh install and upgrades from a copy of existing data. Backfill reservations, validate stock/totals and migrate public proofs privately. Use backward-compatible migrations, a tested maintenance/atomic deployment process and smoke checks. | Production-data rehearsal succeeds; releases restart services and run healthy; rollback preserves orders created during the release and handles schema changes deliberately. |
| P0-17 | **Customer-facing operations and bilingual completion.** Replace placeholder legal/business content with approved information for the operating jurisdiction. Publish delivery, refund, privacy, contact and order-support information. Translate critical validation, statuses, email and failure/retry messages. | Customers can understand charges, delivery and refunds in EN/MM; support can resolve order/payment issues; business/content approval is recorded. |

Payment eligibility must be checked against the merchant's actual country and account, especially if operating from Myanmar. Enable Stripe only after confirming eligibility and currency support; an eligible offline/local method may be the launch path. Do not infer gateway minor units from whole-kyat display rounding. [Stripe availability](https://stripe.com/global), [currency rules](https://docs.stripe.com/currencies).

Keep signed, idempotent callbacks and test delayed/reordered delivery; Stripe documents retries and non-guaranteed event ordering. Persist accepted events before asynchronous processing, and acknowledge only when they are safely accepted. [Stripe webhook guidance](https://docs.stripe.com/webhooks).

For server configuration, public document root, production debug settings, caching and worker reloads, use the [Laravel deployment guide](https://laravel.com/framework/docs/12.x/deployment).

## F1–F4 — committed v2 feature requirements

These four features are part of the planned v2 release. Implement them on top of the pricing, inventory and authorization services so component updates, indexed search results and broadcast events cannot bypass commerce rules.

### F1 — Livewire and shared interactive components

- Adopt Livewire 4 for admin products/orders/categories/users, storefront filtering/pagination, variant selection, cart/wishlist, reviews and customer order views. Use Alpine for local menus, tabs and modals; retain shared services for checkout/payment mutations.
- Build shared validation, loading, empty, error/retry and notification patterns in EN/MM. Preserve shareable URLs, browser history, keyboard focus and stable row/component keys.
- Deliver the admin product table first as a required integration milestone, then migrate the remaining scoped screens incrementally. Retire each old AJAX handler only after feature parity passes, and prevent duplicate Alpine initialization on Livewire pages.
- Authorize every component action on the server, validate client-supplied IDs and guard duplicate submissions. Test forged component requests and uploads as well as normal interactions.

Acceptance: all scoped screens have component/browser coverage; search/filter/page state survives refresh and Back/Forward; rapid requests cannot overwrite newer results; menus/modals still work after DOM updates; unauthorized writes fail; EN/MM mobile layouts and commerce journeys pass.

Livewire includes Alpine and documents removing a separately initialized copy. Package versions and bundling will be pinned during implementation. [Livewire installation and Alpine integration](https://livewire.laravel.com/docs/4.x/installation).

### F2 — product variants, SKU pricing and stock

- Introduce product variants with unique SKUs, valid option combinations (initially size/color/package), active status, price, images and delivery weight where applicable. Define option-combination uniqueness and availability rules.
- Make variants the stock/reservation unit across admin adjustments, cart, checkout, payment commits, cancellations, returns and the inventory ledger. Parent product quantities are derived summaries; define one owner for each stock movement.
- Cart identity includes the variant. Persist immutable SKU/option/price snapshots on order lines so later catalog edits do not change receipts, reports, fulfilment or refunds. Validate an active variant belongs to its product.
- Give each existing simple product a default variant through a reconciled migration. Map existing carts and open reservations; preserve historical order records and recorded totals. Provide admin variant creation/editing and a customer selector with clear price/stock/image changes.
- Archive ordered/reserved variants safely and test price edits, discontinued options and refunds independently from product-family deletion.

Acceptance: legacy products remain purchasable after migration; stock/reservations reconcile before and after backfill; two different variants remain distinct cart lines; competing purchases for the last unit of one SKU cannot oversell; changing a catalog variant cannot rewrite old orders; invalid/inactive variant IDs are rejected.

### F3 — advanced bilingual search

- Integrate Laravel Scout with a dedicated search engine. Start with a Meilisearch proof of concept using real EN/MM product names, descriptions, SKU/option text and representative misspellings; select the engine after the relevance/tokenization trial.
- Include autocomplete, typo tolerance where validated, ranked results and facets for category, price, availability, variant options and eco attributes. Synchronize query/filter/sort/page state with the URL and provide accessible keyboard navigation and no-results/retry states.
- Index public, active catalog data only. Queue index updates after committed catalog changes; propagate deletions, variant edits and visibility changes. Hydrate/revalidate purchasability and prices from the database when displaying results and checking out.
- Add index rebuild/reconciliation, failed-update alerts, versioned indexing settings and a tested database-search fallback during engine outages. Keep administrative keys on the server and limit public search requests.

Acceptance: an agreed EN/MM relevance fixture passes; indexed variants and facets represent current catalog rules; hidden/archived products are excluded even when the index is stale; index updates meet an agreed freshness target; fallback browsing/search works during an outage; autocomplete, filters and measured latency budgets pass on mobile.

Scout supports external engines including Meilisearch and Typesense. Myanmar language behavior needs a project-specific evaluation before the engine choice is finalized. [Laravel Scout](https://laravel.com/framework/docs/13.x/scout).

### F4 — real-time orders, payment status and stock updates

- Implement Laravel broadcasting with Reverb/Echo for new admin orders, customer payment/order status, stock availability and relevant badge/notification updates. Deliver server-confirmed events after transactions commit.
- Protect customer/order and staff channels with explicit authorization. Minimize event payloads; publish only safe availability data on any public product channel. Never broadcast proofs, addresses, emails or gateway secrets.
- Let events prompt an authorized data refresh. Keep checkout and stock validation authoritative on the server; handle duplicate/out-of-order notifications, reconnects and missed events without repeated order actions.
- Provide refresh/polling fallback and a visible connection/retry state. Clear subscriptions on navigation/logout and prevent duplicate listeners when Livewire components rerender.
- Supervise Reverb and broadcast queues, configure WSS/TLS and allowed origins, monitor delivery failures/connection counts and test restart/deploy behavior. Persist delivery intent for important events that need replay/reconciliation.

Acceptance: two independent browser sessions observe authorized changes within an agreed delivery target; one customer cannot subscribe to another customer's order; event payloads expose no private data; disconnect/reconnect and worker restarts recover current state; duplicate events cannot duplicate stock/payment actions; shopping remains usable with WebSockets unavailable.

Use the official [Reverb deployment guidance](https://laravel.com/framework/docs/13.x/reverb) and [broadcasting authorization/queue guidance](https://laravel.com/framework/docs/13.x/broadcasting) when configuring the implementation.

## P1 — further maintainability and customer experience

These follow the commerce/security foundation. Promote an item to P0 when it is necessary for the actual launch catalog or operating process.

| Work | Intended outcome |
|---|---|
| Shared domain services and validation | Pricing, inventory, payment and order rules have one authoritative implementation; controllers use shared services, policies and Form Requests. |
| Frontend consolidation | Move inline/public scripts into a tested Vite build; track vendor versions/licenses; reduce duplicate Bootstrap/jQuery/Alpine patterns; use reusable Blade components and consistent loading/error states. |
| Fulfilment and customer accounts | Saved addresses, shipment tracking, receipt downloads, returns and partial-refund UI beyond the committed order-status updates. |
| Reviews and trust | Verified-purchase checks, moderation/reporting and abuse controls; review/helpful actions remain concurrency-safe. |
| Search and SEO | Product slugs/redirects, canonical URLs, sitemap, accurate Product structured data, accessible filtering and useful empty results. |
| Eco information | Structured materials/origin/certification data with evidence and review dates; explain any sustainability score and document estimated-impact assumptions. |
| Performance work | Optimize measured slow queries, eager loading, indexes, image sizes and public-page caching; keep customer/admin/payment content private. |

## P2 — growth after stable operation

- Coupons/promotions with usage limits and audited pricing rules.
- Guest carts and safe login merging; back-in-stock and reorder flows.
- Recommendations and customer impact summaries.
- Additional payment providers, multi-location inventory or APIs when there is a confirmed business need.

## Implementation sequence and exit criteria

| Phase | Scope | Exit criteria |
|---|---|---|
| 1. Preserve and prepare | Confirm operating decisions; establish repository/CI; build staging; patch runtime and evaluate Laravel 13 upgrade independently | Baseline reproducible; target-runtime tests pass; staging HTTPS and secrets configured |
| 2. Protect accounts and data | OTP/password/OAuth repairs, admin MFA, policies, safe JSON, throttling, upload/retention guards | Security/role/abuse tests pass; account recovery and record retention demonstrated |
| 3. Complete variant-aware commerce | F2 data model/backfill, pricing, inventory ledger, lifecycle, payment reconciliation, refunds and durable notifications | Legacy and variant stock/totals reconcile; MySQL race tests and launch payment/recovery flows pass |
| 4. Adopt Livewire | F1 shared layout/components, admin product-table milestone, remaining admin/customer screens and variant selectors | Feature parity, component authorization, EN/MM/history/focus and browser commerce journeys pass |
| 5. Add advanced search | F3 EN/MM engine trial, Scout integration, facets/autocomplete, indexing and fallback | Relevance, visibility, freshness, outage recovery and search performance gates pass |
| 6. Add real-time delivery | F4 channels/events, Livewire integration, reconnect/fallback and supervised staging services | Cross-session changes, authorization, duplicate/missed-event and reconnect tests pass |
| 7. Operate and recover | Persistent storage, worker/scheduler/search/Reverb supervision, email, backups, monitoring and incident runbooks | Restore, index rebuild and service-failure drills meet agreed targets; operators receive alerts |
| 8. Validate and release | Existing-data migration rehearsal, real devices, accessibility/bilingual review, peak-load test, deployment/rollback rehearsal | Every P0 and F1–F4 gate has evidence; business/payment configuration approved; release candidate tagged |
| 9. Extend v2 | Further P1/P2 features in small reviewed releases | Each addition keeps commerce/security/recovery checks passing |

Calendar estimates and hosting costs follow the operating decisions and a scoped implementation backlog. Include search hosting, WebSocket capacity, broadcast/indexing workers and variant migration effort. Assign an owner and estimate to every P0 and F1–F4 item before scheduling the release.

## Production release checklist

- [x] P0-01: version-control baseline, hosted CI, verified production archive and main-branch protection; owner PR review/merge pending.
- [ ] P0-02–P0-06: supported environment and tested account/data protection.
- [ ] P0-07–P0-11: exact pricing, safe concurrent stock, payment recovery/refunds and retained records.
- [ ] P0-12–P0-14: supervised services, delivered email, persistent storage, restore drill and working alerts.
- [ ] P0-15–P0-17: MySQL/browser/load checks, migration/rollback rehearsal and customer-facing operations approved.
- [ ] F1: Livewire migration, shared interaction states and component authorization checks passed.
- [ ] F2: variant migration, SKU-level pricing/reservations and historical order snapshots verified.
- [ ] F3: EN/MM advanced search, indexing/rebuild and outage fallback verified.
- [ ] F4: authorized real-time updates, reconnect/fallback and service supervision verified.
- [ ] Actual merchant methods/currencies and shipping/tax/rounding rules confirmed.
- [ ] Every unchecked gate has an assigned owner; all gates checked before accepting real customer payments.

## Code evidence behind the priorities

| Observation | Source |
|---|---|
| OTPs use `rand`, store the code, use session `otp_verified`, and password validation caps length at 15 | [Customer password controller](../app/Http/Controllers/User/UserPasswordController.php), [admin password controller](../app/Http/Controllers/Admin/AdminPasswordController.php), [OTP model](../app/Models/PasswordOtp.php) |
| OAuth runs stateless and matches existing users by email/provider | [SocialLoginController](../app/Http/Controllers/SocialLoginController.php) |
| Review JSON returns a loaded user relation | [ReviewController](../app/Http/Controllers/ReviewController.php), [User serialization](../app/Models/User.php) |
| Admin edits stock directly; currency lookup falls back to 1; product deletion is physical | [Admin ProductController](../app/Http/Controllers/Admin/ProductController.php) |
| Orders cascade on user deletion; item product references become null on deletion | [Orders migration](../database/migrations/2026_02_21_083751_create_orders_table.php), [order-items migration](../database/migrations/2026_02_21_174639_create_order_items_table.php) |
| Payment commits queue email after commit; dispatch exceptions are reported without persisted retry intent in this service | [OrderLifecycle](../app/Services/OrderLifecycle.php) |
| Reservation expiry exists and needs supervised scheduling/monitoring | [Console schedule](../routes/console.php), [StripePayments](../app/Services/StripePayments.php) |
| Current CI targets PHP 8.2/Node 22, SQLite tests and compiled Blade checks | [CI workflow](../.github/workflows/ci.yml), [PHPUnit configuration](../phpunit.xml) |

This is a source-based planning review, not a completed penetration test or production certification. Record implementation results against this note as v2 progresses.
