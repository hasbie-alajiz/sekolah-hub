# ADR-003 — Modular Monolith Architecture

Status: Accepted

Date: 2026-06-18

## Context

Aplikasi memiliki beberapa domain besar:

- CMS
- Media
- Gallery
- Contact
- Theme
- PPDB

Namun microservice terlalu kompleks untuk kebutuhan V1.

## Decision

Menggunakan Modular Monolith.

Struktur utama:

app/Modules/

Tanpa package modular tambahan.

## Consequences

Dependency flow:

Controller
→ Action
→ Service
→ Contract
→ Model

Modul baru harus melalui keputusan arsitektur baru.
