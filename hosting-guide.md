# 🌐 Panduan Deployment Jariklurik ke Hostinger (Hostinger 101)

Panduan langkah demi langkah untuk meng-online-kan aplikasi Jariklurik di layanan hosting Hostinger. Panduan ini mencakup persiapan aset, konfigurasi database, dan keamanan production.

---

## 📋 Prasyarat (Persiapan Local)

Sebelum upload, kita harus "mematangkan" aplikasinya dulu di laptop.

### 1. Build Aset Frontend (PENTING!)
Shared hosting biasanya **tidak memiliki Node.js** untuk running `npm run dev`. Jadi kita harus compile aset CSS dan JS dulu.

1.  **Build TailwindCSS (Minified)**:
    Buka terminal di folder project, jalankan:
    ```bash
    npx tailwindcss -i ./resources/css/tailwind.css -o ./public/css/app.css --minify
    ```

2.  **Build React Components (Security Dashboard)**:
    Jalankan perintah ini untuk membuat file bundle JS:
    ```bash
    npm run security-build
    ```
    *Pastikan folder `dist` atau file hasil build di `public/assets` atau `public/js` sudah terupdate.*

### 2. Bersihkan & ZIP Project
Kita akan mengompres folder project agar mudah di-upload.

1.  **Hapus/Exclude Folder Ini** (Jangan di-upload!):
    *   ❌ `node_modules` (Berat, tidak dipakai di production)
    *   ❌ `.git` (Data version control, bahaya jika terekspos)
    *   ❌ `.github`
    *   ❌ `tests`
    *   ❌ `writable/cache/*` (Isinya hapus, tapi folder `cache` biarkan)
    *   ❌ `writable/logs/*`
    *   ❌ `writable/session/*`

2.  **ZIP Folder Project**:
    *   Select semua file (kecuali yang di-exclude di atas).
    *   Klik Kanan -> **Send to** -> **Compressed (zipped) folder**.
    *   Beri nama: `jariklurik_prod.zip`.

### 3. Export Database Local
1.  Buka **phpMyAdmin** local (`localhost/phpmyadmin`).
2.  Pilih database `jariklurik`.
3.  Klik tab **Export**.
4.  Pilih method **Quick**, format **SQL**.
5.  Klik **Go** -> Download file `.sql` (misal: `jariklurik_final.sql`).

---

## 🚀 Langkah Upload di Hostinger

### 1. Setup Database di Hostinger
1.  Login ke **hPanel** (Hostinger Panel).
2.  Masuk ke menu **Databases** -> **Management**.
3.  Buat Database Baru:
    *   **MySQL Database Name**: misal `u123456789_jariklurik`
    *   **MySQL Username**: misal `u123456789_admin`
    *   **Password**: *Buat password yang SANGAT KUAT (simpan di Notepad dulu)*.
4.  Klik **Create**.
5.  Klik tombol **Enter phpMyAdmin** di sebelah database yang baru dibuat.
6.  Di phpMyAdmin Hostinger:
    *   Klik tab **Import**.
    *   Upload file `jariklurik_final.sql` dari komputer.
    *   Klik **Go**. (Pastikan sukses 100%).

### 2. Upload File Website
1.  Di hPanel, masuk ke menu **Files** -> **File Manager**.
2.  Masuk ke folder **public_html**.
    *   *Jika ini domain utama, upload di dalam `public_html`*.
    *   *Jika subdomain (misal: `app.domain.com`), buat folder baru dulu*.
3.  Klik icon **Upload** (kanan atas) -> pilih `jariklurik_prod.zip`.
4.  Setelah upload sukses, Klik Kanan pada file ZIP -> **Extract**.
    *   Extract ke folder saat ini (`.`).
5.  Hapus file ZIP-nya jika sudah selesai.

---

## ⚙️ Konfigurasi Production

Ini bagian paling krusial agar website jalan!

### 1. Mengatur Environment (`.env`)
1.  Cari file `.env` di File Manager (jika tidak ada, rename `env` menjadi `.env`).
2.  Klik Kanan -> **Edit**.
3.  Ubah baris-baris berikut:

    ```ini
    # Environment (PENTING: Ubah ke production untuk keamanan)
    CI_ENVIRONMENT = production

    # Base URL (Sesuaikan dengan domain kamu)
    # Jangan lupa akhiri dengan garis miring '/'
    app.baseURL = 'https://namadomainkamu.com/'

    # Database (Pakai user & pass yang dibuat di Step 1 Hostinger tadi)
    database.default.hostname = localhost
    database.default.database = u123456789_jariklurik
    database.default.username = u123456789_admin
    database.default.password = PasswordSuperKuatTadi123!
    database.default.DBDriver = MySQLi
    ```
4.  **Save**.

### 2. Menyesuaikan Folder `public` (Struktur CI4)
Secara default, CodeIgniter 4 punya folder `public`. Di hosting, kita ingin root domain langsung mengakses isi `public` tanpa ngetik `domain.com/public`.

**Cara Aman (Recommended)**:
1.  Pindahkan **ISI** folder `public` (index.php, .htaccess, favicon.ico, assets, dll) ke **LUAR** folder `public` (langsung di root `public_html`).
2.  Hapus folder `public` yang sudah kosong.
3.  Edit file `index.php` (yang baru dipindah ke root):
    *   Cari baris: `require FCPATH . '../app/Config/Paths.php';`
    *   Ubah menjadi: `require FCPATH . 'app/Config/Paths.php';`
    *   (Hapus tanda `../` karena file `index.php` sekarang sejajar dengan folder `app`).

**Cara Alternatif (.htaccess Rewrite)**:
Jika malas memindah file, buat file `.htaccess` baru di root `public_html` dan isi kode ini untuk me-redirect ke folder public:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### 3. Folder Permissions (Izin Akses)
Pastikan folder `writable` bisa ditulis oleh sistem:
1.  Klik Kanan folder `writable`.
2.  Pilih **Permissions**.
3.  Centang **Write** untuk Owner (biasanya 755 sudah cukup, tapi jika error log permission denied, coba 775).
4.  Lakukan hal sama untuk folder `public/uploads` atau `public/file` agar user bisa upload CV/Foto.

---

## 🛡️ Security Check & Troubleshooting

### 1. Perbaiki Masalah Umum
*   **Error 404 (Page Not Found)**:
    *   Pastikan file `.htaccess` ada di root (sebelah `index.php`). CodeIgniter butuh ini untuk *pretty URLs*.
    *   Isi default `.htaccess`:
        ```apache
        <IfModule mod_rewrite.c>
            Options +FollowSymlines
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^([\s\S]*)$ index.php/$1 [L,NC,QSA]
        </IfModule>
        ```

*   **Error "Whoops!" (CI_ENVIRONMENT = production)**:
    *   Jika environment = production, CodeIgniter menyembunyikan detail error (layar putih/Whoops).
    *   Untuk debugging, ubah sementara `.env` jadi `development`. **Jangan lupa kembalikan ke `production` setelah fix!**
    *   Cek log error di folder `writable/logs/log-YYYY-MM-DD.log`.

### 2. Security Dashboard & API
Karena kita pakai fitur security baru:
*   Pastikan tabel `web_visitors` dan `security_logs` ada di database (sudah ter-import).
*   Test akses endpoint API: `https://domain.com/api/security/stats` (Harus return JSON).

### 3. Optimization (Opsional)
*   **PHP Version**: Di hPanel -> **PHP Configuration**, pastikan pakai **PHP 8.1** atau 8.2 (sesuai dev local).
*   **Extensions**: Pastikan extension `intl`, `gd`, `mysqli`, `json`, `mbstring` aktif (biasanya default aktif di Hostinger).

---

## 🎯 Final Checklist
- [ ] Domain bisa diakses dan tidak 404.
- [ ] Login admin/company/applicant berfungsi.
- [ ] Upload foto/CV berhasil (Permission OK).
- [ ] Dashboard Security menampilkan data (Visitor tracking jalan).
- [ ] Environment setted to `production`.

**Selamat! Jariklurik sudah online! 🌍**

---


## Troubleshooting Log (2026-01-29) - Detailed Repair Report

This log documents the specific technical fixes applied to resolve the Staging (Hostinger) and Localhost synchronicity issues.

---

### 1. 🛣️ Routing Fix (404 Error on Subpages)
 **Issue**: Visiting `staging.jariklurik.id/lowongan-kerja` caused a 404, but `.../index.php/lowongan-kerja` worked. CodeIgniter was strictly enforcing `index.php` in the URI.

**File**: `app/Controllers/PageController.php` (Line 23)

**❌ Before (Old Code)**:
```php
$page = $this->request->getPath();
// Only checked for explicitly empty or root path
if (empty($page) || $page === "/") { 
    $page = "lowongan-kerja"; 
}
```

**✅ After (Fix)**:
We added a check for `"index.php"` to handle the discrepancy between URL rewriting and CodeIgniter's internal router request parsing.

```php
$page = $this->request->getPath();
// Added check for "index.php" string
$page = (empty($page) || $page === "/" || $page === "index.php") ? "lowongan-kerja" : $page;
```

---

### 2. ⚙️ Server Configuration (.htaccess Compatibility)
**Issue**: Hostinger's subdomain structure (`domains/jariklurik.id/public_html/staging`) conflicted with the standard `RewriteBase /` directive, causing routing loops or 404s.

**File**: `staging/public/.htaccess`

**❌ Before**:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /  <-- Causes issues on some subdomains
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>
```

**✅ After**:
Disabled `RewriteBase`. This forces Apache to calculate the base path dynamically relative to the current folder.

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    # RewriteBase /  <-- DISABLED/COMMENTED OUT
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>
```

---

### 3. 🗄️ Database Connection (500 Internal Server Error)
**Issue**: Site crashed immediately. The `.env` file contained a typo in the username and used an IP address that Hostinger rejected (preferred `localhost`).

**File**: `staging/.env`

**❌ Before**:
```ini
database.default.hostname = 127.0.0.1
database.default.username = u123_radiant  <-- TYPO (Missing 'o')
```

**✅ After**:
Corrected the credentials based on the hosting control panel details.

```ini
database.default.hostname = localhost      <-- FIXED
database.default.username = u123_radianto  <-- FIXED
```

---

### 4. 🔑 Permission System Repair (Missing Sidebar)
**Issue**: "Security Command Center" menu hidden. Database check revealed `security.view` permission was missing entirely from `auth_permissions` table, and `developer` group had no access.

**Fix Method**: **Embedded Controller Script** (Since direct SQL access was slow).

**File Accessed**: `app/Controllers/PageController.php` (Temporary Injection)

**The Logic Injected:**
```php
// 1. Insert Missing Permission
$db->table('auth_permissions')->insert([
    'name' => 'security.view', 
    'description' => 'View Security Dashboard'
]);

// 2. Assign to Groups (Including Developer)
$groups = ['admin', 'superadmin', 'developer'];
foreach ($groups as $g) {
    $db->table('auth_groups_permissions')->insert([
        'group_id' => $g_id, 
        'permission_id' => $perm_id
    ]);
}
```
*Note: This script was run on both Staging (`staging.jariklurik.id/resolve-permissions`) and Localhost (`localhost/jariklurik/resolve-permissions`) then removed.*

---

### 5. 📦 Asset Path & Deployment (White Screen)
**Issue**: The React Dashboard loaded a white screen.
1.  **Wrong Folder**: Files were uploaded to `public_html/assets` (Production) instead of `staging/public/assets` (Staging).
2.  **Missing Manifest**: The `.vite` folder (hidden by default on some systems) was not uploaded.

**Diagnosis**:
Created a probe script `probe_assets.php` that ran `scandir()` on the server to prove the folder was missing.

**File**: `staging/public/probe_assets.php` (Specific Diagnostic Tool)
```php
// Probing exact path expected by CodeIgniter FCPATH
$target = FCPATH . 'assets/security-dashboard';

if (!is_dir($target)) {
    mkdir($target, 0755, true); // FORCE CREATE
    echo "Created folder at: " . $target;
}
```

**Final Path Structure (Corrected)**:
```text
/home/u12345/domains/jariklurik.id/public_html/staging/public/assets/
└── security-dashboard/
    ├── .vite/              <-- MUST EXIST
    │   └── manifest.json   <-- Critical for React loading
    ├── index.js
    ├── index.css
    └── logo.png
```
Once the files were moved to this exact path (created by the probe), the white screen was resolved.
