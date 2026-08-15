# Database Schema

## Core Tables

### companies

Tenant root record.

### users

Global user identity.

### company_users

Membership table linking users to companies, with role assignment and status.

### roles

Company-scoped or system-scoped roles.

### permissions

Atomic permissions used by policies and role assignments.

### branches

Optional company branches.

### departments

Optional company departments.

### categories

Default and custom tracked-item categories.

### tracked_items

The central business entity.

Important columns:

- company_id
- branch_id
- department_id
- category_id
- owner_user_id
- responsible_user_id
- reference_number
- issue_date
- expiry_date
- reminder_rule_id
- status
- priority
- notes
- archived_at

### tags

Reusable labels.

### tracked_item_tags

Many-to-many mapping between items and tags.

### documents

High-level document container for a tracked item.

### document_versions

Append-only file/version history.

Important columns:

- document_id
- version_number
- file_path
- original_filename
- stored_filename
- mime_type
- file_size
- sha256_hash
- uploaded_by
- uploaded_at
- comment

### renewals

Renewal workflow records.

### renewal_steps

Workflow state history and approvals.

### reminders

Reminder schedule definitions and generated reminder instances.

### notifications

In-app notifications.

### notification_logs

Delivery logs for email and future channels.

### audit_logs

Append-only activity log.

### subscriptions

Company subscription state.

### plans

Configurable plan definitions.

### usage_records

Plan usage snapshots and enforcement metadata.

## Indexing Rules

Add indexes on:

- company_id
- expiry_date
- status
- responsible_user_id
- category_id
- branch_id
- department_id
- created_at

## Data Rules

- Never overwrite old document versions.
- Never delete renewal history by default.
- Prefer soft deletion for user-visible records where retention matters.
- Use foreign keys for referential integrity.
