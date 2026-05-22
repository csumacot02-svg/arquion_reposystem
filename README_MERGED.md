# Merged Inventory System

This Laravel project merges:

1. `warehouse-inventory` — item CRUD, stock control, request approval/rejection.
2. `inventory-form` — public inventory request form.

## Main URLs

- `/` — landing page
- `/warehouse/items` — warehouse inventory dashboard
- `/warehouse/requests` — submitted request approvals
- `/request` — inventory request form

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

Then open:

```text
http://127.0.0.1:8000
```

## Notes

- The request form now saves directly into the warehouse request tables.
- Approving a request deducts stock from inventory.
- The old API routes are still included for compatibility:
  - `GET /api/available-items`
  - `POST /api/inventory-requests`
