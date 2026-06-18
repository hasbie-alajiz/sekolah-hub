# Product Requirements Document (PRD)

## CMS Sekolah + PPDB V1

### Versi

v1.0 (Final)

### Status

Approved

### Target Pengguna

- Sekolah SD
- Sekolah SMP
- Sekolah SMA/SMK
- Madrasah

### Target Infrastruktur

- Shared Hosting (prioritas utama)
- PHP + MySQL/MariaDB
- Tanpa kebutuhan VPS, Docker, Redis, atau queue worker permanen

---

# 1. Latar Belakang

Banyak sekolah membutuhkan website resmi yang dapat dikelola sendiri dan terintegrasi dengan PPDB (Penerimaan Peserta Didik Baru). Solusi yang ada umumnya memiliki dua masalah:

1. Terlalu kompleks dan mahal.
2. Sulit dijalankan pada shared hosting.

Produk ini bertujuan menyediakan CMS sekolah yang ringan, mudah dideploy, fleksibel, serta memiliki modul PPDB terintegrasi.

---

# 2. Tujuan Produk

Menyediakan sistem yang mampu:

- Mengelola website sekolah.
- Mengelola konten berita dan halaman.
- Mengelola galeri.
- Menerima pesan dari pengunjung.
- Menyediakan PPDB online.
- Berjalan optimal pada shared hosting.

---

# 3. Non-Goals (Tidak Masuk V1)

- Plugin marketplace.
- Visual page builder.
- Custom post type builder.
- Theme marketplace.
- Online payment.
- WhatsApp gateway.
- CBT (Computer Based Test).
- Ranking engine otomatis.
- Parent portal.
- Conditional logic PPDB.
- Live theme customizer.

---

# 4. Arsitektur

## Arsitektur Aplikasi

Modular Monolith.

Setiap modul memiliki:

- Model.
- Migration.
- Service.
- Action.
- Controller.
- Route.
- View.

Komunikasi antar modul menggunakan service/contract.

Tidak menggunakan foreign key lintas modul.

---

# 5. Modul Sistem

## Authentication

Fitur:

- Login.
- Logout.
- Reset password.
- Email verification (opsional).

## Authorization

Menggunakan Spatie Laravel Permission.

Role awal:

- Super Admin
- Admin Sekolah
- Editor

---

# 6. Media Module

Fitur:

- Upload file.
- Folder virtual.
- Soft delete.
- Media reusable.
- Thumbnail otomatis saat upload.

Tipe file:

- JPG
- JPEG
- PNG
- WEBP
- PDF

Keputusan:

- media_variants menggunakan tabel terpisah.
- Satu media dapat digunakan oleh banyak modul.

---

# 7. CMS Module

## Posts

Fitur:

- CRUD berita.
- Draft/publish.
- Featured image.
- SEO title.
- SEO description.
- Single author.

## Categories

Fitur:

- Hierarkis.
- Banyak kategori per post.

## Pages

Fitur:

- CRUD halaman.
- Mendukung parent-child.

## Menu

Fitur:

- Multi menu.
- Nested menu.

Keputusan:

- Seluruh entitas publik menggunakan slug.
- Slug bersifat global unik.

---

# 8. Gallery Module

Fitur:

- CRUD album.
- Upload banyak foto.
- Publish/draft.
- Cover album.
- Lightbox publik.

Keputusan:

- Satu media dapat masuk ke banyak album.
- Cover menggunakan cover_media_id.
- Urutan menggunakan sort_order.
- Slug global unik.

---

# 9. Contact Module

Fitur:

- Form kontak publik.
- Inbox admin.
- Filter.
- Ubah status.
- Soft delete.

Field:

- Nama
- Email
- Nomor HP
- Subjek
- Pesan

Status:

- unread
- read
- replied
- archived

Spam Protection:

- Cloudflare Turnstile.

Email:

- Mendukung Cloudflare Email Routing.
- Email notification default OFF.

Metadata:

- Simpan IP Address.
- Simpan User Agent.

---

# 10. Theme Module

Level:
Theme Engine Level 1–2.

Bukan visual builder.

Fitur:

- Memilih theme aktif.
- Mengatur urutan section homepage.
- Mengaktifkan/menonaktifkan section.
- Mengatur konfigurasi theme.

Keputusan:

- Theme dibaca dari filesystem.
- Tidak ada tabel theme.
- Konfigurasi disimpan di settings.
- Admin tidak dapat membuat section baru.

Contoh section:

- Hero
- Sambutan Kepala Sekolah
- Statistik
- Berita
- Galeri
- PPDB
- CTA
- Kontak

---

# 11. PPDB Module

## 11.1 Tahun Ajaran

Fitur:

- Multi tahun ajaran.
- Hanya satu tahun aktif.

Data:

- Nama tahun ajaran.
- Kode.
- Jadwal pendaftaran.
- Jadwal pengumuman.

---

## 11.2 Jalur Pendaftaran

Fitur:

- Multi jalur.

Contoh:

- Zonasi
- Prestasi
- Afirmasi
- Perpindahan Orang Tua

Keputusan:

- Setiap jalur memiliki form sendiri.

---

## 11.3 Form Builder Lite

Fitur:

- Operator membuat field.
- Field berbeda tiap jalur.

Supported Field:

- text
- textarea
- number
- date
- email
- phone
- select
- radio
- checkbox
- file
- heading
- description

Keputusan:

- Conditional logic ditunda.

---

## 11.4 Registrasi

Fitur:

- Draft.
- Submit.
- Lock setelah submit.
- Export Excel.
- Cetak bukti pendaftaran.

Nomor Pendaftaran:
Format khusus.

Contoh:
PPDB-2026-000001

Status:

- draft
- submitted
- under_review
- verified
- accepted
- rejected
- withdrawn

---

## 11.5 Dynamic Data Storage

Pendekatan:
Hybrid Dynamic Form.

Menggunakan:

- registrations
- registration_values

Bukan:

- Kolom statis.
- JSON penuh.

Checkbox:
Disimpan sebagai JSON pada value_text.

---

## 11.6 Dokumen

Fitur:

- Upload dokumen.
- Verifikasi dokumen.
- Catatan verifikasi.

Status:

- pending
- approved
- rejected

Storage:
Private storage.

---

## 11.7 Pengumuman

Fitur:

- Publish serentak.
- Batch pengumuman.

Operator dapat:

- Menentukan waktu publish.
- Mengumumkan berdasarkan batch.

---

# 12. Audit & Logging

Fitur:

- Audit log aktivitas penting.

Mencatat:

- User.
- Action.
- Target data.
- Perubahan.
- IP Address.
- User Agent.

---

# 13. Deployment

Target utama:
Shared Hosting.

Kebutuhan minimum:

- PHP 8.x
- MySQL/MariaDB
- Composer
- Cron Job

Tidak membutuhkan:

- Redis
- Docker
- Supervisor
- Elasticsearch

Update aplikasi:
Manual deployment.

---

# 14. Kriteria Keberhasilan

Produk dianggap berhasil apabila:

- Dapat dijalankan pada shared hosting umum.
- Website sekolah dapat dikelola tanpa bantuan developer.
- PPDB dapat disesuaikan antar sekolah.
- Admin dapat mengelola seluruh konten inti.
- Sistem stabil digunakan oleh sekolah skala kecil hingga menengah.

---

## Tenant Model

Sekolah Hub menggunakan arsitektur single-tenant.

Satu instalasi aplikasi hanya digunakan oleh satu sekolah.

Tidak mendukung multi-tenant pada V1.

---

# 15. Status V1

Final.

Seluruh keputusan produk, arsitektur, dan database telah disetujui sebagai fondasi implementasi.
