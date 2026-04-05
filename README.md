# Portfolio Core Platform (Laravel 12 + Filament)

A production-grade portfolio platform built as a **full PHP product**, not a basic personal profile page.

## What's Included

- Laravel 12 monolith architecture
- Premium public website pages:
  - Home
  - Services
  - Case Studies (list + detail)
  - Blog (list + detail)
  - About / Process
  - Contact
- Filament admin panel (`/admin`) for:
  - Services
  - Projects / Case Studies
  - Blog Posts
  - Testimonials
  - FAQs
  - Lead Inquiries (pipeline stages)
  - SEO Pages
  - Site Settings
- Lead CRM flow:
  - Contact form submission
  - Lead storage
  - Lead activity timeline entries
  - Notification email dispatch
- SEO foundation:
  - Meta controls per page
  - OpenGraph fields
  - Robots support
  - Sitemap generation command

## Stack

- PHP 8.4+
- Laravel 12
- Filament 3
- MySQL / SQLite
- Blade + Tailwind CSS + Vite

## Environment Notes

This project is optimized for shared hosting compatibility (including Hostinger):

- `QUEUE_CONNECTION=database`
- `SESSION_DRIVER=database`
- `CACHE_STORE=file`

Set these in `.env` for production.

Filament requires the PHP extension `ext-intl` in production/local runtime environments.

## Quick Start

1. Install dependencies:

```bash
composer install
npm install
```

2. Configure environment:

```bash
cp .env.example .env
php artisan key:generate
```

3. Run migrations and seeders:

```bash
php artisan migrate --seed
```

4. Start local dev:

```bash
composer run dev
```

## Admin Login (Seeded)

Configured using `.env` variables:

- `ADMIN_EMAIL`
- `ADMIN_PASSWORD`

Default values are in `.env.example`.

## Personalization Checklist

Fastest way: open `/admin/brand-profile` and fill your personal details in one form.

Or update manually via `Site Settings` keys:

- `site_name`
- `site_tagline`
- `brand_person_name`
- `brand_role`
- `brand_short_bio`
- `brand_long_bio`
- `profile_photo_url`
- `contact_email`
- `social_linkedin_url`
- `social_github_url`
- `social_x_url`

## Sitemap + Robots

Generate sitemap manually:

```bash
php artisan app:generate-sitemap
```

Also scheduled in `routes/console.php`:

- Daily at `02:00`

On Hostinger, add a cron job:

```bash
* * * * * php /home/USERNAME/path-to-project/artisan schedule:run >> /dev/null 2>&1
```

## Testing

```bash
php artisan test
```

Feature tests cover:

- Public route availability
- Contact lead submission behavior
- Honeypot anti-spam guard
- Draft vs published visibility
