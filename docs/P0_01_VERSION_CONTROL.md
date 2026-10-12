# P0-01 — version control and reproducible releases

Implementation started: 12 October 2026. Local implementation is under verification. Hosted checks and branch protection will be recorded after connecting the supplied GitHub repository.

Scope: P0-01 plus the requested EcoShop v2.0 branding. Further v2 upgrades proceed one item at a time.

## Source and review process

The stabilized local source is committed and tagged `baseline-stabilized-2026-10-12`. Preserve this tag as the pre-v2 rollback reference. The remote repository is `https://github.com/Ph4nt0m-C0R3/eco-shop`; its existing main history will be preserved.

Use a feature branch for each plan item, review its pull request and require the `check` CI job before merging into `main`. Enable a main-branch rule requiring pull requests, at least one independent approval, passing checks, resolved conversations and blocking force pushes/deletion. Apply administrator bypass rules deliberately. A solo maintainer should establish a practical review policy before enabling an approval rule that they cannot satisfy themselves.

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

Pending final clean-checkout, artifact and hosted-CI verification. Record results here and update the production-plan progress table after verification; retain pending host administration separately.

Implementation references: [Git archive/export rules](https://git-scm.com/docs/git-archive), [Composer locked installs](https://getcomposer.org/doc/03-cli.md#install-i), [GitHub build/test workflows](https://docs.github.com/en/actions/tutorials/build-and-test-code/nodejs).
