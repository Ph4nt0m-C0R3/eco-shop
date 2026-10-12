const test = require('node:test');
const assert = require('node:assert/strict');
const {forbidden} = require('../scripts/verify-release.cjs');

test('deployment policy excludes credentials, customer data and demo account seeders', () => {
  for (const file of [
    './.env', '.env.production', '.aws/credentials', '.git/config',
    'database/database.sqlite', 'audit/v2-baseline/audit.sqlite', 'database/seeders/DatabaseSeeder.php',
    'storage/app/private/payment_screenshots/proof.png', 'storage/app/public/products/customer-upload.jpg',
    'public/storage/payment_screenshots/proof.png', 'storage/logs/laravel.log',
    'storage/framework/sessions/session', 'storage/framework/views/compiled.php',
    'bootstrap/cache/config.php', 'public/hot', 'node_modules/package/file.js',
    'storage/app/public/default/kbz_qr.png',
  ]) assert.equal(forbidden(file), true, file);
});

test('deployment policy retains application code, production libraries and default branding assets', () => {
  for (const file of [
    './app/Services/OrderLifecycle.php', 'composer.lock', 'package-lock.json',
    'vendor/autoload.php', 'vendor/laravel/framework/src/Illuminate/Support/Str.php',
    'public/build/manifest.json', 'public/global/img/flags/mm.svg',
    'storage/app/public/default/logoText.png', 'storage/framework/views/.gitignore',
    'storage/framework/cache/data/', 'bootstrap/cache/.gitignore',
  ]) assert.equal(forbidden(file), false, file);
});
