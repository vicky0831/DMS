# Malaysia SME Compliance & Renewal Tracker

Simple SaaS for Malaysian SMEs to track renewals, expiry dates, responsibility, reminders, audit history, and document versions.

## Product Direction

This repository will be built as a focused compliance and renewal platform, not a generic document-management system.

Primary question:

> What needs attention today?

## Proposed Stack

- Backend: Laravel + PHP
- Frontend: React + TypeScript + Vite
- Database: MySQL
- Queue/Cache: Redis
- File storage: private object/file storage
- Auth: session-based web auth with CSRF protection

## Current Scope

The first implementation slice will cover:

- authentication
- company registration and tenant isolation
- users, roles, and permissions
- tracked items
- categories
- expiry dates and status calculation
- dashboard summary
- reminders
- private document upload
- audit logs
- search and filters
- responsive UI

## Design References

- [Architecture](docs/architecture.md)
- [Database schema](docs/database-schema.md)
- [API contracts](docs/api-contracts.md)
- [Security and tenancy](docs/security-and-tenancy.md)
- [Testing strategy](docs/testing-strategy.md)
