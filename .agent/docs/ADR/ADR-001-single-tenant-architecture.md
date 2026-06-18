# ADR-001 — Single Tenant Architecture

Status: Accepted

Date: 2026-06-18

## Context

Target utama Sekolah Hub adalah sekolah dengan anggaran terbatas yang umumnya menggunakan shared hosting cPanel.

Multi-tenant akan menambah kompleksitas pada:

- database isolation
- provisioning
- deployment
- backup
- maintenance

## Decision

Sekolah Hub menggunakan arsitektur Single Tenant.

Satu instalasi aplikasi Laravel hanya melayani satu sekolah.

Setiap sekolah memiliki:

- database sendiri
- storage sendiri
- deployment sendiri

## Consequences

### Positif

- Arsitektur lebih sederhana
- Shared hosting friendly
- Risiko kebocoran data antar sekolah tidak ada
- Backup dan restore lebih mudah

### Negatif

- Setiap sekolah memerlukan deployment terpisah
- Update aplikasi dilakukan per instalasi
