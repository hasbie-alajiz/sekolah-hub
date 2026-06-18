# Sekolah Hub V1 — Project Summary & Compliance Audit

Dokumen ini berisi rangkuman komprehensif proyek Sekolah Hub V1 serta audit kepatuhan (*compliance audit*) terhadap aturan utama yang ditetapkan dalam `GEMINI.md` dan `.agent/rules/`.

---

## 1. Project Overview & Tech Stack Compliance

Sekolah Hub V1 adalah platform website sekolah *single-tenant* yang dirancang untuk dijalankan di lingkungan *shared hosting* cPanel.

| Kebutuhan (`GEMINI.md`) | Implementasi Proyek Saat Ini | Status Kepatuhan |
| :--- | :--- | :--- |
| **Backend & Framework** | Laravel (PHP 8.1+), Eloquent ORM | **Laravel v13.8** (PHP 8.3), Eloquent ORM | **PATUH** |
| **Database Engine** | Kompatibel dengan MySQL 5.7+ / MariaDB | SQLite untuk pengembangan lokal, query teruji aman untuk MySQL 5.7+ | **PATUH** |
| **Frontend Admin** | Blade + Alpine.js + Tailwind CSS | Blade + Alpine.js + Tailwind CSS + **DaisyUI v5** | **PATUH** |
| **Autentikasi** | Laravel Breeze (Blade stack) | Laravel Breeze (Session-based auth) | **PATUH** |
| **WYSIWYG** | TinyMCE (Self-hosted, tanpa CDN eksternal) | TinyMCE dimuat lokal dari `/vendor/tinymce/tinymce.min.js` | **PATUH** |
| **Export/Import** | Laravel Excel (PhpSpreadsheet) | Terpasang `maatwebsite/excel: ^3.1` | **PATUH** |
| **Storage** | Lokal (`storage/app/public/`) | Terbagi atas `public` disk (media) dan `private` disk (dokumen PPDB) | **PATUH** |
| **Deployment** | Git + `php artisan app:install` | Version control Git aktif, command setup terdefinisi di `composer.json` | **PATUH** |

---

## 2. Kepatuhan Aturan Batasan Arsitektur (Guardrails)

Berdasarkan aturan utama di `GEMINI.md` dan `.agent/rules/architecture.md`, berikut adalah status audit batasan sistem:

1.  **Tidak ada Queue Worker (Mandat: Mutlak):**
    *   *Status:* **PATUH**. Seluruh proses pengolahan data (seperti kompresi gambar di modul Media dan log audit di modul System) berjalan sinkron dalam satu cycle request-response.
2.  **Tidak ada WebSocket/Broadcasting (Mandat: Mutlak):**
    *   *Status:* **PATUH**. Proyek bersih dari dependensi real-time broadcasting.
3.  **Tidak ada Dependency Binary Eksternal (Mandat: Mutlak):**
    *   *Status:* **PATUH**. Modul Media menggunakan ekstensi internal **PHP GD** untuk pengolahan gambar (thumbnail & resize), bukan CLI binary pihak ketiga seperti ImageMagick.
4.  **Kompatibilitas Query MySQL 5.7+:**
    *   *Status:* **PATUH**. Seluruh query database menggunakan Eloquent/Query Builder standar. Tidak ada sintaks spesifik PostgreSQL atau JSON native yang tidak didukung MySQL 5.7.
5.  **Build Asset (Vite) di-commit ke Repo:**
    *   *Status:* **PATUH**. Direktori `public/build/` secara aktif dicatat dan di-commit ke Git.

---

## 3. Modular Monolith Boundaries & Dependency Flow

Struktur folder diletakkan di bawah `app/Modules/`. Setiap modul terisolasi dan mengikuti alur dependensi:
$$\text{Controller} \longrightarrow \text{Action} \longrightarrow \text{Service} \longrightarrow \text{Contract} \longrightarrow \text{Model}$$

### Status Kepatuhan Batas Modul:
*   **Model Lintas Modul:** **PATUH**. Tidak ada model Eloquent yang di-import atau dipakai secara langsung oleh modul lain.
*   **Foreign Key Lintas Modul:** **PATUH**. Migrasi modular tidak membuat constraint foreign key database lintas modul. Sebagai gantinya, relasi disimpan sebagai kolom ID biasa (misalnya `featured_media_id` di tabel `posts`).
*   **Komunikasi Lintas Modul:** **PATUH**. Komunikasi antar modul dijembatani secara longgar menggunakan interface/contract (misalnya `Post` dan `Page` memanggil `MediaServiceInterface` untuk me-resolve URL media gambar).

---

## 4. Matriks Status Implementasi Modul (`.agent/rules/*.md`)

| Modul | Aturan Kunci (`.agent/rules/`) | Status Saat Ini | Keterangan / Komponen Terpasang |
| :--- | :--- | :--- | :--- |
| **System** | RBAC Spatie, global settings, audit logs, User CRUD. | **Lengkap** | `Setting`, `AuditLog` models, `SystemService`, `SystemSeeder`, CRUD User dengan role-permissions mapping. |
| **Media** | Folder virtual, upload disk public/private, generate variants (GD). | **Lengkap** | `Media`, `MediaFolder`, `MediaVariant` models, `MediaService` (resize GD thumbnail & medium). |
| **CMS** | Posts, Pages (hierarchy), Categories (hierarchy), Menus & MenuItems (visual builder, dynamic URL resolver), TinyMCE. | **Lengkap** | `Post`, `Page`, `Category`, `Menu`, `MenuItem` models, `CMSService`, `CMSSeeder`, `PublicCMSController`, admin & public views, visual menu builder Alpine.js. |
| **Gallery** | Album foto, sort order, cover media, draft/published. | *Belum Mulai* | Masuk dalam roadmap fase berikutnya. |
| **Contact** | Form pesan masuk, CF Turnstile, soft delete. | *Belum Mulai* | Masuk dalam roadmap fase berikutnya. |
| **PPDB** | Tahun ajaran, track, dynamic form (EAV), upload dokumen privat, accepted/rejected lifecycle. | *Belum Mulai* | Masuk dalam roadmap fase berikutnya. |
| **Theme** | Filesystem themes (`themes/`), homepage sections JSON. | **Skeleton** | Direktori `themes/school-classic` terdaftar, binding layout publik `public.blade.php` aktif. |

---

## 5. Status Verifikasi Pengujian (Automated & Manual)

Sesuai aturan `convention.md` dan `testing.md`, setiap fitur utama dilengkapi unit/feature test.

*   **Automated Feature Tests:**
    *   **SystemTest.php**: Lulus (menguji setup seeder, pembaruan setting, CRUD user, audit log, masking password).
    *   **MediaUploadTest.php**: Lulus (menguji validasi upload, pembuatan folder, generate variant GD).
    *   **CMSTest.php**: Lulus (menguji otorisasi multi-role, CRUD post/page/category, slug unik otomatis, visual menu builder saving, dan render rute publik).
*   **Hasil Test Suite Akhir:**
    *   Total Tests: **41**
    *   Total Assertions: **172**
    *   Status: **PASSED (Green)**
*   **Database Seeding:**
    *   Command `php artisan db:seed` sukses mengeksekusi `SystemSeeder` dan `CMSSeeder` tanpa kendala relasi maupun kendala database lainnya.
