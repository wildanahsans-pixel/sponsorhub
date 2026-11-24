# SponsorHub — Laravel 13 implementation (UC-01–UC-09)

This is an application overlay for a fresh Laravel 13 project. It includes the database schema, role-based login, all nine use-case routes, controllers, a health-score service, file evidence uploads, dashboard, and CSV report export.

## Requirements
- PHP 8.4+
- Composer
- MySQL 8+
- Laravel 13

## Install
```bash
composer create-project laravel/laravel sponsorhub
cd sponsorhub
```
Extract this ZIP into the project root and allow overwriting files. Then configure `.env`:
```env
APP_NAME=SponsorHub
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sponsorhub_db
DB_USERNAME=root
DB_PASSWORD=
```

Run:
```bash
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```
Open `http://127.0.0.1:8000`.

## Demo accounts
All demo passwords: `password`
- `panitia@sponsorhub.test` — Panitia
- `perusahaan@sponsorhub.test` — Perusahaan
- `koordinator@sponsorhub.test` — Koordinator
- `bendahara@sponsorhub.test` — Bendahara

## UC coverage
| UC | Implemented feature |
|---|---|
| UC-01 | Sponsor health score + indicators + follow-up recommendation |
| UC-02 | Sponsor CRUD + follow-up activity log |
| UC-03 | Benefit evidence upload (file or public URL) |
| UC-04 | Company package/benefit customization + draft/submission |
| UC-05 | Company approve/reject evidence + rejection note |
| UC-06 | Coordinator dashboard + category/date filters |
| UC-07 | CSV report export (Excel-compatible) |
| UC-08 | Record final agreement amount and benefit list |
| UC-09 | Verify payment and store verification history |

## Important implementation notes
- Health Score is a transparent heuristic, not a predictive model: response 30%, payment 35%, benefit completion 35%.
- UC-07 exports CSV, which opens in Excel. PDF export can be added with DomPDF once the report layout is finalized.
- This is a functional academic-project baseline, not a production-hardened financial system. Before deployment, configure HTTPS, mail, backups, stricter file scanning, audit logging, and real payment/bank reconciliation.
- Company demo account represents one sponsor organization. In a real multi-tenant deployment, link the company user to a specific `sponsor_id` and enforce tenant scoping in policies.
