@echo off
setlocal
set "ECOSHOP_PHP=%~dp0.tools\php-8.5.11\php.exe"
if not exist "%ECOSHOP_PHP%" (
    echo EcoShop requires the project-local PHP 8.5.11 runtime. See docs/P0_02_DEPLOYMENT.md. 1>&2
    exit /b 1
)
"%ECOSHOP_PHP%" %*
exit /b %ERRORLEVEL%
