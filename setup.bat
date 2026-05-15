@echo off
SETLOCAL EnableDelayedExpansion
echo =========================================================
echo 💧 AquaPure Safe Multi-Engine Team Setup Initializer
echo =========================================================

:: ── PHASE 1: SYSTEM ENGINE VERIFICATION ──────────────────────
echo 🔍 Phase 1: Checking Local System Runtimes...

where php >nul 2>nul
if %errorlevel% neq 0 (
    echo 📥 PHP runtime missing. Deploying portable instance...
    powershell -Command "[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12; Invoke-WebRequest -Uri 'https://php.net' -OutFile 'php.zip'"
    powershell -Command "Expand-Archive -Path 'php.zip' -DestinationPath 'C:\php' -Force"
    set "PATH=%PATH%;C:\php"
    setx PATH "%PATH%;C:\php" /M >nul 2>nul
    del php.zip
) else ( echo ✅ PHP runtime verified. )

where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo 📥 Composer package engine missing. Building runtime bin nodes...
    php -r "copy('https://getcomposer.org', 'composer-setup.php');"
    php composer-setup.php --install-dir=C:\php --filename=composer >nul 2>nul
    php -r "unlink('composer-setup.php');"
) else ( echo ✅ Composer package manager verified. )

where npm >nul 2>nul
if %errorlevel% neq 0 (
    echo 📥 Node.js runtime missing. Installing MSI package...
    powershell -Command "Invoke-WebRequest -Uri 'https://nodejs.org' -OutFile 'node.msi'"
    msiexec /i node.msi /qn /norestart
    del node.msi
    echo ⚠️ Node.js installer deployed. Teammates may need to reopen terminal windows.
) else ( echo ✅ Node.js runtime verified. )

:: ── PHASE 2: APPLICATION DEPENDENCY INSTALLATION ─────────────
echo 📦 Phase 2: Installing Application Packages...
call composer install --no-interaction
call npm install

:: ── PHASE 3: ENVIRONMENT PRESERVATION ────────────────────────
echo 📝 Phase 3: Structuring Environmental Configurations...
if not exist .env (
    copy .env.example .env >nul
    call php artisan key:generate
    echo ✅ Local .env schema initialized with fresh security key.
) else (
    echo ℹ️ Pre-existing .env detected. Personal file preserved without modifications.
)

:: ── PHASE 4: RE-LINKING DATABASE SCHEMAS ────────────────────
echo 🗄️ Phase 4: Syncing Database Instances...
set "MYSQL_BIN=mysql"
if exist "C:\xampp\mysql\bin\mysql.exe" set "MYSQL_BIN=C:\xampp\mysql\bin\mysql"

echo 🛠️ Running database migrations safely...
"%MYSQL_BIN%" -u root -e "CREATE DATABASE IF NOT EXISTS aquapure CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>nul

if %errorlevel% neq 0 (
    echo ❌ ERROR: Could not talk to XAMPP MySQL engine server.
    echo 💡 FIX: Turn on Apache and MySQL in XAMPP, then restart this setup.
    pause
    exit /b
)

call php artisan migrate

:: ── PHASE 5: ASSET COMPILATION VIA VITE ──────────────────────
echo 🛠️ Phase 5: Compiling Static UI Core Layout Assets...
call php artisan storage:link >nul 2>nul
call npm run build

echo =========================================================
echo 🎯 SUCCESS! Setup complete! Everything is ready to boot.
echo =========================================================
pause
