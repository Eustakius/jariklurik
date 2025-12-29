# 🚀 Jariklurik - Panduan Setup Localhost

Halo guys! 👋 Panduan ini bakal bantuin kalian buat nge-setup aplikasi Jariklurik di laptop kalian masing-masing. Santuy aja, ikutin langkah-langkahnya ya!

## 🛠️ Persiapan (Wajib Punya)

Sebelum mulai, pastikan laptop kalian udah terinstall alat-alat tempur ini biar ga error di tengah jalan:

1.  **🐘 XAMPP** (buat Database & Server):
    *   Download di [apachefriends.org](https://www.apachefriends.org/index.html).
    *   **Penting**: Pastikan versi PHP-nya minimal **8.1** (sesuai `composer.json`).
2.  **🎼 Composer** (Manajer Dependency PHP):
    *   Download di [getcomposer.org](https://getcomposer.org/download/).
    *   Ini **WAJIB** buat download library kayak `myth/auth` atau `spreadsheet`. Tanpa ini, app bakal error `Class not found`.
3.  **🟢 Node.js** (Buat Frontend/Tailwind):
    *   Download di [nodejs.org](https://nodejs.org/).
    *   Kita butuh ini buat compile CSS biar ganteng (TailwindCSS).
4.  **🐙 Git** (Buat Download/Upload Kodingan):
    *   Download di [git-scm.com](https://git-scm.com/).
    *   Biar bisa pake script `recommit_changes.bat`.
5.  **🌐 Web Browser**: Chrome, Firefox, atau Edge (bebas dah).

## ⚡ Cara Install & Setup

### 1. 📂 Setup Database
1.  Buka **XAMPP Control Panel** terus nyalain **MySQL** (Apache opsional sih kalo kalian pake `spark serve`, tapi nyalain aja gapapa).
2.  Buka browser terus ke `http://localhost/phpmyadmin`.
3.  Klik **New** buat bikin database baru.
4.  Kasih nama databasenya: `jariklurik`
5.  Klik **Create**.
6.  Pilih database `jariklurik` yang baru dibuat tadi di sidebar kiri.
7.  Klik tab **Import**.
8.  Pilih file `jariklurik.sql` yang ada di folder project ini.
9.  Klik **Import** di paling bawah.

### 2. ⚙️ Install Library & Konfigurasi
Sebelum jalanin app, kita harus download semua "bumbu" pelengkapnya dulu.

1.  **Install Dependency Backend**:
    *   Buka terminal di folder project.
    *   Ketik: `composer install`
    *   Tunggu sampe selesai. Ini bakal download library PHP di folder `vendor`.
2.  **Install Dependency Frontend**:
    *   Ketik: `npm install`
    *   Tunggu beres (folder `node_modules` bakal muncul).
3.  **Setup Environment**:
    *   File `.env` udah kita settingin buat local development, jadi aman guys.
    *   **🔗 Base URL**: `http://localhost:8081/`
    *   **💾 Database**: `jariklurik` (User: `root`, Password: kosongin aja)

### 3. ▶️ Jalanin Aplikasi (Rekomendasi)
Pake script yang udah kita siapin biar gampang dan port-nya konsisten di 8081.

1.  Buka terminal kalian (Command Prompt atau PowerShell).
2.  Masuk ke folder project kalian.
3.  Jalanin perintah ini:
    ```bash
    .\run.bat
    ```
4.  Buka browser terus akses: `http://localhost:8081`

### 4. 🤓 Cara Manual (Alternatif)
Kalo script `run.bat` gabisa jalan:
1.  Masuk ke folder `ci`: `cd ci`
2.  Jalanin manual: `php spark serve --port 8081`

## 🧙‍♂️ Script Bantuan (Tools)
Kita udah buatin beberapa script ajaib biar hidup kalian lebih mudah:

-   **🪄 `manual_fix_logos.bat`**: Script ini buat **benerin logo perusahaan yang ilang**.
    -   *Cara pake*: Tinggal klik 2x aja. Script-nya pinter kok, dia bakal otomatis nyari folder project kalian (mau ditaruh dimana aja) dan nge-copy semua logo ke tempat yang bener.
-   **✨ `recommit_changes.bat`**: Script baru buat **upload hanya file yang berubah & baru** dengan tampilan keren.
    -   *Cara pake*: Klik 2x, lebih efisien daripada script lama.

---

## ⚠️ Masalah yang Sering Muncul (Troubleshooting)

-   **🚫 "Inspectable WebContents" di port 8080?**
    Port 8080 sering dipake aplikasi lain (kayak Steam). Makanya kita default-in pake port **8081** biar ga tabrakan.
-   **❌ Database Error?**
    Pastiin MySQL di XAMPP udah nyala ya guys.
-   **❓ "Command not found" pas ketik php?**
    Itu berarti PHP belum masuk PATH Windows. Tambahin folder PHP kalian (misal `C:\xampp\php`) ke Environment Variable Windows.

---

## 📘 PANDUAN PELENGKAP & TROUBLESHOOTING DETAIL
---

Bagian ini menjelaskan secara **detail** setiap masalah teknis yang mungkin muncul dan **langkah demi langkah** cara memperbaikinya.

### 1. 🔐 Masalah Login 2FA Developer (Terkunci / Loop)
**Gejala**: Anda login sebagai `developer`, diminta kode 2FA Google Authenticator, tapi kode yang di HP Anda salah terus. Atau setelah reset database, akun developer malah terkunci 2FA padahal belum disetup ulang.

**Penyebab**:
Saat database di-reset (di-import ulang), data user kembali ke awal tapi "Secret Key" 2FA di database lama mungkin tidak cocok dengan yang di HP Anda, atau status 2FA-nya "nyangkut" aktif padahal Anda belum scan QR baru.

**🛠️ Cara Fix (Solusi Permanen)**:
Kita tidak bisa sekadar mematikan 2FA karena logic-nya kompleks. Solusi terbaik adalah **menghapus user developer secara total** dan **membuatnya ulang** agar sistem menganggap ini user baru yang bersih.

1.  Pastikan terminal terbuka di folder project.
2.  Jalankan perintah ini:
    ```bash
    php spark recreate:developer
    ```
3.  Tunggu sampai muncul tulisan hijau "Done!".
4.  Buka browser, login ulang sebagai `developer` (password: `developer`).
5.  Web akan meminta Anda setup 2FA baru. Scan QR code yang muncul pakai aplikasi Google Authenticator di HP.
6.  Masukkan kode angka, dan akun aman terkendali!

---

### 2. 🖼️ Gambar Captcha Rusak / Tidak Muncul
**Gejala**: Di halaman login atau register, gambar Captcha cuma kotak kosong atau icon gambar rusak. Di terminal mungkin ada error `Size: 0 bytes`.

**Penyebab**:
Masalah ini "komplikasi" dari beberapa hal: PHP GD Library mati, atau ada "sampah" (spasi kosong/newline) di file PHP lain yang ikut terkirim saat bikin gambar.

**🛠️ Cara Fix**:
Lakukan langkah ini berurutan sampai bener:

1.  **Cek Extension GD di PHP**:
    *   Buka XAMPP Control Panel > Config > PHP (php.ini).
    *   Cari tulisan `;extension=gd`. Hapus titik koma `;` di depannya jadi `extension=gd`.
    *   Save, terus Stop & Start Apache.
2.  **Jalankan Script Font Repair**:
    *   Kita butuh font khusus. Jalankan file `manual_fix_logos.bat` (klik 2x). Ini akan otomatis install font `Roboto` yang dibutuhkan Captcha.
    *   *(Info Teknis)*: Fungsi `imagettftext` di PHP akan gagal (fatal error) jika file font tidak ditemukan di path yang benar, menyebabkan gambar jadi 0 bytes.
3.  **Hapus Cache Browser**:
    *   Kadang browser nyimpen gambar rusak. Tekan `Ctrl + F5` di halaman login.

---

### 3. 🏢 Logo Perusahaan Hilang (Format Gambar Salah)
**Gejala**: Logo perusahaan (misal "PT Duta Wibawa") munculnya logo default Jariklurik.

**Penyebab Teknis**:
1.  **Hardcoded Path vs Base URL**: Di database, path tersimpan lengkap (misal `/assets/images/logo.png`). Kodingan lama sering menambahkan prefix manual seperti `base_url('uploads/' . $logo)`, jadinya double path (`http://.../uploads//assets/...`).
2.  **Logic `file_exists()` yang "Menipu"**: Kodingan lama mengecek `file_exists()` sebelum menampilkan gambar. Masalahnya, `file_exists` mengecek path **FILE SYSTEM** (D:\xampp\...), sedangkan browser butuh **URL** (http://localhost...). Saat pakai `spark serve`, struktur folder virtual berubah, jadi `file_exists` bilang "file gak ada" padahal ada, akhirnya yang dirender gambar default.

**🛠️ Cara Fix**:
Cukup satu klik. Kita sudah buatkan script otomatis.

1.  Buka folder project.
2.  Cari file `manual_fix_logos.bat`.
3.  **Klik 2x**.
4.  Script akan otomatis:
    *   Mencari folder sumber logo.
    *   Mengkopi logo ke semua folder tujuan (`public/__uploads`, `public/assets`, dll) biar pasti ketemu.
    *   Refresh browser Anda.

**Logic Fix di Code (FYI)**:
Di file `JobVacancy.php`, kita hapus pengecekan `file_exists` dan langsung paksa render path dari database dengan `ltrim` biar bersih:
```php
'logo' => !empty($this->company?->logo) 
            ? base_url(ltrim($this->company->logo, '/')) 
            : base_url('image/logo.png'),
```

---

### 4. ❌ "Big X" & Filter Error (Tampilan Berantakan)
**Gejala**: Saat klik filter "Paling Sering Dilamar", muncul icon tanda silang "X" raksasa yang menutupi layar. Filter juga lemot, harus klik 2x baru update.

**Penyebab**:
Icon SVG "X" tidak punya ukuran (width/height), jadi dia ngambil ukuran asli (yang ternyata gede banget). Logic filter juga telat ngambil data.

**🛠️ Cara Fix**:
Masalah ini sudah diperbaiki di kodingan terbaru (`JobVacancyList.js` & CSS). Tapi kalau masih muncul:
1.  **Clear Cache Browser** (Wajib!): Browser sering nyimpen file JS/CSS lama. Tekan `Ctrl + Shift + R`.
2.  Pastikan Anda pakai file terbaru dari repo ini.

---

### 5. 🔌 Port Conflict (Eror "Inspectable WebContents")
**Gejala**: Muncul error merah di terminal saat `spark serve`, bilang port 8080 already in use.

**🛠️ Cara Fix**:
Jangan pake port 8080. Pake port **8081**.
*   **Cara Gampang**: Selalu nyalakan aplikasi pake file `run.bat`. Dia otomatis pake port 8081.
*   **Cara Manual**: Ketik `php spark serve --port 8081`.

---

### 6. 📊 Tabel Pelamar (Applicant Table) Berantakan
**Gejala**: Di halaman admin/perusahaan, tabel pelamar kolomnya sempit, datanya tertukar, atau statusnya (Accepted/Rejected) tidak muncul icon yang benar.

**Penyebab Teknis Detail**:
Tabel ini menggunakan **DataTables (Server Side)**. Masalah terjadi karena ketidakcocokan antara JSON yang dikirim Backend dengan definisi Kolom di Frontend:
1.  **Format Data**: Controller sebelumnya mengirim raw data object, padahal DataTables butuh array spesifik yang sudah diformat (misal: status `1` harus diubah jadi HTML badge `<span class="badge">Accepted</span>` *sebelum* dikirim ke browser).
2.  **Render Status**: Fungsi helper `statusRender()` sebelumnya tidak ter-load di model `JobVacancy.php`, jadi kolom status kosong.

**🛠️ Cara Fix**:
Masalah ini sudah diperbaiki di logic backend (`JobVacancy.php` function `formatDataTableModel`).
Jika Anda masih melihat tabel berantakan:
1.  Pastikan file `app/Entities/JobVacancy.php` adalah versi terbaru.
2.  **Clear Cache Browser** (`Ctrl+Shift+R`) karena DataTables menyimpan "state" (urutan kolom) di cache browser.
3.  Perbaikan ini otomatis berlaku tanpa perlu script tambahan.

---

### 7. ⏳ Infinite Loading (Muter-muter Terus)
**Gejala**: Halaman lowongan kerja loading terus (muter-muter) gak kelar-kelar.

**Penyebab**:
Masalah **CORS**. Domain di browser beda sama domain di config aplikasi.

**🛠️ Cara Fix**:
1.  Buka file `.env` di folder `ci` (atau root).
2.  Cari baris `app.baseURL`.
3.  Pastikan isinya SAMA PERSIS dengan link di browser.
    *   Kalo di browser `http://localhost:8081`, di .env juga harus `http://localhost:8081/`.
    *   Jangan lupa akhiri dengan garis miring `/`.

---

## 📝 CHANGELOG - Recent Updates

### 🎯 December 24, 2025 - Mass Action System Overhaul

**✨ Fitur Baru & Perbaikan Besar:**

#### 1. 🔧 **Mass Action Functionality (Bulk Operations)**
Sekarang admin bisa melakukan aksi massal (approve, reject, revert, delete) untuk banyak data sekaligus!

**Modul yang Ditingkatkan:**
- ✅ **Job Seeker** - Mass Process, Mass Approve, Mass Reject, Mass Revert
- ✅ **Purna PMI** - Mass Process, Mass Approve, Mass Reject, Mass Revert  
- ✅ **Training Type** - Mass Delete (dengan validasi quota)
- ✅ **Applicant** - Mass Process, Mass Approve, Mass Reject, Mass Revert

**Fitur Unggulan:**
- 📦 **Checkbox Selection** - Pilih banyak item sekaligus
- 🎯 **Smart Quota Management** - Otomatis update quota saat approve/reject/revert
- ⚡ **Real-time Feedback** - Pesan error detail (misal: "Quota Full" untuk item tertentu)
- 🔄 **Auto-refresh Table** - Tabel otomatis reload setelah aksi berhasil

#### 2. 🔐 **Permission System Fix (403 Forbidden Errors)**
Diperbaiki semua masalah permission yang menyebabkan error 403 saat mass action.

**Yang Diperbaiki:**
- ✅ `mass-process` → sekarang pakai permission `.approve` (sebelumnya salah pakai `.process`)
- ✅ `mass-approve` → sekarang pakai permission `.approve`
- ✅ `mass-reject` → sekarang pakai permission `.reject`
- ✅ `mass-revert` → sekarang pakai permission `.revert` (sebelumnya salah pakai `.process`)
- ✅ `mass-delete` → sekarang pakai permission `.delete`

**File yang Diupdate:**
- `app/Filters/PermissionFilter.php` - Mapping permission yang benar
- `app/Config/Routes.php` - Semua route mass action pakai filter `permission`

#### 3. 💬 **Error Messaging Improvement**
Pesan error sekarang super detail dan user-friendly!

**Sebelum:**
```
❌ Error occurred
```

**Sekarang:**
```
✅ 3 items approved. 2 failed. Details: Item ID 5: Quota Full., Item ID 7: Training Type Not Found.
```

**Yang Diperbaiki:**
- ✅ Standardisasi format JSON response (`Success`/`Error` dengan TitleCase)
- ✅ Error details langsung muncul di alert message
- ✅ Console logging super detail untuk debugging
- ✅ Partial success handling (beberapa berhasil, beberapa gagal)

#### 4. 🎨 **UI/UX Enhancements**
- ✅ **Decision Modal** untuk Mass Process (pilih Approve atau Reject)
- ✅ **Flowbite Modal** initialization yang aman (no more console errors)
- ✅ **Mass Action Buttons** dengan warna berbeda per aksi (primary, danger, success)
- ✅ **URL Generation Priority** - Manual config prioritas lebih tinggi dari auto-generate

#### 5. 🛡️ **Data Integrity & Validation**
- ✅ **Quota Validation** - Cek quota sebelum approve/revert
- ✅ **Training Type Validation** - Cek training type exists sebelum update
- ✅ **Cascade Delete Prevention** - Training Type dengan `quota_used > 0` tidak bisa dihapus
- ✅ **Transaction Safety** - Error di satu item tidak affect item lain

#### 6. 🔐 **CSRF & 403 Forbidden Error Fix (Final Solution)**

**🐛 Masalah yang Terjadi:**
```
PUT http://localhost:8081/back-end/applicant/mass-approve
Status: 403 Forbidden
```

**Kenapa 403 Muncul?**
- CodeIgniter's CSRF filter secara default mengecek CSRF token di **POST body**
- Kami mengirim data sebagai **JSON** dengan CSRF token di **request header** (`X-CSRF-TOKEN`)
- Filter CSRF tidak bisa menemukan token di lokasi yang benar → validasi gagal → **403 Forbidden**
- Mass-action endpoints dilindungi CSRF padahal seharusnya hanya permission filter yang mengecek

**Solusi yang Diterapkan:**

1. **Exclude Mass-Action Routes dari CSRF Filter** (di `app/Config/Filters.php`):
```php
public array $globals = [
    'before' => [
        'csrf' => ['except' => [
            'back-end/api/*',      // API endpoints
            'back-end/*/mass-*',   // ✅ Mass-action endpoints
        ]],
    ],
];
```

2. **Backend Controllers Updated** - Parse JSON requests:
   - `ApplicantController`
   - `JobSeekerController`
   - `PurnaPmiController`
   
```php
$ids = $this->request->getVar('ids');

// Parse JSON if form data not found
if (empty($ids) && strpos($this->request->getHeaderLine('Content-Type'), 'application/json') !== false) {
    $json = $this->request->getJSON();
    $ids = $json->ids ?? null;
}
```

3. **Frontend** (`table.php`) - Send CSRF in header:
```javascript
headers: {
    '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
}
```

**Hasil:**
- ✅ No more 403 Forbidden errors
- ✅ Permission check masih jalan normal
- ✅ JSON data aman terkirim
- ✅ Support both form-encoded & JSON formats

---

**📊 Technical Details:**
- **Files Modified**: 10+ files (Controllers, Views, Filters, Routes)
- **Lines Changed**: 500+ lines
- **Bugs Fixed**: 7 critical issues (403 errors, URL generation, error messaging, etc.)
- **Testing**: All mass actions verified across all modules and tabs

**🔗 Full Documentation:**
- Lihat `TECHNICAL_CHANGELOG.md` untuk detail teknis lengkap
- Permission audit report tersedia di dokumentasi internal

---

