# ADR-007 — Dynamic PPDB Form Architecture

Status: Accepted

Date: 2026-06-18

## Context

Setiap sekolah memiliki kebutuhan form PPDB yang berbeda.

## Decision

Menggunakan Hybrid Dynamic Form (EAV terbatas).

Field definition:

- admission_form_fields

Field value:

- registration_values

## Consequences

Admin dapat mengubah struktur form tanpa migration database.

Kompleksitas query sedikit meningkat dibanding tabel statis.
