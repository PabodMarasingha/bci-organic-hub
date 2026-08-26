# BCI Organic Hub and Digital Fulfillment System

BSIT 22063 – Group Software Project, BCI Campus (2026)

A full-stack web platform that lets the BCI Campus community order organic salads, fresh juices, and portioned meals — with live customization, digital payments, and end-to-end delivery tracking from kitchen to doorstep.

## Problem It Solves

Campus food options are mostly processed, quick-fix choices with no way to customize for dietary needs, and no digital system for remote ordering or delivery. The Organic Hub replaces queueing and guesswork with a build-your-own ordering flow, real-time kitchen coordination, and zone-based delivery dispatch.

## Core Features

- **Customer**: browse the menu by category (salad, juice, bowl, wrap, smoothie), build a custom bowl from real ingredients, cart & checkout, order tracking, order reviews
- **Kitchen Staff**: live order queue, mark orders preparing/ready, real-time ingredient stock toggle (in stock / out of stock)
- **Delivery**: zone-based dispatch, driver assignment, pickup → delivered status flow, cash-on-delivery collection confirmation
- **Admin**: revenue & profit overview, ingredient management, staff management, live order and driver assignment panel

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2)
- **Frontend**: Blade templates, Tailwind CSS
- **Database**: MySQL (via XAMPP)
- **Auth & Roles**: Laravel Breeze/Fortify + Spatie `laravel-permission`

## Getting Started

### Prerequisites
- XAMPP (PHP 8.2+, MySQL)
- Composer
- Node.js & npm

### Setup

```bash
git clone https://github.com/PabodMarasingha/bci-organic-hub.git
cd bci-organic-hub

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Configure your database credentials in `.env`, then:

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link

npm run build
php artisan serve
```

Visit `http://127.0.0.1:8000`.

### Test Accounts

Seeded automatically via `php artisan db:seed`:

| Role | Email | Password |
|---|---|---|
| Admin | admin@bci.test | password |
| Kitchen Staff | kitchen@bci.test | password |
| Delivery Driver | driver1@example.com | password |
| Delivery Staff | delivery@bci.test | password |
| Customer | customer1@example.com | password |

New sign-ups through the registration page default to the **customer** role.

## Project Structure Notes

- Menu items are managed through the `ProductItem` model (`product_items` table) — this is what the customer-facing Menu page queries.
- Role and permission logic uses Spatie's `laravel-permission` package (`roles`, `permissions`, `model_has_roles` tables).
- Delivery zone/driver/order test data is seeded via `DeliveryTestingSeeder`.

## Team

| Name | Role | Function |
|---|---|---|
| Chethisha Pabod | Team Leader | Backend development, system integration, bug fixes & debugging |
| Thilina Sandaruwan | Member | Frontend design & UI |
| Sithum Kawshika | Member | Documentation & QA (testing) |
| Imanya Rajapaksha | Member | Database design |

<!-- Add Student IDs for each member here -->

## Course

BSIT 22063 – Group Software Project, BCI Campus, 2026