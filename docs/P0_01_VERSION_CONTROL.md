# P0-01 — version control and reproducible releases

Completed: 12 October 2026. Source control, release packaging, hosted CI and main-branch protection are implemented and verified. [Pull request #1](https://github.com/Ph4nt0m-C0R3/eco-shop/pull/1) awaits project-owner review and merge.

Scope: P0-01 plus the requested EcoShop v2.0 branding. Further v2 upgrades proceed one item at a time.

## Source and review process

The stabilized local source is committed and tagged `baseline-stabilized-2026-10-12`. Preserve this tag as the pre-v2 rollback reference. The remote repository is `https://github.com/Ph4nt0m-C0R3/eco-shop`; its existing main history is preserved in the v2 branch.

Use a feature branch for each plan item and review its pull request before merging. Main protection is enabled: an up-to-date successful GitHub Actions `check` job, pull requests and resolved conversations are required; force pushes/deletion are blocked and administrator enforcement is enabled. The solo-maintainer policy uses zero required independent approvals, so the owner can review and merge their own PR. Set one or more required independent approvals when a second reviewer is available. This work is left in a draft PR for owner review.

Local hooks are an additional guard; server-side branch protection and CI enforce the shared workflow. Enable the tracked hook after cloning:

```sh
git config core.hooksPath .githooks
```

`scripts/check-repository.cjs` inspects the staged index for prohibited local-data paths and recognizable live credential formats. It reports file paths without printing credentials. This is a guard against common mistakes; review changes and use the Git host's secret scanning as available.

## Clean-checkout verification

Use the exact Node version from `.nvmrc` (24.18.0). P0-01 retains the existing PHP 8.2 application baseline; runtime/framework upgrades belong to P0-02. CI installs the latest available PHP 8.2 patch. The current local PHP 8.2.12 must be upgraded before production.

In an isolated clean checkout:

```sh
composer validate --strict
composer install --prefer-dist --no-interaction
cp .env.example .env
php artisan key:generate
npm ci
npm run check:repository
npm run test:release
npm run build
php artisan test
php scripts/check-blade.php
composer audit
npm audit
```

On Windows use `Copy-Item .env.example .env` and `npm.cmd` where PowerShell execution policy blocks `npm.ps1`. The PHP tests force an in-memory SQLite database. Preserve the main installation's `.env`; the example-copy/key step applies to the new isolated checkout.

The Windows clean install encountered temporary extraction/deletion locks for Faker. Completing the same locked install with `composer install --prefer-source --no-interaction` succeeded; package versions were unchanged. The Linux CI clean dist install passed without this workaround.

## Production artifact

After the tests pass, install production dependencies in that isolated checkout and package its committed source:

```sh
composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
npm run release:package
```

The packager requires a clean tracked source tree, a compiled Vite manifest and Composer metadata showing development packages are absent. It exports the commit using `git archive`, adds production `vendor/` and built assets, checks deployment contents and writes a `release-manifest.json` with source commit, tool/runtime information, lock-file hashes and SHA-256 hashes of packaged files. The output is `dist/ecoshop-v2.0-<commit>.tar.gz` plus an archive checksum file. Existing outputs are never overwritten.

Git/archive and deployment checks exclude `.env` files, audit/SQLite data, customer uploads/proofs, runtime logs/caches, Git metadata, tests, demonstration seeders/factories and sample payment QR assets. Required fallback branding images are tracked and packaged. Provision genuine payment settings/QRs and the real database independently; seeders with demonstration accounts are unavailable in the deployed archive.

Verify an archive before deployment:

```sh
node scripts/verify-release.cjs dist/ecoshop-v2.0-<commit>.tar.gz
```

Compare its SHA-256 with the adjacent checksum file. Reproducibility means rebuilding the same committed source and locked dependency versions with the recorded toolchain, then comparing manifest file hashes. Archive compression timestamps and platform-specific generated Composer files may differ; byte-identical cross-platform archives are not claimed.

Extract releases outside the live application directory. Connect persistent uploads/private proofs and the production environment separately, keep the stable `APP_KEY`, and follow a rehearsed migration/service-restart deployment procedure. Keep the previous verified artifact for rollback; do not reset a live database or regenerate its key. Database-compatible rollback is a P0-16 release requirement.

## Branding

`config/app.php` owns the display brand `EcoShop v2.0` and version `2.0.0`. Customer/admin page titles, shared navigation/footer, authentication branding and order emails use it. Navigation includes a compact `v2.0` badge alongside the existing logo. `.env.example` and the local `APP_NAME` identify version two; no secret values are changed.

## Completion evidence

| Check | Result |
|---|---|
| Clean checkout | Locked Composer and npm installs succeeded; production frontend build passed |
| PHP tests | 46 passed, 196 assertions, in the isolated checkout |
| Compiled Blade | 139 templates checked, 0 failures |
| Release policy | 2 tests passed, covering prohibited data and required deployable assets |
| Branding browser checks | 19 EN/MM/page/viewport cases passed; no server/JavaScript errors, broken images or page-level overflow |
| Hosted Linux CI | [Run 38218714398](https://github.com/Ph4nt0m-C0R3/eco-shop/actions/runs/38218714398) passed all installs, checks, audits, production packaging and artifact upload |
| CI deployment archive | Downloaded and verified locally: 10,991 entries; exclusions and required production files passed; archive checksum matched |
| Windows production archive | Packaging and content verification passed: 11,011 entries; production dependencies and built assets present |
| GitHub main protection | Required PR + up-to-date Actions check + resolved conversations; administrator enforcement; force pushes/deletion blocked |

Verified Linux artifact source: `fb18dfcb9110a11465fd821851f14f301363007e`. SHA-256: `e68801db6fa5fb02f098352e2120324ac1b515293cdf028934af9419a1ffa6bc`. Windows artifact source: `9358548281dcabaec45efa624dfd36756b3627bb`; SHA-256: `6100fde51003d17cbf37479b2e863d0361fe830aa61f2f56f99fe67d4a1fc2b4`. Later completion-note commits receive their own CI artifacts.

The first upgrade is complete. PR review/merge and production deployment are separate actions. P0-02 and F1–F4 remain pending; no later upgrade is started automatically.

Implementation references: [Git archive/export rules](https://git-scm.com/docs/git-archive), [Composer locked installs](https://getcomposer.org/doc/03-cli.md#install-i), [GitHub build/test workflows](https://docs.github.com/en/actions/tutorials/build-and-test-code/nodejs).
