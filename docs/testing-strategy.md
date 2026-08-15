# Testing Strategy

## Unit Tests

Cover business rules for:

- expiry calculations
- reminder generation
- renewal state transitions
- permission checks
- subscription limits

## Feature Tests

Cover:

- registration and login
- tenant isolation
- tracked-item CRUD
- uploads
- renewal workflow
- notifications
- search and filters

## Security Tests

Cover attempted access to another company’s data, file downloads, and manipulated identifiers.

## Data/Background Job Tests

Cover idempotent reminder generation, queued notification delivery, and retry behavior.

## Frontend Tests

Cover dashboard visibility, empty states, form validation, and responsive behavior on mobile breakpoints.
