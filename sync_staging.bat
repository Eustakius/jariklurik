@echo off
cd /d "%~dp0"
echo ===========================================
echo SYNCING TO STAGING (Mirror Mode)
echo ===========================================
echo Source:      %CD%
echo Destination: %CD%\staging
echo.
echo [ATTENTION]
echo 1. This will OVERWRITE files in 'staging' with your current files.
echo 2. Any file in 'staging' that does not exist in root will be DELETED (Mirroring).
echo 3. EXCLUDED: '.env' (Configuration Safe), 'node_modules', '.git', 'tests'.
echo.
echo Press any key to start syncing...
pause

:: Robocopy Command Explanation:
:: /MIR :: Mirror (Copy new, delete extra in dest)
:: /XD  :: Exclude Directories
:: /XF  :: Exclude Files
:: /R:1 :: Retry once on error
:: /W:1 :: Wait 1 second between retries

robocopy "%~dp0." "%~dp0staging" /MIR ^
  /XD staging .git .github .vscode tests node_modules ^
  /XF .env sync_staging.bat .gitignore creating_staging.bat ^
  /R:1 /W:1

echo.
echo ===========================================
echo SYNC COMPLETE!
echo ===========================================
echo Check the output above for any errors.
pause
