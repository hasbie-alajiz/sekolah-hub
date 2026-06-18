---
trigger: always_on
---

# Media Module Rules

Status: Final

---

## Purpose

Media Module bertanggung jawab atas:

- Upload file
- Storage management
- Thumbnail generation
- Reusability media lintas modul

---

## Storage Rules

Gunakan Laravel filesystem:

- public disk → media publik
- private disk → dokumen PPDB

Dilarang:

- Direct filesystem access
- Hardcoded path

---

## Media Entity Rules

Setiap file disimpan sebagai:

- media
- media_variants (thumbnail, resize)

---

## Reusability Rule

Satu media dapat digunakan oleh:

- CMS
- Gallery
- PPDB
- Theme

Tanpa duplikasi file.

---

## Upload Rules

- Validasi mime type wajib
- Size limit wajib
- Sanitasi filename
- Generate unique filename

---

## Thumbnail Rules

- Thumbnail dibuat saat upload
- Tidak ada lazy generation V1
- Variants disimpan di table media_variants

---

## Deletion Rules

Soft delete default.

Hard delete hanya jika:

- File orphan
- Manual cleanup admin

---

## Access Rule

Tidak boleh akses file langsung dari modul lain.

Wajib melalui Media Service.

---

## Pengecualian

Pengecualian: Dokumen PPDB (private) dikecualikan dari Media Module. Upload dokumen PPDB dilakukan langsung oleh PPDB Module ke storage/app/private/. Path disimpan di tabel registration_documents.
