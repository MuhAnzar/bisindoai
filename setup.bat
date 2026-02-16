@echo off
echo ========================================
echo BISINDO CNN - Quick Setup
echo ========================================
echo.

REM Check if Python is installed
python --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Python is not installed!
    echo Please install Python 3.10 first.
    pause
    exit /b 1
)

echo [1/5] Checking Python version...
python --version

echo.
echo [2/5] Creating virtual environment...
if not exist ".venv" (
    python -m venv .venv
    echo Virtual environment created!
) else (
    echo Virtual environment already exists.
)

echo.
echo [3/5] Activating virtual environment...
call .venv\Scripts\activate.bat

echo.
echo [4/5] Installing dependencies...
cd api
pip install -r requirements.txt

echo.
echo [5/5] Verifying installation...
python -c "import tensorflow as tf; print(f'  TensorFlow: {tf.__version__}')"
python -c "import cv2; print(f'  OpenCV: {cv2.__version__}')"
python -c "import numpy as np; print(f'  NumPy: {np.__version__}')"
python -c "import flask; print(f'  Flask: {flask.__version__}')"
python -c "import PIL; print(f'  Pillow: {PIL.__version__}')"

echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Next steps:
echo 1. Terminal 1: php artisan serve
echo 2. Terminal 2: cd api ^&^& python app.py
echo.
pause
