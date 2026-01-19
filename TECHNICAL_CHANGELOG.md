# Catatan Perubahan Teknis (Technical Changelog)

Dokumen ini merinci perubahan teknis yang dilakukan pada aplikasi Jariklurik untuk memastikan aplikasi dapat berjalan dengan lancar di lingkungan localhost (XAMPP/Spark) di komputer Anda.

## Masalah Utama
1.  **Gambar dan Aset Tidak Muncul**: Aplikasi menggunakan path (jalur file) absolut (seperti `/image/logo.png`) yang gagal dimuat karena struktur folder di localhost berbeda dengan server production.
2.  **Halaman Tidak Ditemukan (404)**: Konfigurasi server default XAMPP seringkali bermasalah dalam menangani routing CodeIgniter 4 (URL cantik), menyebabkan error 404 saat membuka halaman selain halaman depan.
3.  **Loading Terus-menerus (Infinite Loading)**: Pada halaman lowongan kerja, daftar lowongan tidak muncul dan hanya menampilkan loading spinner selamanya.
4.  **Konflik Port**: Port default `8080` yang biasa digunakan `spark serve` bentrok dengan aplikasi lain (Steam Debugger), membuat server gagal menyala.

## Solusi yang Diterapkan

### 1. Perbaikan Jalur Gambar dan Aset (Pathing Fixes)
Kami mengganti semua jalur file yang 'hardcoded' dengan fungsi dinamis bawaan CodeIgniter.
-   **Perubahan Code**: Mengubah `<img src="/image/logo.png">` menjadi `<img src="<?= base_url('image/logo.png') ?>">`.
-   **Alasan**: Fungsi `base_url()` akan otomatis menyesuaikan alamat link gambar, tidak peduli apakah aplikasi dijalankan di `localhost:8080`, `localhost:8081`, atau subfolder lainnya.

### 2. Pindah ke `php spark serve`
Kami mengubah strategi server dari menggunakan XAMPP Apache murni menjadi server bawaan CodeIgniter (`spark`).
-   **Perubahan Konfigurasi**: Mengatur port default menjadi **8081** di `run.bat` dan `.env`.
-   **Alasan**:
    -   **Lebih Stabil**: Server `spark` dikonfigurasi khusus untuk CodeIgniter, sehingga masalah routing/404 otomatis teratasi.
    -   **Menghindari Bentrok**: Kami memindahkan port ke **8081** untuk menghindari konflik dengan Steam/aplikasi lain di port 8080.

### 3. Optimasi Kecepatan (Mode Production)
Agar aplikasi terasa lebih cepat dan ringan.
-   **Perubahan**: Mengubah `CI_ENVIRONMENT` di `.env` menjadi `production` dan menambahkan `loading="lazy"` pada gambar banner besar.
-   **Alasan**: Mematikan fitur debugging (toolbar merah di bawah) yang memakan banyak memori, serta menunda download gambar besar sampai benar-benar dibutuhkan browser.

### 4. Perbaikan Infinite Loading (Error CORS & Javascript)
Masalah ini membuat data lowongan kerja gagal diambil dari "API" server.
-   **Penyebab**: Browser memblokir permintaan data karena alamat server di konfigurasi (`http://localhost:8081/`) memiliki tanda garis miring di akhir, sedangkan browser mengirim alamat tanpa garis miring (`http://localhost:8081`). Server menganggap ini sebagai serangan keamanan (CORS) dan menolaknya.
-   **Solusi (`JWTAuth.php`)**: Memperbarui kode filter keamanan untuk mengabaikan tanda garis miring (slash) di akhir URL.
-   **Solusi Frontend (`job-vacancy-list.php`)**: Menambahkan penanganan error di kode Javascript. Jika terjadi error lagi di masa depan, aplikasi tidak akan "hang" (loading terus), melainkan menampilkan pesan error "Gagal memuat data".
-   **Perbaikan Gambar Logo**: Memperbaiki kode backend agar URL logo perusahaan selalu lengkap menggunakan `base_url()`, sehingga gambar tidak rusak (broken image) lagi.
-   **Perbaikan Layout Dropdown**: Memperbaiki CSS pada dropdown "Negara" yang terpotong/kekecilan agar tampilannya pas dan rapi.

## Cara Menjalankan Aplikasi

Cukup gunakan file **`run.bat`** yang telah kami buat:
1.  Buka terminal/CMD.
2.  Ketik `.\run.bat` lalu tekan Enter.
3.  Aplikasi akan otomatis terbuka di browser pada alamat `http://localhost:8081`.

*Catatan: Pastikan XAMPP (MySQL) tetap menyala untuk database.*

### 5. Visitor Tracking & API Stabilization (19 Januari 2026)
Masalah ini membuat data statistik pengunjung di dashboard tidak muncul (tetap nol) dan dashboard mengalami error koneksi.
-   **Penyebab 1 (Casing Logic)**: Filter tracking menggunakan kode yang membedakan huruf besar/kecil saat mengecek metode "GET". Karena server mengirimkan "GET" (huruf besar) dan kode mengecek "get" (huruf kecil), tracking selalu dilewati.
-   **Penyebab 2 (Model Restriction)**: Model `WebVisitorModel` memblokir penyimpanan data `page_url` dan `last_activity` karena alasan keamanan (tidak ada di daftar *allowed fields*). Padahal ini adalah data utama yang dibutuhkan dashboard.
-   **Penyebab 3 (API Response Corruption)**: Script `preload.php` mencetak teks "Loaded: ..." langsung ke browser. Teks ini merusak format data JSON yang dikirim ke dashboard, menyebabkan error "NetworkError" karena browser tidak bisa membaca data yang bercampur teks.
-   **Solusi Terpadu**:
    1.  Menormalkan pengecekan metode request menggunakan `strtolower()`.
    2.  Membuka izin penyimpanan field `page_url` dan `last_activity` di model.
    3.  Mematikan (memberikan komentar) pada perintah `echo` di `preload.php` agar response API bersih dan valid.
    4.  Mengahktifkan kembali filter `visitor-tracker` di file `Config/Filters.php`.
