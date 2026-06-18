# ADR-010 — File Storage Architecture

Status: Accepted

Date: 2026-06-18

## Context

Media publik dan dokumen PPDB memiliki kebutuhan keamanan berbeda.

## Decision

Menggunakan dua kategori storage:

Public:

storage/app/public/

Private:

storage/app/private/

## Consequences

Dokumen PPDB tidak dapat diakses langsung melalui URL publik.

Akses harus melalui authorization layer.
