# Sekolah Hub V1 — AI Coding Constitution

Dokumen ini adalah panduan utama untuk seluruh aktivitas coding di project Sekolah Hub V1.
Baca seluruh aturan sebelum menulis kode apapun.

---

## Project Overview

Sekolah Hub adalah platform website sekolah single-tenant berbasis Laravel yang di-deploy
di shared hosting cPanel. Satu instalasi = satu sekolah.

Target pengguna akhir adalah admin sekolah non-teknis, bukan developer.

---

## Tech Stack

- **Backend:** Laravel (PHP 8.1+), Eloquent ORM
- **Database:** MySQL / MariaDB
- **Frontend Admin:** Blade + Alpine.js + Tailwind CSS
- **Auth:** Laravel Breeze (Blade stack)
- **WYSIWYG:** TinyMCE (self-hosted, tanpa CDN eksternal)
- **Export:** Laravel Excel (PhpSpreadsheet)
- **Storage:** Laravel filesystem lokal (`storage/app/public/`)
- **Deploy:** Git + `php artisan app:install`

---

## Aturan Utama yang Tidak Boleh Dilanggar

1. **Tidak ada queue worker.** Seluruh proses harus selesai dalam satu HTTP request-response cycle.
2. **Tidak ada WebSocket atau broadcasting.**
3. **Tidak ada dependency binary eksternal** (LibreOffice, ImageMagick via binary, dll).
4. **Tidak ada fitur di luar scope PRD v1.2.** Jika ragu, tanya sebelum membuat.
5. **Seluruh query harus kompatibel dengan MySQL 5.7+.** Hindari sintaks PostgreSQL.
6. **Build asset (Vite) dijalankan saat development.** Output `public/build/` di-commit ke repo.

---

## Modular Rules

.agent/rules/
├── conventions.md
├── architecture.md
├── database.md
├── auth-roles.md
├── cms.md
├── media.md
├── gallery.md
├── contact.md
├── ppdb.md
├── theme.md
├── storage.md
├── security.md
├── testing.md (belum ada)
└── agent-behavior.md

---

## Source of Truth

Jika terdapat konflik atau ambiguitas, gunakan urutan berikut:

1. PRD Final
2. ERD Final
3. ADR/\*
4. GEMINI.md
5. .agent/rules/\*
6. Dokumentasi resmi framework/library
7. Preferensi agent

Agent tidak boleh mengubah keputusan yang sudah final tanpa persetujuan eksplisit.

---

## Anti-Hallucination Policy

Jangan mengarang.

Jika tidak yakin terhadap:

- API Laravel,
- Package pihak ketiga,
- Sintaks Composer,
- Konfigurasi server,
- Perintah Artisan,
- Best practice framework,

maka:

1. Gunakan MCP Context7 untuk mencari dokumentasi resmi.
2. Jika Context7 tidak tersedia atau tidak cukup, lakukan browsing internet.
3. Kutip atau jelaskan sumber yang digunakan.
4. Jangan membuat method, class, command, atau konfigurasi fiktif.

Jika informasi tidak dapat diverifikasi, nyatakan secara eksplisit.

---

## Laravel Philosophy

Prioritas implementasi:

1. Laravel built-in.
2. Package yang telah disetujui.
3. Dependency baru dengan justifikasi dan persetujuan.

Jangan membuat abstraction apabila Laravel telah menyediakan solusi standar.

Gunakan konvensi Laravel terlebih dahulu.

---

## Modular Boundary

Arsitektur menggunakan Modular Monolith.

Dependency flow:

Controller
→ Action
→ Service
→ Contract
→ Model

Dilarang:

- Model lintas modul.
- Foreign key lintas modul.
- Business logic kompleks di Controller.
- Business logic di Blade.
- Mengakses modul lain secara langsung tanpa Contract/Service.

---

## Ambiguity Handling

Jika terdapat informasi yang ambigu atau belum diputuskan:

- Berhenti.
- Jelaskan opsi yang tersedia.
- Sebutkan trade-off.
- Minta keputusan eksplisit.

Jangan membuat asumsi diam-diam.

---

## Architecture Change Policy

Agent tidak boleh:

- Mengubah struktur folder modular yang telah disepakati.
- Menambah layer arsitektur baru.
- Mengganti pola Controller → Action → Service → Contract → Model.
- Memindahkan domain ke modul baru.

kecuali terdapat instruksi eksplisit dari pengguna.

Jika perubahan mempengaruhi:

- struktur modul,
- dependency flow,
- database boundary,
- deployment architecture,

agent wajib meminta persetujuan terlebih dahulu.

---

## Scope Guard

Agent tidak boleh membuat:

- fitur roadmap masa depan,
- fitur yang "mungkin dibutuhkan nanti",
- extensibility yang belum diperlukan,
- abstraction untuk use case yang belum ada.

Implementasikan hanya kebutuhan yang telah tercantum pada PRD V1.

---

## Change Strategy

Gunakan Minimal Change Principle.

Saat mengubah kode:

- lakukan perubahan sekecil mungkin,
- hindari refactor yang tidak diminta,
- jangan mengubah style code yang tidak terkait task,
- jangan memindahkan file tanpa alasan yang jelas.

Tujuan utama adalah menyelesaikan task dengan risiko regresi minimum.

---

## Additional Rules

- Agent must not load all rules at once.
- Agent must load rules incrementally based on task context.

---

Interpretasi Source of Truth:

- PRD menjelaskan apa yang dibangun.
- ERD menjelaskan bagaimana data disimpan.
- ADR menjelaskan mengapa keputusan arsitektur diambil.
- GEMINI.md menjelaskan bagaimana agent bekerja.
- .agent/rules menjelaskan aturan implementasi rinci.
