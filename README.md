# Karihaalan Web - Hotel & Petrol Station Management System

A responsive, multi-role dashboard-based management system built with **Laravel 11** and **Bootstrap 5**. Karihaalan Web allows Admins, Hotel Managers, and Petrol Station Managers to manage their operations efficiently from a single platform.

## 🚀 Features

- ✅ Built with Laravel 11 and Bootstrap 5
- ✅ Mobile-responsive and modern UI
- ✅ Multi-user authentication (Admin, Hotel Manager, Petrol Station Manager)
- ✅ Role-based access control using middleware
- ✅ Admin dashboard for full control over the system
- ✅ Hotel Manager module:
  - Manage hotels, rooms, and bookings
  - Staff and revenue reports
- ✅ Petrol Station Manager module:
  - Manage petrol stock and fuel sales
  - Track staff shifts and logs
- ✅ Database seeding and migration ready
- ✅ Clean RESTful structure and modular code

## 🔐 User Roles

| Role               | Access Areas                             |
|--------------------|-------------------------------------------|
| Admin              | Full access to all modules & user roles   |
| Hotel Manager      | Hotel-related management only             |
| Petrol Manager     | Petrol station-related management only    |

## 🛠 Tech Stack

- **Backend**: Laravel 11 (PHP 8.3)
- **Frontend**: Blade, Bootstrap 5
- **Database**: MySQL
- **Auth**: Laravel Breeze
- **Other Tools**: Laravel Artisan, Laravel Seeder, Eloquent ORM

## 🧩 Installation Guide

```bash
git clone https://github.com/your-username/karihaalan_web.git
cd karihaalan_web
composer install
npm install && npm run dev
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
