# Invoice API Documentation

This document provides information about the Invoice API endpoints, their parameters, and response formats.

## Base URL

All API endpoints are prefixed with `/api`.

## Endpoints

### 1. List Invoices

Retrieves a paginated list of invoices with optional filtering.

**URL:** `/api/invoices`

**Method:** `GET`

**Query Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| payment_status | string | No | Filter invoices by payment status. Options: `all` (default), `paid`, `pending` |
| currency | string | No | Filter invoices by currency. Options: `USD`, `UGX` |
| page | integer | No | Page number for pagination (default: 1) |

**Response:**

```json
{
  "data": [
    {
      "id": 1,
      "hashed_id": "encoded_id_string",
      "date_invoiced": "2023-01-01",
      "transaction_currency": "USD",
      "shippment": {
        "id": 1,
        "number": "SHIP-001"
      },
      "user": {
        "id": 1,
        "name": "John Doe"
      },
      "amount": 1000,
      "total_amount": 1180,
      "paid_amount": 1180,
      "balance": 0,
      "payment_status": "paid",
      "created_at": "2023-01-01T12:00:00.000000Z",
      "updated_at": "2023-01-01T12:00:00.000000Z"
    },
    // More invoices...
  ],
  "pagination": {
    "total": 50,
    "per_page": 15,
    "current_page": 1,
    "last_page": 4,
    "from": 1,
    "to": 15
  }
}
```

### 2. Get Invoice Details

Retrieves detailed information about a specific invoice.

**URL:** `/api/invoices/{id}`

**Method:** `GET`

**URL Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | string/integer | Yes | The invoice ID or hashed ID |

**Response:**

```json
{
  "data": {
    "id": 1,
    "hashed_id": "encoded_id_string",
    "date_invoiced": "2023-01-01",
    "transaction_currency": "USD",
    "shippment": {
      "id": 1,
      "number": "SHIP-001"
    },
    "user": {
      "id": 1,
      "name": "John Doe"
    },
    "details": [
      {
        "id": 1,
        "particular": "Service Fee",
        "quantity": 2,
        "unit_price": 500,
        "amount": 1000
      }
    ],
    "payments": [
      {
        "id": 1,
        "payment_date": "2023-01-05",
        "amount_paid": 1180,
        "mode_of_payment": "Bank",
        "narrative": "Payment for invoice #1"
      }
    ],
    "taxes": [
      {
        "id": 1,
        "tax_name": "VAT",
        "rate": 18,
        "amount": 180
      }
    ],
    "amount": 1000,
    "total_amount": 1180,
    "paid_amount": 1180,
    "balance": 0,
    "payment_status": "paid",
    "created_at": "2023-01-01T12:00:00.000000Z",
    "updated_at": "2023-01-01T12:00:00.000000Z"
  }
}
```

### 3. Get Invoice Statistics

Retrieves statistics about invoices.

**URL:** `/api/invoices/statistics`

**Method:** `GET`

**Response:**

```json
{
  "data": {
    "total_invoices": 50,
    "paid_invoices": 30,
    "pending_invoices": 20,
    "amounts": {
      "usd": {
        "total": 50000,
        "paid": 30000,
        "pending": 20000
      },
      "ugx": {
        "total": 180000000,
        "paid": 100000000,
        "pending": 80000000
      }
    }
  }
}
```

## Error Responses

In case of errors, the API will return appropriate HTTP status codes along with error messages:

```json
{
  "error": "Error message here"
}
```

Common error status codes:
- 404: Resource not found
- 500: Server error

## Authentication

The API uses Laravel Sanctum for authentication. You need to include the authentication token in the request headers:

```
Authorization: Bearer {your_token}
```

To obtain a token, use your application's authentication system.
