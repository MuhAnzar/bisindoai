@echo off
title BISINDO Development Server
echo ========================================
echo   BISINDO Development Server
echo   Laravel + Python API Auto-Start
echo ========================================
echo.

REM Get current directory
set PROJECT_DIR=%~dp0

REM Check if model exists
if not exist "%PROJECT_DIR%storage\app\public\models\best_abjad.keras" (
    echo [WARNING] Model file not found!
    echo Expected: storage\app\public\models\best_abjad.keras
    echo API will start but predictions may fail.
    echo.
)

REM Start Flask API in background
echo [1/2] Starting Python API in background...
start "BISINDO API" /min cmd /c "cd /d %PROJECT_DIR% && call .venv\Scripts\activate.bat && cd api && python app.py"

REM Wait a moment for API to initialize
timeout /t 2 /nobreak > nul

REM Check if API is running
echo [2/2] Starting Laravel server...
echo.
echo ----------------------------------------
echo   API:     http://127.0.0.1:5000
echo   Laravel: http://127.0.0.1:8000
echo ----------------------------------------
echo.
echo Press Ctrl+C to stop Laravel server.
echo (API window will need to be closed separately)
echo.

REM Start Laravel server (foreground)
php artisan serve

REM When Laravel stops, also try to stop the API
echo.
echo Stopping servers...
taskkill /FI "WINDOWTITLE eq BISINDO API" >nul 2>&1
echo Done.
