# API Contracts

## Standards

- JSON responses
- server-side validation on every write endpoint
- paginated list endpoints
- consistent error envelope
- no internal tenant leakage in error messages

## Core Endpoints

### Auth

- `POST /api/auth/register`
- `POST /api/auth/login`
- `POST /api/auth/logout`
- `POST /api/auth/forgot-password`
- `POST /api/auth/reset-password`

### Company and Users

- `GET /api/company`
- `POST /api/company`
- `GET /api/users`
- `POST /api/users`
- `PUT /api/users/{id}`

### Tracked Items

- `GET /api/items`
- `POST /api/items`
- `GET /api/items/{id}`
- `PUT /api/items/{id}`
- `DELETE /api/items/{id}`

- `POST /api/items/{id}/renewal/start`
- `POST /api/items/{id}/documents`
- `GET /api/items/{id}/history`
- `GET /api/items/{id}/versions`

### Dashboard and Search

- `GET /api/dashboard`
- `GET /api/search`
- `GET /api/reports/expiry`

### Notifications

- `GET /api/notifications`
- `POST /api/notifications/{id}/read`

## Response Shape

Standard success response:

```json
{
  "data": {},
  "meta": {}
}
```

Standard validation error:

```json
{
  "message": "Unable to save this item.",
  "errors": {
    "expiry_date": ["The expiry date must be a valid date."]
  }
}
```

## Pagination

List endpoints should return:

- current page
- per page
- total
- last page
- data array
