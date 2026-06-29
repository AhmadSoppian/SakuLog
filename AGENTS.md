# SakuLog — Agent Guide

## Stack

- **Laravel 11** (PHP 8.2+, Blade templates, Tailwind CSS 3, Vite)
- **MySQL** configured in `.env` (DB: `saku-log`); `database.sqlite` present but unused
- **No JS framework** — vanilla JS via `resources/js/bootstrap.js` (axios)
- **No auth scaffolding yet** — `task1.md` specifies building register/login frontend

## Early-stage status

Only 2 commits exist. The DB schema is:
- `users` (default Laravel columns)
- `categories` (id, name, type: income|expense, timestamps)
- `transactions` (id, user_id FK, category_id FK, type, amount, description, balance_before, balance_after, transaction_date, timestamps)

No custom models (except `User`), no controllers, only the default `welcome` Blade view.

## Commands

```bash
# Run everything concurrently (Vite + Artisan serve + queue + logs)
composer dev

# Individual
php artisan serve
npm run dev          # Vite with laravel-vite-plugin
npm run build        # production Vite build
php artisan migrate
php artisan make:model -m  # model + migration
php artisan make:controller
```

## Code style

- **Laravel Pint** (`vendor/bin/pint`) — run before committing
- `.editorconfig`: 4-space indent, LF line endings
- PSR-4: `App\` → `app/`, `Database\Factories\` → `database/factories/`, `Tests\` → `tests/`

## Tests

```bash
php artisan test              # full suite
php artisan test --filter=X   # focused
```

PHPUnit config uses `APP_ENV=testing`, array cache, sync queue. DB tests use MySQL (not sqlite). No factories or seeders for `categories`/`transactions` exist yet — only `UserFactory` + `DatabaseSeeder`.

## Key files

- `task1.md` — current frontend task spec (register/login UI, monochrome theme)
- `routes/web.php` — single `GET /` → welcome view
