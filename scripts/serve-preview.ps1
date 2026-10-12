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
# Preserve the isolated database overrides in Artisan's child server process.
& $previewPhp artisan serve --no-reload --host=127.0.0.1 --port=$Port
exit $LASTEXITCODE
