# Copilot instructions for inventory-management

This repository is a Laravel application with the app source mounted under `src/` and local development driven by Docker Compose from the repo root.

## Key project layout
- `src/app/Http/Controllers/` contains controller logic.
- `src/app/Models/` contains Eloquent models (`Item`, `StockLog`, `User`).
- `src/routes/web.php` defines the main web routes and uses `auth` middleware.
- `src/database/migrations/` defines the schema for `items` and `stock_logs`.
- `src/app/Http/Requests/` holds request validation classes, but some controllers still use inline `$request->validate()`.
- `src/resources/views/` contains Blade templates.
- `src/package.json` uses Vite for front-end assets: `npm run dev` / `npm run build`.

## Local development workflow
- Start containers: `docker compose up -d --build`
- Enter PHP container: `docker compose exec php bash`
- Install PHP deps from inside container: `composer install`
- Create `.env`: `cp .env.example .env`
- Generate key: `php artisan key:generate`
- Run migrations: `php artisan migrate`
- Seed initial data: `php artisan db:seed`
- Serve assets from `src/` with Vite if needed.
- Access app at `http://localhost`; MailHog is available at `http://localhost:8025` when `MAIL_HOST=mailhog`.

## Important conventions
- Keep Laravel paths rooted in `src/`.
- Prefer `use` imports at the top of PHP files, but existing controllers sometimes use fully qualified class names directly.
- `Item` is fillable only for `name`, `sku`, and `unit`.
- `ItemUpdateRequest` currently expects route model binding for `items/{item}`.
- Routes include a stock-related section, but the repo does not currently contain `StockController` or `StocksController`; do not assume those controllers exist unless added.

## What to focus on
- Preserve the existing Laravel architecture and route conventions.
- Avoid introducing new root-level app paths outside `src/`.
- If modifying validation, update the request class under `src/app/Http/Requests/` and align controller usage.
- For database changes, update migrations in `src/database/migrations/` and Eloquent relationships in models.

## Notes for code generation
- Use route names from `src/routes/web.php` when generating links or redirects.
- Keep current authentication guard style: `Route::middleware(['auth'])` around the main resource routes.
- The current app is small and focused on item CRUD plus stock log tracking, so prefer minimal changes and explicit Laravel semantics.
