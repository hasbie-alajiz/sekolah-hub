# ADR-011 — Database Boundary Policy

Status: Accepted

Date: 2026-06-18

## Context

Modular Monolith memerlukan batas modul yang jelas.

## Decision

Foreign Key intra-modul diperbolehkan.

Foreign Key lintas modul dilarang.

## Consequences

Integritas data tetap terjaga dalam modul.

Coupling antar modul tetap rendah.
