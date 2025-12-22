@echo off
setlocal
echo ========================================================
echo MANUAL LOGO SYNC SCRIPT (UNIVERSAL)
echo ========================================================
echo.
echo This script works on ANY computer. It automatically detects
echo your folder location.
echo.

rem Get the directory of this batch file
set "ROOT=%~dp0"

set "SOURCE=%ROOT%public_html\assets\images\company\logo"
set "DEST_CI=%ROOT%ci\public\assets\images\company\logo"
set "DEST_STAGING=%ROOT%staging\public\assets\images\company\logo"

echo Detected Root: %ROOT%
echo.

echo 1. Syncing to CI/PUBLIC (for spark serve)...
if not exist "%DEST_CI%" mkdir "%DEST_CI%"
xcopy /Y /S "%SOURCE%\*.*" "%DEST_CI%\"
echo.

echo 2. Syncing to STAGING (for XAMPP/Apache)...
if not exist "%DEST_STAGING%" mkdir "%DEST_STAGING%"
xcopy /Y /S "%SOURCE%\*.*" "%DEST_STAGING%\"
echo.

echo 3. Syncing Fonts to WRITABLE (for Captcha)...
set "DEST_FONTS=%ROOT%writable\fonts"
if not exist "%DEST_FONTS%" mkdir "%DEST_FONTS%"
copy /Y "%ROOT%public_html\fonts\RedHatDisplay-Regular.ttf" "%DEST_FONTS%\Roboto-Regular.ttf"
echo.

echo ========================================================
echo SYNC COMPLETE.
echo Press any key to close this window.
echo ========================================================
pause
endlocal
