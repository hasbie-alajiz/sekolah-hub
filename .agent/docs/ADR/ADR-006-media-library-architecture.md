# ADR-006 — Media Library Architecture

Status: Accepted

Date: 2026-06-18

## Context

Media digunakan oleh banyak modul.

Duplikasi file harus dihindari.

## Decision

Menggunakan Media Module terpusat.

Satu media dapat digunakan ulang oleh:

- CMS
- Gallery
- PPDB
- Theme

Thumbnail dibuat saat upload.

Variant disimpan pada tabel media_variants.

## Consequences

Tidak ada upload file langsung oleh modul lain.

Seluruh file harus melalui Media Module.
