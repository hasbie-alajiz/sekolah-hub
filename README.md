# Sekolah Hub V1

Sekolah Hub V1 adalah platform website sekolah berbasis Laravel yang dirancang khusus untuk deployment di shared hosting (cPanel). Aplikasi ini menggunakan arsitektur **Modular Monolith** untuk menjaga kerapian kode dan modularitas fitur, serta beroperasi secara single-tenant (satu instalasi untuk satu sekolah).

---

## 🛠️ Tech Stack

- **Backend:** Laravel 11.x, Eloquent ORM
- **Database:** MySQL 5.7+ / MariaDB / SQLite (lokal dev)
- **Frontend Admin:** Blade + Alpine.js + Tailwind CSS + DaisyUI
- **Autentikasi:** Laravel Breeze (Blade stack)
- **WYSIWYG:** TinyMCE (Self-hosted di `public/vendor/tinymce/`)
- **Export/Import:** Laravel Excel (PhpSpreadsheet)
- **Filesystem:** Laravel Local Filesystem (`storage/app/public/` & `storage/app/private/`)

---

## 🏗️ Arsitektur Monolit Modular

Struktur kode modular diletakkan di bawah direktori `app/Modules/`. Setiap modul mewakili domain bisnis terisolasi:

```text
app/Modules/
├── System/       # Konfigurasi global, User management, RBAC (Spatie), Audit Log
├── Media/        # Manajemen upload file, folder virtual, generate thumbnail
├── CMS/          # Berita (Posts), Kategori, Halaman Statis (Pages), Menu Navigasi
├── Gallery/      # Publikasi Album dan Foto Kegiatan Sekolah
├── Contact/      # Form Hubungi Kami & Inbox Pesan Masuk
├── Theme/        # Sistem tema berbasis filesystem
└── PPDB/         # Penerimaan Siswa Baru, Form Dinamis (EAV), Dokumen, Pengumuman
```

Setiap modul mengikuti dependency flow berikut:
`Controller` ➔ `Action` ➔ `Service` ➔ `Contract` ➔ `Model`

### 🚫 Aturan Batasan Arsitektur
1. **Tidak ada Queue Worker**: Semua proses harus selesai dalam satu cycle HTTP request-response.
2. **Tidak ada WebSocket / Broadcasting**.
3. **Tanpa Dependency Binary Eksternal** (seperti CLI LibreOffice, dll.).
4. **Foreign Key Lintas Modul Dilarang**: Relasi antar tabel lintas modul menggunakan referensi ID biasa (tanpa constraint foreign key database) untuk menjaga low-coupling.

---

## 🚀 Setup & Instalasi Pengembangan

### 1. Prasyarat
Pastikan sistem Anda sudah terpasang:
- PHP 8.1 atau lebih tinggi
- Composer 2.x
- Node.js & NPM

### 2. Kloning Proyek
```bash
git clone <repository-url> sekolah-hub
cd sekolah-hub
```

### 3. Salin Konfigurasi Environment
```bash
copy .env.example .env
```
Sesuaikan konfigurasi koneksi database di file `.env`. Untuk pengembangan lokal yang cepat, Anda dapat menggunakan SQLite.

### 4. Instal Dependensi PHP & JS
```bash
composer install
npm install
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Jalankan Migrasi Database
Seluruh migrasi modular akan dijalankan secara terpusat:
```bash
php artisan migrate
```

### 7. Jalankan Server Pengembangan
```bash
npm run dev
```

---

## 📝 Catatan TinyMCE (Self-Hosted)
Proyek ini menghindari penggunaan CDN untuk TinyMCE. Folder `public/vendor/tinymce/` telah disiapkan dan harus diisi manual dengan file dari **TinyMCE Community edition** agar editor teks dapat dimuat secara lokal.

---

## 🧪 Pengujian
Menjalankan unit dan feature test secara terstruktur:
```bash
php artisan test
```
Struktur folder testing modular terletak di bawah direktori `tests/Feature/` dan `tests/Unit/`.
