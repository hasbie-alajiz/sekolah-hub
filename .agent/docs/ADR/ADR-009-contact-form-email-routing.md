# ADR-009 — Contact Form & Email Routing

Status: Accepted

Date: 2026-06-18

## Context

Sekolah sering kesulitan mengelola SMTP sendiri.

## Decision

Menggunakan:

- Cloudflare Turnstile
- Cloudflare Email Routing

Pesan tetap disimpan ke database.

## Consequences

Setup email lebih sederhana.

Spam protection tersedia tanpa dependency tambahan.
