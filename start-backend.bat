@echo off
setlocal enabledelayedexpansion

echo ========================================
echo   Laravel Backend - Supabase Edition
echo ========================================
echo.

REM Check PHP installation
echo [1/5] Checking PHP installation...
where php >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] PHP is not installed or not in PATH!
    echo Please install PHP from https://www.php.net/downloads
    echo or install XAMPP/Laragon/WAMP
    echo.
    pause
    exit /b 1
)

echo [OK] PHP is installed
for /f "tokens=*" %%i in ('php --version 2^>^&1 ^| findstr /C:"PHP"') do (
    echo %%i
    goto :phpfound
)
:phpfound
echo.

REM Check Composer installation
echo [2/5] Checking Composer installation...
where composer >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Composer is not installed!
    echo Please install Composer from https://getcomposer.org
    echo.
    pause
    exit /b 1
)

echo [OK] Composer is installed
echo.

REM Check vendor directory
echo [3/5] Checking dependencies...
if not exist "vendor\" (
    echo [INFO] Installing Composer dependencies...
    echo This may take a few minutes...
    call composer install
    if %errorlevel% neq 0 (
        echo [ERROR] Failed to install dependencies!
        echo.
        pause
        exit /b 1
    )
    echo.
) else (
    echo [OK] Dependencies already installed
    echo.
)

REM Check .env file
echo [4/5] Checking environment configuration...
if not exist ".env" (
    echo [WARNING] .env file not found!
    if exist ".env.example" (
        echo Creating from .env.example...
        copy ".env.example" ".env" >nul
        echo [SUCCESS] .env file created
        echo.
        echo [IMPORTANT] Please configure your .env file with Supabase credentials:
        echo - DB_CONNECTION=pgsql
        echo - DB_HOST=aws-1-ap-southeast-2.pooler.supabase.com
        echo - DB_PORT=5432
        echo - DB_DATABASE=postgres
        echo - DB_USERNAME=postgres.dxzkxvczjdviuvmgwsft
        echo - DB_PASSWORD=qW^^_q2ydq+w^)vJ-4
        echo.
        echo Press any key to continue after updating .env...
        pause
    ) else (
        echo [ERROR] .env.example not found! Cannot create .env
        echo.
        pause
        exit /b 1
    )
) else (
    echo [OK] Environment file exists
    echo.
)

REM Test database connection
echo [5/5] Testing Supabase connection...
php artisan db:show >nul 2>&1
if %errorlevel% neq 0 (
    echo [WARNING] Could not verify database connection
    echo Please check your .env file has correct Supabase credentials
    echo.
    echo You can continue anyway, but the server might not work properly.
    echo.
) else (
    echo [OK] Database connection configured
    echo.
)

echo ========================================
echo   Starting Laravel Development Server
echo ========================================
echo.
echo Server will be available at:
echo   ^> http://localhost:8000
echo   ^> http://127.0.0.1:8000
echo.
echo Press Ctrl+C to stop the server
echo ========================================
echo.

REM Start Laravel development server
php artisan serve

if %errorlevel% neq 0 (
    echo.
    echo [ERROR] Failed to start Laravel server!
    echo.
    pause
    exit /b 1
)

endlocal
