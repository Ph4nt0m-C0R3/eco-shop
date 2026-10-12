# Dot-source this file to select EcoShop's PHP in the CURRENT PowerShell terminal.
# Set-ExecutionPolicy -Scope Process Bypass; . .\scripts\use-php.ps1
$ecoShopRoot = [System.IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..'))
$ecoShopPhpDirectory = Join-Path $ecoShopRoot '.tools/php-8.5.11'
$ecoShopPhpExecutable = Join-Path $ecoShopPhpDirectory 'php.exe'
if (-not (Test-Path -LiteralPath $ecoShopPhpExecutable)) {
    throw 'EcoShop requires its project-local PHP 8.5.11 runtime. See docs/P0_02_DEPLOYMENT.md.'
}
$env:Path = $ecoShopPhpDirectory + [System.IO.Path]::PathSeparator + $env:Path
Write-Host 'EcoShop terminal now uses PHP 8.5.11. This affects only this terminal session.'
& $ecoShopPhpExecutable --version
