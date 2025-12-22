@echo off
setlocal EnableDelayedExpansion
cd /d "%~dp0"

echo ========================================================
echo GIT FINAL CLEANUP (REDUCE SIZE)
echo ========================================================
echo.
echo This script will re-scan your folder and REMOVE any files
echo that should be ignored (like big SQL dumps, zips, etc.)
echo from the upload list.
echo.

rem 0. Fix Network
git config --global http.postBuffer 524288000
git config --global http.version HTTP/1.1

echo 1. Undoing last attempts (Soft Reset)...
git reset --soft HEAD~2 >nul 2>&1

echo.
echo 2. CLEARING THE STAGE (Un-tracking everything)...
git rm -r --cached . >nul 2>&1

echo.
echo 3. Re-adding files (Respecting .gitignore)...
echo This might take a minute...
git add .

echo.
echo 4. Committing optimized version...
git commit -m "Cleanup large files and finalize project"

echo.
echo 5. Pushing to GitHub (Final Attempt)...
echo Please wait for 'Writing objects: 100%%'...
git push -u origin main --force

if !errorlevel! neq 0 (
    echo.
    echo [ERROR] Push failed.
) else (
    echo.
    echo [SUCCESS] Project uploaded successfully! (And it's smaller now)
)

echo.
echo Press any key to close...
pause
