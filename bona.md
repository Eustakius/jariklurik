# Catatan Setup & Perbaikan Jarik Lurik (Localhost Bona)

Dokumen ini berisi rangkuman lengkap dari semua langkah instalasi, konfigurasi, dan perbaikan kode yang telah dilakukan untuk menjalankan website `jariklurik` di laptop ini via XAMPP.

---

## 1. Setup Awal (Environment)

### A. Repository & Tools
1.  **Git**: Diinstall via `winget`. Repo dicloning ke `c:\xampp\htdocs\jariklurik`.
2.  **PHP**: Menggunakan PHP v8.2.12 bawaan XAMPP (`C:\xampp\php\php.exe`).
3.  **Composer**: Diinstall manual (v2.9.2). Dependency diinstall dengan command:
    ```bash
    composer install --ignore-platform-reqs
    ```
4.  **Node.js**: Diinstall version `24.12.0` (LTS) beserta NPM untuk frontend.
    ```bash
    npm install
    ```

### B. Konfigurasi Server (XAMPP & PHP)
1.  **Enable Intl Extension**:
    -   File `php.ini` dimodifikasi.
    -   Baris `;extension=intl` diubah menjadi `extension=intl` (titik koma dihapus).
2.  **MySQL Config (`my.ini`)**:
    -   Ditemukan error "MySQL Server Has Gone Away".
    -   **Solusi**: `max_allowed_packet` dinaikkan menjadi **64MB** via command global.

### C. Konfigurasi Aplikasi (`.env`)
File `.env` dibuat dengan setting berikut agar sesuai port 8081:
```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8081/'
database.default.hostname = localhost
database.default.database = jariklurik
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

---

## 2. Perbaikan Database

### A. Setup Awal
-   Database `jariklurik` dibuat.
-   Import file `jariklurik.sql`.

### B. Masalah & Fix
1.  **Error "Unknown Column google2fa_secret"**:
    -   Table `users` tidak punya kolom untuk kunci 2FA.
    -   **Fix**: Menambahkan kolom manual via SQL:
        ```sql
        ALTER TABLE users ADD COLUMN google2fa_secret VARCHAR(255) NULL AFTER user_type;
        ```
    -   *Catatan*: Setelah file SQL baru diganti, database di-reset ulang (Drop -> Create -> Import), dan kolom sudah aman.

---

## 3. Perbaikan Script & Kode (Fixing Bugs)

Berikut adalah daftar file yang dimodifikasi untuk memperbaiki error yang muncul:

### A. Script `run.bat` (Tidak Jalan)
Script asli mencoba masuk ke folder `ci` yang salah dan tidak mengenali path PHP.
**Perubahan (`run.bat`):**
```batch
@echo off
"C:\xampp\php\php.exe" spark serve --port 8081 %*
```
*(Menggunakan path absolute PHP dan menghapus `cd ci`)*.

### B. Controller `TwoFactorController.php` (Class Not Found)
Library `GoogleAuthenticator` tidak ditemukan karena namespace salah atau library belum diinstall.
**Tindakan:**
1.  Install Library: `composer require sonata-project/google-authenticator`
2.  **Perubahan Kode (Line 6)**:
    ```diff
    - use Google\Authenticator\GoogleAuthenticator;
    + use Sonata\GoogleAuthenticator\GoogleAuthenticator;
    ```

### C. Security Error (CSRF Token Mismatch)
Token validasi form selalu gagal ("SecurityException").
**Tindakan 1: `App\Config\Security.php`**
Mematikan regenerasi token agar koneksi stabil.
```diff
- public bool $regenerate = true;
+ public bool $regenerate = false;
```

**Tindakan 2: `App\Config\Filters.php`**
Mematikan global CSRF filter sementara karena konflik session di localhost.
```diff
- 'csrf' => ['except' => [ ... ]]
+ // 'csrf' => ... (dikomentari)
```

### D. Routing Error (404 Page Not Found)
Jka halaman `/back-end/2fa/enable` di-refresh, muncul 404 karena route hanya menerima POST.
**Perubahan `App\Config\Routes.php` (Line 16):**
Ditambahkan route fall-back agar redirect kembali ke setup.
```php
$routes->post('back-end/2fa/enable', '\App\Controllers\Backend\TwoFactorController::enable', ['filter' => 'auth']);
// Baris baru ditambahkan:
$routes->get('back-end/2fa/enable', '\App\Controllers\Backend\TwoFactorController::setup', ['filter' => 'auth']);
```

---

## 4. Cara Menjalankan Website

Untuk menyalakan server selanjutnya, cukup buka terminal di folder project (`c:\xampp\htdocs\jariklurik`) dan ketik:

```powershell
.\run.bat
```

Lalu buka browser di: **[http://localhost:8081](http://localhost:8081)**
