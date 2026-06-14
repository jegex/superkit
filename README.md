<div align="center">
  <h1>Superkit</h1>
  [![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=flat-square&logo=php)](https://php.net)
  [![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
  [![Filament](https://img.shields.io/badge/Filament-5-FDAC4F?style=flat-square&logo=filament)](https://filamentphp.com)
  [![Livewire](https://img.shields.io/badge/Livewire-4-FB70A9?style=flat-square&logo=livewire)](https://livewire.laravel.com)
  [![Pest](https://img.shields.io/badge/Pest-4-CB4E24?style=flat-square&logo=pest)](https://pestphp.com)
  [![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=flat-square&logo=tailwindcss)](https://tailwindcss.com)
  [![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](https://opensource.org/licenses/MIT)
</div>

<p align="center">
  <strong>Laravel 13 + Filament 5 starter kit</strong> with built-in CMS, multi-language support, role management, media library, scheduled publishing, and more.
</p>

---

## ✨ Features

| Category | Features |
|---|---|
| 🛡️ **User & Access** | Role-based access via Filament Shield, granular permissions, user impersonation, multi-role support |
| 👤 **Profile & Auth** | Filament Breezy (2FA, Sanctum tokens, browser sessions), avatar with UI Avatars fallback, customizable profile |
| 📝 **Content CMS** | Posts, Pages, Products with publishing workflow, featured content, rich editor, media attachments |
| ⏰ **Scheduled Publishing** | Write now, publish later — automatic scheduling via artisan command + Laravel scheduler |
| 🌐 **Multi-Language** | Spatie Translatable on Content & Taxonomy, locale routing, 2000+ locales, scope control (frontend/all) |
| 🖼️ **Media Library** | Spatie Media Library with WebP conversions (thumbnail, medium, large, preview), responsive images |
| 📋 **Menu Builder** | Drag-and-drop menu editor with translatable names, multiple locations (header, footer) |
| 📊 **Activity Log** | Full audit trail with timeline views for all content and user actions |
| 🔍 **SEO Management** | Meta tags, Open Graph, Twitter Cards, Schema.org, sitemap, robots.txt, verification codes |
| ⚙️ **Dynamic Settings** | 10 configurable setting groups managed from the admin panel (branding, SEO, mail, scripts, etc.) |
| 🧪 **Health & Backup** | Application health monitoring dashboard + database/file backup management |
| 📧 **Mail Management** | SMTP configuration from admin panel, multiple providers (Mailgun, Postmark, SES) |
| 🧩 **Developer Tools** | Interactive setup wizard, global helper functions, locale-aware slug service, scheduled task processor |
| 🧪 **Testing Ready** | Pest 4 with SQLite in-memory, feature tests for frontend & taxonomy CRUD, factory support |

---

## 📋 Requirements

- PHP **8.3+**
- Composer **2.x**
- Node.js **20+**
- MySQL / MariaDB / PostgreSQL

---

## 🚀 Quick Start

```bash
composer create-project superkit/superkit

cd superkit

# Or if you cloned the repo manually:
# composer install && npm install

cp .env.example .env
php artisan key:generate
```

Run the interactive setup wizard:

```bash
php artisan superkit:setup
```

It will guide you through:
- Application name & URL
- Admin credentials (email & password)
- Default language
- Database migration
- Demo data seeding (optional)
- Shield permissions & super admin creation

Build assets and start:

```bash
npm run build
php artisan serve
```

Access the admin panel at **`/admin`** with the credentials you provided during setup.

### Development Mode

```bash
composer run dev
```

Runs `php artisan serve`, queue worker, log watcher, and Vite dev server concurrently.

---

## ⚙️ Configuration

### Environment

Key variables in `.env`:

```env
APP_NAME=Superkit
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=superkit
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### Admin Settings Panel

Navigate to **Settings** in the admin panel (grouped under a cluster):

| Group | Settings Key | Manages |
|---|---|---|
| General | `system_general` | Site name, tagline, description, maintenance mode |
| Branding | `system_branding` | Logo, dark logo, favicon |
| Company | `system_company` | Company name, email, phone, address |
| Localization | `system_localization` | Default language, multi-language toggle, supported locales, scope, locale detection |
| Legal | `system_legal` | Terms, privacy, cookie policy URLs |
| Error | `system_error` | Custom 404/500 error messages |
| SEO | `system_seo` | Meta, OG, Twitter Card, Schema.org, sitemap, robots.txt, verification codes |
| Social | `system_social` | Social profile URLs, share platforms |
| Scripts | `system_scripts` | Header/body/footer custom scripts, custom CSS/JS |
| Mail | `system_mail` | SMTP config, Mailgun/Postmark/SES, queue, rate limiting, test mode |

---

## 🏗️ Architecture & Business Logic

### Services

#### `ContentService`

Handles scheduled content publishing.

```php
use App\Services\ContentService;

$count = app(ContentService::class)->processScheduledContent();
// Publishes all content where scheduled_at <= now()
// Returns the number of published items
```

#### `MultiLanguageService`

Central service for multi-language configuration. Registered as a singleton in `AppServiceProvider`.

```php
use App\Services\MultiLanguageService;

$mls = app(MultiLanguageService::class);

$mls->isEnabled();              // bool — is multi-language active?
$mls->getSupportedLocales();    // ['en', 'id', ...]
$mls->getDefaultLocale();       // 'en'
$mls->getScope();               // MultiLanguageScope::Frontend or ::All
$mls->shouldLocalize($request); // bool — should the current request be localized?
$mls->configurePackage();       // Updates laravellocalization config dynamically
```

The service auto-configures `laravellocalization` at boot based on settings.

#### `SlugService`

Locale-aware unique slug generation with conflict detection.

```php
use App\Services\SlugService;
use App\Models\Content;

$slug = app(SlugService::class)->generate(
    source: 'Hello World',       // string to slugify
    modelClass: Content::class,  // model to check against
    type: 'post',                // optional type filter
    record: null,                // optional record to exclude
);
// Returns: 'hello-world' (or 'hello-world-1', 'hello-world-2', etc.)

// Check if a slug is already taken:
$taken = app(SlugService::class)->isTaken(
    slug: 'hello-world',
    modelClass: Content::class,
    type: 'post',
    record: null,
);
```

### Global Helpers

All defined in `app/helpers.php`:

#### Settings Accessors

```php
setting(GeneralSettings::class, 'name');           // Locale-aware value
settingRaw(GeneralSettings::class, 'name');        // Raw value (no locale resolution)
```

#### Site Info

```php
siteName();          // App name from GeneralSettings
siteTagline();       // Tagline from GeneralSettings
siteDescription();   // Description from GeneralSettings
siteLogo();          // Logo URL from BrandingSettings
siteLogoDark();      // Dark logo URL from BrandingSettings
siteFavicon();       // Favicon URL from BrandingSettings
```

#### Social & Company

```php
socialLinks();
// ['facebook' => 'https://...', 'twitter' => 'https://...', ...]

companyInfo();
// ['name' => '...', 'email' => '...', 'phone' => '...', 'address' => '...']

legalLinks();
// ['terms' => 'https://...', 'privacy' => 'https://...', 'cookie' => 'https://...']
```

#### SEO

```php
seoMeta();
// Returns array with: meta_description, meta_keywords, canonical_url,
// og_type, og_title, og_description, og_image, twitter_card_type,
// twitter_site, schema_type, schema_name, verification_codes, etc.
```

#### Utilities

```php
defaultLocale();    // 'en' — from LocalizationSettings
storageUrl($path);  // Full URL for a storage path
```

### Enums

#### `ContentType`

```php
use App\Enums\ContentType;

ContentType::Post;    // 'post'
ContentType::Page;    // 'page'
ContentType::Product; // 'product'
```

#### `ContentStatus`

```php
use App\Enums\ContentStatus;

ContentStatus::DRAFT;      // 'draft'      — gray, clock icon
ContentStatus::PENDING;    // 'pending'    — info, exclamation icon
ContentStatus::PUBLISHED;  // 'published'  — success, check-circle icon
ContentStatus::ARCHIVED;   // 'archived'   — gray, archive-box icon

// Each status has:
$status->getLabel();  // 'Draft', 'Pending Review', etc.
$status->getColor();  // 'gray', 'info', 'success'
$status->getIcon();   // 'heroicon-m-clock', etc.
```

#### `TaxonomyType`

```php
use App\Enums\TaxonomyType;

TaxonomyType::Category; // 'category'
TaxonomyType::Tag;      // 'tag'
```

#### `MultiLanguageScope`

```php
use App\Enums\MultiLanguageScope;

MultiLanguageScope::Frontend; // Multi-language only on frontend routes
MultiLanguageScope::All;      // Multi-language everywhere including admin
```

### Models

#### `Content` — Main content model

| Attribute | Type | Notes |
|---|---|---|
| `title` | `json` (translatable) | Localized title |
| `slug` | `json` (translatable) | Localized URL slug |
| `excerpt` | `json` (translatable) | Localized summary |
| `content` | `json` (translatable) | Localized rich content |
| `type` | `ContentType` enum | Post, Page, or Product |
| `status` | `ContentStatus` enum | Draft, Pending, Published, Archived |
| `is_featured` | `boolean` | Featured flag |
| `published_at` | `datetime` | When it was published |
| `scheduled_at` | `datetime` | Scheduled publication time |
| `metadata` | `json` | Flexible metadata storage |

**Relations:** `author` (BelongsTo User), `tags` (MorphToMany Taxonomy)

**Traits:** `HasTranslations`, `HasTags`, `InteractsWithMedia`, `SoftDeletes`, `HasFactory`, `InteractsWithTimeline`

**Media Conversions:** preview (300×300), thumbnail (150×150), medium (600×600), large (1200×800) — all WebP

**Media Collections:** `featured` (single file), `content`, `gallery`

#### Model Scopes

```php
Content::published();           // published_at IS NOT NULL AND <= now()
Content::scheduled();           // scheduled_at IS NOT NULL AND <= now()
Content::draft();               // published_at IS NULL
Content::byStatus($status);     // WHERE status = $status
Content::byType($type);         // WHERE type = $type
Content::featured();            // WHERE is_featured = true

// Usage:
$posts = Content::published()
    ->byType(ContentType::Post)
    ->with('tags', 'author')
    ->get();
```

#### Sub-models

```php
// Content subtypes — extend Content with no additional logic
App\Models\Blog\Post;
App\Models\Blog\Page;

// Taxonomy subtypes
App\Models\Blog\Category;  // Taxonomy with type = 'category'
App\Models\Blog\Tag;       // Taxonomy with type = 'tag'
```

#### `Taxonomy` — Extends Spatie Tag

| Attribute | Type | Notes |
|---|---|---|
| `name` | `json` (translatable) | Localized name |
| `slug` | `json` (translatable) | Localized slug |
| `description` | `json` (translatable) | Localized description |
| `type` | `TaxonomyType` enum | Category or Tag |

**Traits:** `NodeTrait` (nested set), `SoftDeletes`, `HasTranslations`

#### `User`

| Attribute | Notes |
|---|---|
| `username`, `firstname`, `lastname` | Personal details |
| `timezone` | User timezone (defaults to app timezone) |
| `email` | MustVerifyEmail |
| `avatar` | Via Spatie Media Library with UI Avatars fallback |

**Roles:** Uses Spatie Permission + Filament Shield. Super admin check via `$user->isSuperAdmin()` or `$user->hasRole('super_admin')`.

**Traits:** `HasRoles`, `HasApiTokens` (Sanctum), `InteractsWithMedia`, `HasTimeline`

#### Other Models

```php
App\Models\Menu;      // Translatable menu names, extends FilamentMenuBuilder model
App\Models\Locale;    // Supported locales (code, name, script, native, regional)
```

### ⏰ Content Scheduling

Superkit has a complete scheduling system — write content now, publish automatically later.

#### Publishing Workflow

```
                        ┌─────────────────────────────────────────────────┐
                        │           Schedule Publication                  │
                        │     (scheduled_at + status → PENDING)          │
                        └────────────────────┬────────────────────────────┘
                                             │
    ┌──────────┐      Submit for Review     ╱╲       Approve & Publish    ┌───────────┐
    │  DRAFT   │ ──────────────────────────╱  ╲─────────────────────────→ │ PUBLISHED │
    │          │                           ╲  ╱                          │           │
    │ published│                            ╲╱                           │ published │
    │_at = null│                            ╱╲                           │_at = now  │
    └──────────┘     ┌──────────┐          ╱  ╲                          └───────────┘
          ▲          │ PENDING  │ ◄────────╲  ╱                               │
          │          │          │           ╲╱                                │
          │          │ scheduled│           / \                               │
          │          │_at = set │          /   \                              │
          │          └──────────┘              │                              │
          │                                    │ content:process-scheduled    │
          │                                    ▼                              │
          │          ┌──────────────────────────────────────────────────────┐ │
          │          │              Auto-Publish via Scheduler              │ │
          │          │   (when scheduled_at is past, status → PUBLISHED)   │─┘
          │          └──────────────────────────────────────────────────────┘
          │
          └──────────────────── Unpublish (↩ DRAFT, clear scheduled_at) ────┘
```

#### Admin Panel Actions

| Action | Location | Effect |
|---|---|---|
| **Schedule Publication** | Edit form toolbar | Sets `status = PENDING`, fills `scheduled_at` |
| **Publish Now** | Edit form toolbar | Immediate publish (`published_at = now()`) |
| **Approve & Publish** | Table row action | Approves pending content, notifies author |
| **Unpublish** | Edit form toolbar | Returns to DRAFT, clears `scheduled_at` |
| **Duplicate** | Table row action | Copies content as new DRAFT |
| **Toggle Featured** | Edit form toolbar | Toggles `is_featured` |

#### Scheduling via Admin Form

In the **Status & Visibility** section of the content form:
1. Set status to **Pending Review** → the **Schedule For** field appears
2. Pick the desired publication date & time
3. Save — content will wait in PENDING status

#### Automated Processing

```bash
# Publish all content whose scheduled_at has passed
php artisan content:process-scheduled

# Output: "Published 3 scheduled content(s)."
```

To automate, configure the scheduler in `bootstrap/app.php`:

```php
->withSchedule(function (Illuminate\Console\Scheduling\Schedule $schedule) {
    $schedule->command('content:process-scheduled')->everyMinute();
})
```

Then add the Laravel cron entry on your server:

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

#### Table Filters

The content table includes dedicated filters for scheduling:
- **Pending Approval** — content awaiting review
- **Published Posts** — currently published content
- **Published This Month** — content published this month
- **Featured Posts** — featured content (admin/editor only)

#### Business Rules for Authors

Enforced by `ContentObserver`:

| Rule | Detail |
|---|---|
| Cannot publish directly | Setting status to PUBLISHED is forced back to DRAFT |
| Cannot feature content | `is_featured` changes are reverted |
| Cannot change author | `author_id` changes are reverted |
| Cannot change created_by | `created_by` changes are reverted |
| Auto-assign author | `author_id` set to current user if role is 'author' |
| Auto-track editors | `created_by` and `updated_by` filled from authenticated user |

### Observers

#### `ContentObserver`

```php
App\Observers\ContentObserver;
// Registered via #[ObservedBy(ContentObserver::class)] on Content model
```

**On `creating`:**
- Sets `created_by` and `updated_by` to the authenticated user
- If user has role `author`: auto-assigns `author_id`, forces `is_featured = false`, prevents `PUBLISHED` status (downgrades to DRAFT)

**On `updating`:**
- Updates `updated_by`
- If user has role `author`: reverts changes to `is_featured`, `status`, `author_id`, `created_by`

**On `created` / `updated`:**
- Placeholder hooks for notifications (TODO)

### Console Commands

#### `superkit:setup`

Interactive setup wizard for new installations.

```bash
php artisan superkit:setup
```

Prompts for: app name, URL, admin email/password, default language, migration, demo data. Runs Shield install, generates permissions, creates super admin.

#### `content:process-scheduled`

```bash
php artisan content:process-scheduled
```

Publishes all content where `scheduled_at` is in the past. Updates `status → PUBLISHED`, sets `published_at` and `last_published_at` to now, clears `scheduled_at`.

### Validation Rules

#### `UniqueSlug`

Locale-aware unique slug validation rule.

```php
use App\Rules\UniqueSlug;

new UniqueSlug(
    modelClass: Content::class,
    locale: 'en',
    type: 'post',
    ignoreId: $content->id,  // exclude current record on update
);
// Checks: WHERE slug->'en' = ? AND type = 'post' AND id != ?
```

### Policies

| Policy | Guard | Key Abilities |
|---|---|---|
| `ContentPolicy` | Content model | viewAny, view, create, update, delete, deleteAny, restore, forceDelete, replicate, reorder |
| `UserPolicy` | User model | view, create, update, delete |
| `RolePolicy` | Spatie Role | view, create, update, delete |
| `MenuPolicy` | Menu model | view, create, update, delete |
| `ExceptionPolicy` | Exception model | view, delete |
| `Blog\*Policy` | Blog sub-models | Inherits ContentPolicy |

---

## 🧩 Filament Plugins

| Plugin | Purpose |
|---|---|
| **Filament Shield** | Role-based access control with auto-generated permissions |
| **Spatie Media Library** | File uploads with image conversions |
| **Spatie Settings** | Dynamic settings storage (10 groups) |
| **Spatie Tags** | Tagging via Taxonomy model |
| **Filament Breezy** | Profile management, 2FA, Sanctum tokens, browser sessions |
| **Menu Builder** | Drag-and-drop menu editor with translatable names |
| **Exceptions** | In-panel exception viewer |
| **Log Viewer** | Application log viewer |
| **Activity Log** | Audit trail with timeline |
| **Spatie Backup** | Database & file backup management |
| **Spatie Health** | Application health monitoring dashboard |
| **Spatie Translatable** | Filament UI for translatable fields with locale switcher |
| **Filament Impersonate** | User impersonation for admins |

---

## 🌍 Multi-Language

Superkit supports full multi-language out of the box.

### How It Works

1. **Toggle**: Enable/disable via **Localization Settings** in the admin panel
2. **Scope**: Choose between `Frontend` only or `All` (including admin)
3. **Locales**: Select from 2000+ locales seeded in the database
4. **Routing**: Uses `mcamara/laravel-localization` for locale-prefixed URLs (`/en/blog`, `/id/blog`)
5. **Translatable Fields**: `title`, `slug`, `excerpt`, `content` on Content; `name`, `slug`, `description` on Taxonomy

### Locale Detection

- Auto-detect from browser (`Accept-Language` header) — configurable
- Hide default locale in URL — configurable
- Session & cookie persistence

### Helpers

```blade
{{-- Blade usage --}}
{{ setting(App\Settings\System\GeneralSettings::class, 'name') }}
```

The `setting()` helper automatically resolves the current locale for translatable settings.

---

## 🧪 Testing

```bash
php artisan test
```

Uses Pest 4 with SQLite in-memory database.

### Test Structure

```
tests/
├── Feature/
│   ├── ExampleTest.php              # Basic HTTP test
│   ├── FrontendTest.php             # Home, blog, about, contact routes
│   └── TaxonomyTest.php             # CRUD & validation for taxonomies
│   └── SuperkitSetupCommandTest.php # Setup command registration
├── Unit/
│   └── ExampleTest.php              # Sanity check
├── Pest.php                         # Pest configuration
└── TestCase.php                     # Base test case
```

### Factories

```php
Database\Factories\ContentFactory;    // Content model factory with states
Database\Factories\UserFactory;       // User model factory with states
```

---

## 🖥️ Frontend

Built with **Blade**, **Tailwind CSS v4**, and **Livewire v4**.

**Routes:**

| URI | Name | Description |
|---|---|---|
| `/` | `home` | Landing page |
| `/blog` | `blog.index` | Blog listing |
| `/blog/{slug}` | `blog.show` | Single post view (published posts only) |
| `/about` | `about` | About page |
| `/contact` | `contact` | Contact page |

All frontend routes support locale prefixing when multi-language is enabled.

---

## 📄 License

Superkit is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
