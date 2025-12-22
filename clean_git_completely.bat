@echo off
setlocal
cd /d "%~dp0"

echo ========================================================
echo GIT RESET (DANGER ZONE)
echo ========================================================
echo.
echo This will DELETE your entire Git history.
echo It will be like you never used Git on this folder.
echo.
echo YOUR FILES (CODE, IMAGES, ETC) WILL BE SAFE.
echo ONLY THE GIT HISTORY WILL BE DELETED.
echo.
echo Are you sure?
pause

echo.
echo 1. Removing hidden .git folder...
rmdir /s /q .git

if not exist .git (
    echo [SUCCESS] Git history cleaned.
    echo.
    echo You can now:
    echo 1. Run 'setup_git.bat' (if you have it)
    echo 2. Or 'commit_and_push.bat' to start fresh
) else (
    echo [ERROR] Failed to delete .git folder.
    echo Try running as Administrator or close other apps using this folder.
)

echo.
echo ========================================================
pause
