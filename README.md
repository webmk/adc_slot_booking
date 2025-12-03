# ADC Slot Booking

A Laravel-based appointment and slot booking system for ADC centres. This repository implements the backend models, services, and notifications used to manage ADC centres, available dates, capacity levels and bookings.

**Quick summary:** the app provides centre and date management, capacity levels per date, booking creation and updates (with notifications), and mapping of employees/CPF to locations.

**Built with:** `PHP`, `Laravel`, `MySQL` (or other supported DB), `Vite` for frontend assets.

**Project structure highlights:** key models live in `app/Models` (for example `Booking`, `AdcCentre`, `AdcDate`, `CapacityLevel`, `FrozenLevel`, `User`), services are in `app/Services` (for example `CapacityCountService`, `UserLevelService`), and notifications are in `app/Notifications`.

## Features

- **Centre & Date Management:** Manage ADC centres and their available dates (`AdcCentre`, `AdcDate`).
- **Capacity Levels:** Define capacity tiers and per-date capacity (`CapacityLevel`, `FrozenLevel`).
- **Bookings:** Create and update bookings with validation against capacity limits (`Booking` model).
- **Notifications:** Email/notification support for booking creation and updates (`BookingCreatedNotification`, `BookingUpdatedNotification`).
- **Employee / CPF Mappings:** Map employees and CPF records to locations (`EmployeeLocationMapping`, `CpfLocationMapping`).
- **Seeders & Factories:** Pre-populated sample data via seeders (`database/seeders`).
- **Services:** Encapsulated business logic for capacity counting and user level handling (`app/Services`).

## Quick Setup (Local)

Prerequisites: `PHP` (compatible version), `Composer`, `Node.js` + `npm`, and a database (MySQL/MariaDB/Postgres).

1. Clone the repo:

```bash
git clone <repo-url> adc_slot_booking
cd adc_slot_booking
```

2. Install PHP dependencies:

```bash
composer install
```

3. Copy the environment file and set DB credentials in `/.env`:

```bash
copy .env.example .env
php artisan key:generate
```

4. Run migrations and seed sample data:

```bash
php artisan migrate
php artisan db:seed
```

5. Install front-end tooling and build assets:

```bash
npm install
npm run build  # or `npm run dev` for development
```

6. Serve the app locally:

```bash
php artisan serve
```