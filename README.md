# Jariklurik - Panduan Setup Localhost

Halo guys! Panduan ini bakal bantuin kalian buat nge-setup aplikasi Jariklurik di laptop kalian masing-masing. Santuy aja, ikutin langkah-langkahnya ya!

## Persiapan (Wajib Punya)

-   **XAMPP** (buat Database): Download dan install XAMPP dari [apachefriends.org](https://www.apachefriends.org/index.html).
-   **PHP**: Udah include di dalem XAMPP kok.
-   **Web Browser**: Chrome, Firefox, atau Edge (bebas dah).

## Cara Install & Setup

### 1. Setup Database
1.  Buka **XAMPP Control Panel** terus nyalain **MySQL** (Apache opsional sih kalo kalian pake `spark serve`, tapi nyalain aja gapapa).
2.  Buka browser terus ke `http://localhost/phpmyadmin`.
3.  Klik **New** buat bikin database baru.
4.  Kasih nama databasenya: `jariklurik`
5.  Klik **Create**.
6.  Pilih database `jariklurik` yang baru dibuat tadi di sidebar kiri.
7.  Klik tab **Import**.
8.  Pilih file `jariklurik.sql` yang ada di folder project ini.
9.  Klik **Import** di paling bawah.

### 2. Konfigurasi Environment
File `.env` udah kita settingin buat local development, jadi aman guys.
-   **Base URL**: `http://localhost:8081/`
-   **Database**: `jariklurik` (User: `root`, Password: kosongin aja)

### 3. Jalanin Aplikasi (Rekomendasi)
Pake script yang udah kita siapin biar gampang dan port-nya konsisten di 8081.

1.  Buka terminal kalian (Command Prompt atau PowerShell).
2.  Masuk ke folder project kalian.
3.  Jalanin perintah ini:
    ```bash
    .\run.bat
    ```
4.  Buka browser terus akses: `http://localhost:8081`

### 4. Cara Manual (Alternatif)
Kalo script `run.bat` gabisa jalan:
1.  Masuk ke folder `ci`: `cd ci`
2.  Jalanin manual: `php spark serve --port 8081`

## Script Bantuan (Tools)
Kita udah buatin beberapa script ajaib biar hidup kalian lebih mudah:

-   **`manual_fix_logos.bat`**: Script ini buat **benerin logo perusahaan yang ilang**.
    -   *Cara pake*: Tinggal klik 2x aja. Script-nya pinter kok, dia bakal otomatis nyari folder project kalian (mau ditaruh dimana aja) dan nge-copy semua logo ke tempat yang bener.
-   **`commit_and_push.bat`**: Script buat **upload kodingan ke GitHub**.
    -   *Cara pake*: Klik 2x, terus ketik pesan update kalian (misal: "Benerin fitur login"), tekan Enter. Beres! Kodingan langsung terbang ke GitHub.

---

## Masalah yang Sering Muncul (Troubleshooting)

-   **"Inspectable WebContents" di port 8080?**
    Port 8080 sering dipake aplikasi lain (kayak Steam). Makanya kita default-in pake port **8081** biar ga tabrakan.
-   **Database Error?**
    Pastiin MySQL di XAMPP udah nyala ya guys.
-   **"Command not found" pas ketik php?**
    Itu berarti PHP belum masuk PATH Windows. Tambahin folder PHP kalian (misal `C:\xampp\php`) ke Environment Variable Windows.

---

## Changelog Teknis & Log Perbaikan (Buat Laporan)

Ini dokumentasi masalah teknis yang kita temuin pas setup dan gimana solusinya. Lumayan buat bahan laporan guys.

### 1. Port Conflict (8080)
-   **Masalah**: Aplikasi gabisa jalan di port default 8080, biasanya karena dipake service lain (kayak Steam Client WebHelper).
-   **Solusi**: Ganti port default jadi **8081**.
    -   Update `run.bat` buat jalanin `php spark serve --port 8081`.
    -   Update `ci/.env` di bagian `app.baseURL` jadi `http://localhost:8081/`.

### 2. Masalah Pathing (Gambar & Link 404)
-   **Masalah**: Gambar, CSS, sama JS error 404 (not found) dan link-nya mati. Ini gara-gara kodingannya pake "hardcoded paths" (contoh: `src="/image/..."` atau `href="../assets..."`) yang bakal error kalo app-nya dijalain di subfolder atau lewat `spark serve`.
-   **Solusi**: Ganti semua path manual pake helper `base_url()`-nya CodeIgniter.
    -   **File yang diubah**: `header.php`, `login.php`, `layout.php`, `main-banner.php`, `second-banner.php`, `footer.php`.
    -   **Efeknya**: URL-nya jadi dinamis ngikutin server (misal jadi `http://localhost:8081/image/...`), jadi aman mau dijalain dimana aja.

### 3. Logo Perusahaan Hilang (Logic & File Sync)
-   **Masalah**: Logo perusahaan tertentu (kayak "PT Duta Wibawa") malah muncul logo default Jariklurik, padahal filenya ada di backup.
    -   **Penyebab 1 (Logika Kodingan)**: Di `JobVacancy`, logic `formatDataFrontendModel`-nya malah nambahin tulisan `image/` atau `uploads/` di depan path database (padahal di database udah lengkap `/assets/images/...`), jadinya path-nya ngaco kayak `image/assets/images/...`.
    -   **Penyebab 2 (File Sync)**: `spark serve` itu jalanin file dari folder `ci/public`, tapi file logo aslinya ada di `public_html/assets` (struktur lama XAMPP) dan `staging/assets`. Jadi filenya ga ketemu.
-   **Solusi**:
    1.  **Benerin Logic**: Sederhanain code di `ci/app/Entities/JobVacancy.php` biar langsung percaya sama path dari database (`base_url(ltrim($this->company->logo, '/'))`) dan apus cek file (`file_exists`) yang bikin ribet server.
    2.  **Sync File**: Kita bikin script `manual_fix_logos.bat` buat nge-copy **SEMUA** logo perusahaan dari backup `public_html` ke folder `ci/public` dan `staging`. Jadi filenya pasti ada dimanapun kalian jalanin app-nya.

### 4. CORS & Loading Terus-terusan
-   **Masalah**: List lowongan kerja muter-muter doang (loading infinite).
-   **Penyebab**: Request AJAX dari frontend diblokir sama Browser (CORS/Cross-Origin Resource Sharing) gara-gara domain di `app.baseURL` beda sama domain browser kalian.
-   **Solusi**: Samain `app.baseURL` di `.env` jadi `http://localhost:8081/`. Beres deh.

### 5. Koneksi Database
-   **Masalah**: Gabisa connect database pake settingan produksi.
-   **Solusi**: Update `ci/.env` pake settingan standar XAMPP:
    -   Host: `localhost`
    -   User: `root`
    -   Password: (kosong)
    -   DB Name: `jariklurik`

### 6. Bug UI & Sorting ("Big X" & Filter Delay)
-   **Masalah**:
    1.  **"Big X"**: Ada icon silang "X" segede gaban pas filter aktif, nutupin layar. Ini gara-gara icon SVG-nya gapunya aturran size (width/height).
    2.  **Sorting Delay**: Pas pilih "Terbaru" atau "Terlama", list-nya ga langsung update. Harus diklik dua kali baru mau. Ternyata API-nya dipanggil pake nilai variabel lama.
-   **Solusi**:
    1.  **UI Redesign**: Ganti SVG mentah itu pake komponen badge "Hapus Filter" yang rapi.
    2.  **Logic Fix**: Benerin `job-vacancy-list.php` biar ngambil nilai radio button yang baru **sebelum** manggil fungsi ambil data.
