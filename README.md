# Sekolah Hub V1

Sekolah Hub V1 adalah platform website sekolah berbasis Laravel yang dirancang khusus untuk deployment di shared hosting (cPanel). Aplikasi ini menggunakan arsitektur **Modular Monolith** untuk menjaga kerapian kode, memudahkan pemeliharaan, serta beroperasi secara single-tenant (satu instalasi untuk satu sekolah).

---

## 🛠️ Tech Stack & Dependensi

- **Core Backend:** Laravel 13.x & PHP 8.3+ (Eloquent ORM)
- **Database:** MySQL 5.7+ / MariaDB / SQLite (lokal dev)
- **Frontend Admin:** Blade + Alpine.js + Tailwind CSS + DaisyUI
- **Autentikasi & RBAC:** Laravel Breeze (Blade stack) & Spatie Laravel Permission (Roles: Super Admin, Admin Sekolah, Editor)
- **WYSIWYG Editor:** Trix Editor (via `tonysm/rich-text-laravel` dalam Attribute Mode)
- **HTML Sanitizer (XSS Protection):** Symfony HTML Sanitizer (via `symfony/html-sanitizer`)
- **Export Data:** Laravel Excel (PhpSpreadsheet)
- **Sistem Penyimpanan:** Laravel Filesystem
    - `storage/app/public/` (untuk media publik seperti berita, galeri, aset tema)
    - `storage/app/private/` (untuk dokumen sensitif/pribadi PPDB)

---

## 🏗️ Arsitektur Monolit Modular

Struktur modular diletakkan di bawah direktori `app/Modules/`. Setiap modul mewakili domain bisnis terisolasi dengan struktur internal yang seragam:

```text
app/Modules/
├── System/       # Konfigurasi global (Settings), User Management, RBAC (Spatie), Audit Log
├── Media/        # Manajemen upload file, folder virtual, generate variant/thumbnail
├── CMS/          # Berita (Posts), Halaman Statis (Pages), Kategori, Menu Navigasi
├── Gallery/      # Album dan Foto Kegiatan Sekolah
├── Contact/      # Form Hubungi Kami, Inbox Pesan Masuk, Proteksi Cloudflare Turnstile
├── Theme/        # Sistem tema dinamis berbasis filesystem (direktori root /themes)
└── PPDB/         # Penerimaan Pesak Baru, Form Dinamis (EAV), Dokumen Private, Pengumuman, Export Excel
```

Setiap modul mengikuti dependency flow berikut:
`Controller` ➔ `Action` (use case tunggal) ➔ `Service` ➔ `Contract` ➔ `Model`

### 🚫 Aturan Utama & Batasan Arsitektur

1.  **Tidak Ada Queue Worker:** Seluruh proses (termasuk upload, kirim pesan, dll.) harus selesai dalam satu cycle HTTP request-response.
2.  **Tidak Ada WebSocket / Broadcasting:** Aplikasi tidak menggunakan koneksi real-time persisten.
3.  **Tidak Ada Dependency Binary Eksternal:** Menghindari dependency seperti CLI LibreOffice atau ImageMagick via binary demi kompatibilitas cPanel.
4.  **Dilarang Foreign Key Lintas Modul:** Relasi antar tabel lintas modul menggunakan referensi ID biasa (tanpa constraint foreign key database) untuk menjaga low-coupling antar domain modul.
5.  **Strict Type & Dependency Injection:** Menggunakan strict types (`declare(strict_types=1);`) dan constructor injection pada seluruh class PHP baru.

---

## 🚀 Setup & Instalasi Pengembangan

### 1. Prasyarat Sistem

- PHP 8.3 atau lebih tinggi
- Composer 2.x
- Node.js & NPM

### 2. Kloning & Inisialisasi Cepat

Proyek ini menyediakan script setup otomatis untuk mempersiapkan environment pengembangan lokal Anda:

```bash
git clone <repository-url> sekolah-hub
cd sekolah-hub
```

Jalankan composer script setup berikut untuk menginstal dependensi PHP & JS, membuat file `.env` dari `.env.example`, menghasilkan application key, memigrasikan database, serta mem-build aset frontend:

```bash
composer run setup
```

### 3. Konfigurasi Database & Keamanan

Sesuaikan koneksi database di file `.env` yang baru dibuat. Secara default, untuk pengembangan lokal Anda dapat menggunakan SQLite (`database/database.sqlite` akan dibuat secara otomatis).

Pastikan Anda mengonfigurasi Cloudflare Turnstile untuk form publik agar terhindar dari spam:

```env
CLOUDFLARE_TURNSTILE_SITE_KEY=your_site_key
CLOUDFLARE_TURNSTILE_SECRET_KEY=your_secret_key
```

### 4. Menjalankan Server Pengembangan

Untuk menjalankan server Laravel (Artisan) dan Vite development server secara bersamaan menggunakan command wrapper:

```bash
npm run dev
```

Server pengembangan akan aktif pada `http://127.0.0.1:8000` (atau port default PHP artisan serve).

---

## 📝 Integrasi Rich Text Editor (Trix)

Proyek ini menggunakan editor Trix melalui paket `tonysm/rich-text-laravel` dalam **Attribute Mode**. Konten editor disimpan langsung di dalam kolom `content` database bawaan model.

Unggahan berkas/gambar di dalam editor (attachments) ditangani secara asinkron (AJAX) ke route `/admin/media/richtext-upload` yang memanfaatkan `MediaServiceInterface` untuk menyimpan aset secara modular. Gaya visual editor diatur menggunakan DaisyUI lewat Blade component `<x-rich-text::styles theme="daisyui" />` pada layout admin.

---

## 🛡️ Keamanan & Sanitasi HTML (Stored XSS Protection)

Aplikasi mengimplementasikan pembersihan (sanitasi) HTML otomatis pada input Rich Text sebelum disimpan ke database guna menghindari celah keamanan **Stored XSS** (Cross-Site Scripting).

- **Mekanisme:** Pembersihan dilakukan oleh utility class `HtmlSanitizer` (berbasis `symfony/html-sanitizer` v7).
- **Aturan Sanitasi:** Semua elemen berbahaya (seperti `<script>`, `onload`, dll.) akan dihapus, sementara tag markup standar (seperti `<strong>`, `<p>`, `<a>`) diizinkan. Tag khusus Trix Editor (seperti `<rich-text-attachment>`, `<figure>`, dan `<figcaption>` beserta atribut pendukungnya) dipertahankan secara utuh agar tidak merusak attachment gambar.
- **Poin Integrasi:** Otomatis berjalan pada event simpan/perbarui melalui CMS Actions (`CreatePostAction`, `UpdatePostAction`, `CreatePageAction`, `UpdatePageAction`).

---

## 🧪 Pengujian (Testing)

Aplikasi ini dilengkapi dengan pengujian unit dan fitur modular yang terstruktur. Untuk menjalankan seluruh test suite:

```bash
composer run test
```

- **Testing Coverage:** Meliputi otentikasi, otorisasi peran (RBAC), CMS (Posts, Pages, termasuk uji sanitasi XSS pada `CMSTest::test_html_content_is_sanitized`), Contact Form, Media upload, alur pendaftaran PPDB (EAV + Private storage upload), rate limiting, serta integrasi Theme.
- Seluruh file pengujian diletakkan pada direktori `tests/Feature/` dan `tests/Unit/`.
