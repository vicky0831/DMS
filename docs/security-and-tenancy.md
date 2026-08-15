# Security and Tenancy

## Rules

1. Authenticate the user.
2. Validate the user’s company membership.
3. Authorize the action with a server-side permission check.
4. Confirm the target resource belongs to the same company.
5. Return a safe error if access is not allowed.

## File Handling

- Store uploads in private storage.
- Never expose raw storage paths to the browser.
- Use signed or temporary download access after authorization.
- Validate file type by MIME and content sniffing.
- Reject dangerous file types and oversized uploads.

## Threats to Prevent

- SQL injection: use ORM or parameterized queries.
- XSS: escape output and sanitize user-generated content where needed.
- CSRF: use session protection and CSRF tokens.
- IDOR: re-check tenant ownership for every resource access.
- Mass assignment: whitelist fillable fields.
- Path traversal: never trust file names or relative paths.
- Brute force: rate-limit login and sensitive endpoints.

## Auditability

Record:

- login failures
- uploads
- downloads
- reminder sends
- permission-sensitive changes
- renewal state changes

Audit logs are append-only and not editable by normal users.
