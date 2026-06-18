# ADR-002 — Shared Hosting First

Status: Accepted

Date: 2026-06-18

## Context

Mayoritas target pengguna menggunakan shared hosting cPanel.

Arsitektur harus berjalan tanpa akses root server.

## Decision

Seluruh fitur wajib kompatibel dengan shared hosting.

Dilarang bergantung pada:

- Queue Worker
- Horizon
- Supervisor
- WebSocket
- Redis sebagai komponen wajib
- Binary eksternal

## Consequences

Seluruh proses harus selesai dalam request-response lifecycle normal.

Background processing tidak tersedia pada V1.
