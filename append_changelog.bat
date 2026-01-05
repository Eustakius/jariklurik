@echo off
REM ============================================
REM Append Latest Changelog to README.md
REM ============================================

echo.
echo ========================================
echo   Append Changelog to README.md
echo ========================================
echo.

REM Check if CHANGELOG file exists
if not exist "CHANGELOG_2026-01-05.md" (
    echo [ERROR] CHANGELOG_2026-01-05.md not found!
    echo Please make sure the changelog file exists in the project root.
    pause
    exit /b 1
)

REM Check if README.md exists
if not exist "README.md" (
    echo [ERROR] README.md not found!
    echo Please make sure README.md exists in the project root.
    pause
    exit /b 1
)

echo [INFO] Found CHANGELOG_2026-01-05.md
echo [INFO] Found README.md
echo.

REM Append changelog to README
echo [PROCESS] Appending changelog to README.md...
type "CHANGELOG_2026-01-05.md" >> "README.md"

if %ERRORLEVEL% EQU 0 (
    echo.
    echo [SUCCESS] Changelog successfully appended to README.md!
    echo.
    echo You can now view the updated README.md
) else (
    echo.
    echo [ERROR] Failed to append changelog!
    echo Error code: %ERRORLEVEL%
)

echo.
echo ========================================
echo   Process Complete
echo ========================================
echo.
pause
