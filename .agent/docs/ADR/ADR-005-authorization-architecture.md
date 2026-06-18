# ADR-005 — Authorization Architecture

Status: Accepted

Date: 2026-06-18

## Context

Aplikasi membutuhkan role dan permission yang fleksibel.

## Decision

Menggunakan Spatie Laravel Permission.

Role awal:

- Super Admin
- Admin Sekolah
- Editor

Authorization menggunakan Policy.

## Consequences

Role tidak di-hardcode di controller.

Permission dapat berkembang tanpa perubahan arsitektur besar.
