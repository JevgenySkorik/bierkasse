# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Bierkasse is a bookkeeping tool for a student corporation that tracks purchases, payments, and debts. Users add journal entries recording who purchased what product, for how much, and whether they paid or have outstanding debt.

## Development Commands

```bash
# Start development server (runs PHP server, queue, logs, and Vite concurrently)
composer dev

# Run tests (Pest PHP)
./vendor/bin/pest

# Run single test file
./vendor/bin/pest tests/Feature/ExampleTest.php

# Run tests with filter
./vendor/bin/pest --filter="test name"

# Build frontend assets
npm run build

# Run database migrations
php artisan migrate

# Code formatting
./vendor/bin/pint
```

## Architecture

### Tech Stack
- Laravel 11 with PHP 8.2+
- SQLite database
- Pest PHP for testing
- Tailwind CSS + Vite for frontend
- Blade templates

### Data Model
- **journal** - Purchase entries with: name (buyer), product_id, amount, date, method (Cash/Debt/Deposit), total, notes
- **product** - Available products with: name, price, quantity
- **name** - Known customer names with balance (prepaid deposits)
- **User** - Admin users for authenticated routes

Relationships: journal belongsTo product; product hasMany journal

### Controllers
- **JournalController** - Main entry point (`/`), mobile view (`/mobile`), CRUD for journal/product entries, balance and debt updates
- **AdminController** - Auth pages, admin views (journal, products, debts, balances), CSV exports
- **NameController** - Name autocomplete endpoint
- **LanguageController** - Locale switching (English/Russian)

### Payment Methods
- **Cash** - Paid immediately
- **Debt** - Unpaid, tracked on debts page
- **Deposit** - Paid from customer's prepaid balance

### Key Views
- `index.blade.php` - Main journal entry form with paginated history
- `mobile.blade.php` - Mobile-optimized entry form with product preselection via URL params
- `debts.blade.php` - Shows unpaid entries grouped by debtor
- `balances.blade.php` - Manage customer prepaid balances

### Routes
All routes use `LanguageMiddleware`. Admin routes (`/journal`, `/products`, `/debts`, `/balances`, `/updateBalances`, `/addName`, `/updateDebts`) require authentication.
