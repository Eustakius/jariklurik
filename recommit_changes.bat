@echo off
setlocal EnableDelayedExpansion
cd /d "%~dp0"
chcp 65001 >nul

echo.
echo  ========================================================
echo   🚀 JARIKLURIK GIT SYNC - SMART RECOMMIT
echo  ========================================================
echo.

rem 1. Add ALL changes (Modified + New + Deleted)
echo  🔍 [SYSTEM] Mendeteksi file berubah dan file baru...
git add .

rem 2. Check status
git diff --cached --quiet
if %errorlevel% equ 0 (
    echo.
    echo  💤 [INFO] Tidak ada perubahan baru. Kode masih sama persis!
    goto :END
)

rem 3. Commit
echo.
echo  📦 [STATUS] Ada perubahan yang siap disimpan!
echo.
set "msg="
set /p "msg= 💬 Masukkan pesan update (Enter buat default 'Update'): "
if "!msg!"=="" set "msg=Update"

echo.
echo  💾 [SYSTEM] Menyimpan perubahan: "!msg!"
git commit -m "!msg!"

rem 4. Push
echo.
echo  ☁️  [SYSTEM] Mengirim ke GitHub...
git push origin main

if %errorlevel% neq 0 (
    echo.
    echo  ⚠️  [WARNING] Gagal kirim langsung. Mencoba sinkronisasi (Pull) dulu...
    git pull origin main --no-edit
    
    echo.
    echo  🔄 [RETRY] Mencoba kirim ulang...
    git push origin main
) 

if %errorlevel% equ 0 (
    echo.
    echo  ✅ [SUCCESS] Beres! File baru & editan sudah aman di GitHub.
    echo      ✨ Great work! ✨
) else (
    echo.
    echo  ❌ [ERROR] Masih gagal setelah retry. Cek koneksi atau konflik manual.
)

:END
echo.
echo  👉 Tekan tombol apa aja buat keluar...
pause >nul
endlocal
