# 🚀 Jariklurik - Panduan Setup Localhost

Halo guys! 👋 Panduan ini bakal bantuin kalian buat nge-setup aplikasi Jariklurik di laptop kalian masing-masing. Santuy aja, ikutin langkah-langkahnya ya!

## 🛠️ Persiapan (Wajib Punya)

-   **🐘 XAMPP** (buat Database): Download dan install XAMPP dari [apachefriends.org](https://www.apachefriends.org/index.html).
-   **🐘 PHP**: Udah include di dalem XAMPP kok.
-   **🌐 Web Browser**: Chrome, Firefox, atau Edge (bebas dah).

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

### 2. ⚙️ Konfigurasi Environment
File `.env` udah kita settingin buat local development, jadi aman guys.
-   **🔗 Base URL**: `http://localhost:8081/`
-   **💾 Database**: `jariklurik` (User: `root`, Password: kosongin aja)

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
