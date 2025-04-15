# 💰 Expense Tracker API

A simple API for tracking expenses, managing categories, and handling financial data built with Laravel.

---

## 🚀 Features

-   User authentication

-   Manage income and expense transactions

-   Filter transactions by date and type

-   Monthly financial summaries (total income & expenses)

-   Category management

-   Pagination support

-   Token-based access control

---

## 📊 API Endpoints Overview

### 🔐 Authentication

| Method | Endpoint        | Description                    |
| ------ | --------------- | ------------------------------ |
| POST   | `/api/register` | Register a new user            |
| POST   | `/api/login`    | Authenticate and get token     |
| POST   | `/api/logout`   | Log out the authenticated user |

### 👤 Profile

| Method | Endpoint                | Description                    |
| ------ | ----------------------- | ------------------------------ |
| GET    | `/api/profile`          | Get authenticated user profile |
| PATCH  | `/api/profile`          | Update user profile info       |
| PATCH  | `/api/profile/password` | Change user password           |

### 💸 Transactions

| Method | Endpoint                 | Description                      |
| ------ | ------------------------ | -------------------------------- |
| GET    | `/api/transactions`      | List transactions (with filters) |
| POST   | `/api/transactions`      | Create a new transaction         |
| GET    | `/api/transactions/{id}` | Show a specific transaction      |
| PUT    | `/api/transactions/{id}` | Update a transaction             |
| DELETE | `/api/transactions/{id}` | Delete a transaction             |

### 🗂️ Categories

| Method | Endpoint               | Description              |
| ------ | ---------------------- | ------------------------ |
| GET    | `/api/categories`      | List all categories      |
| POST   | `/api/categories`      | Create a new category    |
| GET    | `/api/categories/{id}` | Show a specific category |
| PUT    | `/api/categories/{id}` | Update a category        |
| DELETE | `/api/categories/{id}` | Delete a category        |

---

> 🔒 **Note :** All endpoints (except register/login) require authentication via a Bearer token.

---

## 📝 Required Fields for Resource Creation and Update

### Transaction

**POST** `/api/transactions`

| Field         | Type    | Validation Rules                                   |
| ------------- | ------- | -------------------------------------------------- |
| `category_id` | integer | required, integer                                  |
| `amount`      | numeric | required, max: 9,999,999,990                       |
| `type`        | string  | required, must be either `income` or `expense`     |
| `description` | string  | optional, max: 2048 characters                     |
| `date`        | date    | optional, defaults to current date if not provided |

### Category

**POST** `/api/categories`

| Field  | Type   | Validation Rules                                           |
| ------ | ------ | ---------------------------------------------------------- |
| `name` | string | required, max: 255 characters                              |
| `slug` | string | required, must be unique for the user, max: 255 characters |

> 🔐 **Note :** `slug` must be unique **per user**. Validation ensures uniqueness based on the authenticated user's `id`.

---

## 🔎 Filtering Transactions

You can filter transactions on:

**GET** `/api/transactions`

### Available Query Parameters:

| Parameter | Type   | Description                                        |
| --------- | ------ | -------------------------------------------------- |
| `type`    | string | Filter by type: `income` or `expense`              |
| `start`   | string | Show transactions from this start date (inclusive) |
| `end`     | string | Show transactions up to this end date (inclusive)  |

**Example usage:**

```http
GET /api/transactions?type=income&start=2025-04-01&end=2025-04-30
```
