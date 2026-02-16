@echo off
echo ========================================
echo Starting BISINDO Python API
echo ========================================
echo.

REM Activate virtual environment if exists
if exist ".venv\Scripts\activate.bat" (
    echo Activating virtual environment...
    call .venv\Scripts\activate.bat
)

REM Navigate to api folder
cd api

REM Check if model exists
if not exist "..\storage\app\public\models\best_abjad.keras" (
    echo [ERROR] Model file not found!
    echo Expected: storage\app\public\models\best_abjad.keras
    echo.
    pause
    exit /b 1
)

REM Run Flask app
echo Starting Flask API...
echo.
python app.py
