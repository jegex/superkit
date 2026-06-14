# Changelog

All notable changes to Superkit will be documented in this file.

## v1.0.5 - 2026-06-14

### Changed

- **UserResource** — Added `navigationGroup: 'System'` so it appears under the System group in sidebar

## v1.0.4 - 2026-06-14

### Added

- **Maintenance Mode** — `CheckForMaintenance` middleware + 503 view. Toggle from Settings → General → Maintenance Mode real-time applies to frontend, preserves admin panel and Livewire access.

### Fixed

- **CheckForMaintenance middleware** — Exclude `livewire*` routes so admin panel Livewire interactions work during maintenance

## v1.0.3 - 2026-06-14

### Fixed

- **AdminPanelProvider** — Protect HealthCheckResults, Backups, and LogTable pages with Shield authorization via `authorize()` callbacks; users without the corresponding `View:*` permission can no longer access these pages

## v1.0.2 - 2026-06-14

### Fixed

- **SuperkitSetupCommand** — Remove `--generate` flag from `shield:seeder` call to prevent `NonInteractiveValidationException` caused by internal `shield:generate` call missing `--panel` option

## v1.0.1 - 2026-06-14

### Fixed

- **SuperkitSetupCommand** — Shield commands (install, generate, seeder, super-admin) now run every time setup is executed, fixing empty roles/permissions when user already exists

## v1.0.0 - 2026-06-14

Initial release of Superkit — a Laravel 13 + Filament 5 starter kit.

### Added

- **Content Management** — Posts, Pages, Products with publishing workflow
- **Scheduled Publishing** — Write now, publish later via artisan command + Laravel scheduler
- **Multi-Language** — Spatie Translatable on Content & Taxonomy, locale routing, scope control
- **Role & Permission** — Filament Shield RBAC with granular access control
- **Media Library** — Spatie Media Library with WebP image conversions
- **Menu Builder** — Drag-and-drop menu editor with translatable names
- **Activity Log** — Audit trail with timeline views
- **SEO Management** — Meta tags, Open Graph, Twitter Cards, Schema.org, sitemap
- **Health Check** — Application health monitoring dashboard
- **Backup** — Database and file backup management
- **Dynamic Settings** — 10 configurable setting groups (General, Branding, SEO, Mail, etc.)
- **Interactive Setup** — `php artisan superkit:setup` wizard
- **Global Helpers** — `setting()`, `siteName()`, `socialLinks()`, `seoMeta()`, etc.
- **API** — Sanctum auth with `/api/user` endpoint
- **Frontend** — Blade + Livewire v4 + Tailwind CSS v4 (Home, Blog, About, Contact)
- **Testing** — Pest 4 with SQLite in-memory, 18 feature/unit tests
