param([int]$Port = 8000, [switch]$OfflineMail)
$ErrorActionPreference = 'Stop'
$previewRoot = [System.IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..'))
$previewPhp = Join-Path $previewRoot '.tools/php-8.5.11/php.exe'
if (-not (Test-Path -LiteralPath $previewPhp)) {
    throw 'Install the project-local PHP 8.5.11 runtime first; see docs/P0_02_DEPLOYMENT.md.'
}
$previewDatabase = Join-Path $previewRoot 'audit/v2-baseline/audit.sqlite'
if (-not (Test-Path -LiteralPath $previewDatabase)) {
    throw 'The isolated preview database is missing. Configure your development database and use php artisan serve instead.'
}
Set-Location -LiteralPath $previewRoot
# Keep preview caches separate from regular `php artisan config:cache` output.
# Its isolated database/environment must not be replaced by a cached .env profile.
$previewCache = Join-Path $previewRoot 'audit/v2-baseline/bootstrap-cache'
New-Item -ItemType Directory -Path $previewCache -Force | Out-Null
$env:APP_CONFIG_CACHE = 'audit/v2-baseline/bootstrap-cache/config.php'
$env:APP_ROUTES_CACHE = 'audit/v2-baseline/bootstrap-cache/routes.php'
$env:APP_EVENTS_CACHE = 'audit/v2-baseline/bootstrap-cache/events.php'
$env:APP_SERVICES_CACHE = 'audit/v2-baseline/bootstrap-cache/services.php'
$env:APP_PACKAGES_CACHE = 'audit/v2-baseline/bootstrap-cache/packages.php'
$env:APP_ENV = 'local'
$env:APP_DEBUG = 'true'
$env:APP_URL = "http://localhost:$Port"
$env:DB_CONNECTION = 'sqlite'
$env:DB_DATABASE = $previewDatabase.Replace('\', '/')
$env:CACHE_STORE = 'array'
$env:SESSION_DRIVER = 'database'
$env:SESSION_SECURE_COOKIE = 'false'
# Respect the private .env mail configuration unless offline delivery is explicitly requested.
if ($OfflineMail) { $env:MAIL_MAILER = 'array' }
else { Remove-Item Env:MAIL_MAILER -ErrorAction SilentlyContinue }
$env:QUEUE_CONNECTION = 'sync'
$env:PHP_CLI_SERVER_WORKERS = '1'
# Always reload private .env credentials on a fresh preview launch.
& $previewPhp artisan config:clear
if ($LASTEXITCODE -ne 0) { throw 'Could not clear the preview configuration cache.' }
& $previewPhp artisan route:clear
if ($LASTEXITCODE -ne 0) { throw 'Could not clear the preview route cache.' }
# Preserve the isolated database overrides in Artisan's child server process.
& $previewPhp artisan serve --no-reload --host=127.0.0.1 --port=$Port
exit $LASTEXITCODE
