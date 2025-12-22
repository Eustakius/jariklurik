@echo off
setlocal EnableDelayedExpansion
cd /d "%~dp0"

echo ========================================================
echo GIT QUICK SAVE (UX ENHANCED)
echo ========================================================
echo.

rem 0. FIX NETWORK
git config --global http.postBuffer 524288000
git config --global http.version HTTP/1.1

rem 1. Check Git
if not exist .git (
    echo [INFO] Initializing Git...
    git init
    git branch -M main
    echo.
)

rem 2. Check User
git config user.email >nul 2>&1
if !errorlevel! neq 0 (
    echo [IMPORTANT] Git doesn't know who you are yet.
    set /p "ghemail=Enter your email: "
    set /p "ghname=Enter your name: "
    git config --global user.email "!ghemail!"
    git config --global user.name "!ghname!"
    echo.
)

rem 3. Check Remote
git remote get-url origin >nul 2>&1
if !errorlevel! neq 0 (
    echo [INFO] No GitHub link found.
    set /p "repo_url=Enter GitHub Repo URL: "
    git remote add origin !repo_url!
    echo.
)

rem 4. Add & Commit
echo Checking for changes...
git add .
git diff --cached --quiet
if !errorlevel! equ 0 (
    echo [INFO] No new changes to commit.
    goto :PUSH_STEP
)

set "msg="
set /p "msg=Enter commit message (Press Enter for 'Update'): "
if "!msg!"=="" set "msg=Update"

echo Committing...
git commit -m "!msg!"

:PUSH_STEP
echo.
echo ========================================================
echo READY TO PUSH
echo ========================================================
echo Please be patient.
echo 1. 'Writing objects: 100%%' means upload is DONE.
echo 2. If it stops there, GitHub is processing your files.
echo 3. DO NOT CLOSE until you see "Success" or "Error".
echo.
echo Uploading now...
echo.

git push -u origin main --progress

if !errorlevel! neq 0 (
    echo.
    echo [INFO] First attempt failed or needs sync.
    echo Parsing... 'fetch first' usually means we need to Pull.
    echo.
    echo Syncing (Pulling)...
    git pull origin main --allow-unrelated-histories --no-edit
    
    echo.
    echo Pushing again...
    git push -u origin main --progress
)

if !errorlevel! neq 0 (
    echo.
    echo [ERROR] Push failed.
) else (
    echo.
    echo [SUCCESS] Everything is on GitHub!
)

echo.
echo Press any key to close...
pause
endlocal
