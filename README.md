
# 💊 MediTrack – Medicine Stock & Expiry Tracker

A full-stack Laravel-based inventory management system designed for pharmacies, medical stores, and hospital dispensaries. MediTrack helps manage medicines, batch-wise stock, expiry dates, suppliers, and stock transactions through a centralized dashboard.

The system provides automated stock and expiry status tracking, role-based access control, inventory reports, and a responsive user interface.

---

## 📌 Project Overview

MediTrack is developed to simplify medicine inventory management and reduce manual stock-tracking efforts.

It enables users to manage medicines batch-by-batch, monitor stock availability, identify near-expiry and expired medicines, record stock transactions, and generate inventory reports.

The application includes three user roles — Admin, Pharmacist, and Staff — with role-based permissions.

---

## ✨ Key Features

### 📦 Medicine & Inventory Management
- Add, view, update, and delete medicines.
- Manage medicine categories.
- Track medicines batch-by-batch.
- Store batch number, manufacturing date, expiry date, quantity, pricing, and supplier details.
- Search, filter, sort, and paginate inventory records.

### 📊 Stock Management
- Record stock IN, OUT, and ADJUSTMENT transactions.
- Maintain stock transaction history.
- Prevent invalid stock removal.
- Automatically calculate stock status:
  - In Stock
  - Low Stock
  - Out of Stock

### ⏳ Expiry Management
- Automatically calculate medicine expiry status.
- Identify valid, near-expiry, and expired batches.
- Configure near-expiry alert thresholds.
- View expiry alerts and out-of-stock medicines.

### 📈 Dashboard & Reports
- Dashboard with inventory statistics.
- Chart.js visualizations.
- Recent stock activity and alerts.
- Inventory, expiry, and stock reports.
- Report filtering, CSV export, and print support.

### 👥 Authentication & Role-Based Access
- User registration and login.
- Secure logout and protected routes.
- Admin, Pharmacist, and Staff roles.
- New self-registered users receive the Staff role by default.
- Admin user management.
- Activate or deactivate user accounts.
- Role-based navigation and authorization.
- Profile management and password updates.

### ⚙️ Additional Features
- Pharmacy settings management.
- Configurable minimum stock level.
- Configurable near-expiry threshold.
- Currency configuration.
- Responsive Tailwind CSS interface.
- Form validation, flash messages, and delete confirmations.

---

## 👤 User Roles & Permissions

| Feature | Admin | Pharmacist | Staff |
|---|---|---|---|
| Dashboard & stock history | Yes | Yes | Yes |
| View medicines & expiry alerts | Yes | Yes | Yes |
| Stock IN / OUT / Adjustment | Yes | Yes | Yes |
| Create / Edit / Delete medicines & batches | Yes | Yes | No |
| Manage categories | Yes | No | No |
| View & export reports | Yes | Yes | No |
| Manage settings | Yes | No | No |
| User management | Yes | No | No |
| Manage own profile | Yes | Yes | Yes |

Permissions are enforced through Laravel route middleware and role-based access control.

---

## 🛠️ Technology Stack

| Technology | Purpose |
|---|---|
| PHP 8.2+ | Backend programming |
| Laravel 11 | PHP web application framework |
| MySQL 8.x | Relational database |
| Eloquent ORM | Database operations |
| Blade | Server-side templating |
| Tailwind CSS | UI styling |
| Alpine.js | Frontend interactions |
| Vite | Asset bundling |
| Chart.js | Dashboard charts |
| Composer | PHP dependency management |
| Node.js & npm | Frontend dependency management |
| PHPUnit | Application testing |

---

## 📋 System Requirements

Make sure the following software is installed:

- PHP >= 8.2
- Composer 2.x
- MySQL 8.x or compatible MariaDB
- Node.js 18+ and npm
- Git
- Visual Studio Code (recommended)

Required PHP extensions include `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, and `bcmath`.

---

## 🚀 Installation & Setup Guide

Follow these steps to run MediTrack locally on Windows.

### 1. Clone the Repository

```bash
git clone https://github.com/Bhumika1604/Medicine-Stock-Expiry-Tracker.git
```

Navigate into the project folder:

```bash
cd Medicine-Stock-Expiry-Tracker
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Frontend Dependencies

```bash
npm install
```

Build the frontend assets:

```bash
npm run build
```

For development with automatic asset rebuilding, use:

```bash
npm run dev
```

Keep the Vite development process running in a separate terminal while developing.

### 4. Configure Environment Variables

Create your local environment file:

**Windows CMD:**
```cmd
copy .env.example .env
```

**PowerShell:**
```powershell
Copy-Item .env.example .env
```

Generate the Laravel application key:

```bash
php artisan key:generate
```

> Never upload your actual `.env` file or database credentials to GitHub.

### 5. Create the MySQL Database

Start your MySQL Server and open MySQL Workbench.

Create a database named:

```sql
CREATE DATABASE medicine_tracker
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

### 6. Configure MySQL Connection

Open the `.env` file and update the database configuration according to your local MySQL setup:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medicine_tracker
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

Replace `your_mysql_password` with your actual MySQL password.

If your MySQL root account has no password, leave `DB_PASSWORD` empty.

### 7. Run Database Migrations & Seeders

Run the migrations:

```bash
php artisan migrate
```

To populate the database with the included demo data, run:

```bash
php artisan db:seed
```

Alternatively, migrate and seed in one command:

```bash
php artisan migrate --seed
```

> Run the seeders only in a local/demo environment where inserting the sample data is appropriate. Do not use demo credentials in production.

### 8. Start the Laravel Development Server

```bash
php artisan serve
```

Open the application in your browser:

**http://127.0.0.1:8000**

---

## 🔐 Demo Login Credentials

After successfully running the database seeders, use the following demo accounts:

| Role | Email | Password |
|---|---|---|
| Admin | admin@meditrack.test | password |
| Pharmacist | pharmacist@meditrack.test | password |
| Staff | staff@meditrack.test | password |

These are local demo credentials. Do not use them in a production deployment.

---

## 🧪 Running Tests

MediTrack includes feature tests for authentication, medicines, batches, stock updates, expiry status, role authorization, user management, and profile management.

Run the test suite using:

```bash
php artisan test
```

Ensure your testing environment and database configuration are set up correctly before running tests.

---

## 📁 Project Structure

```text
medicine-stock-tracker/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   ├── Providers/
│   └── Support/
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
│   └── web.php
├── storage/
├── tests/
│   └── Feature/
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md
```

---

## 🗄️ Database Modules

| Table | Description |
|---|---|
| users | User accounts, roles, and account status |
| categories | Medicine categories |
| medicines | Medicine details |
| batches | Batch-wise quantity, pricing, and expiry |
| stock_transactions | Stock movement history |
| settings | Pharmacy and inventory settings |

Stock and expiry statuses are calculated dynamically from batch quantities, minimum stock levels, expiry dates, and the configured near-expiry threshold.

---

## ⚠️ Troubleshooting

### MySQL Connection Refused

- Ensure MySQL Server is running.
- Verify the host, port, username, and password in `.env`.

### Unknown Database Error

Create the `medicine_tracker` database in MySQL Workbench and verify the `DB_DATABASE` value.

### Vite Manifest Not Found

Run:

```bash
npm install
npm run build
```

### Application Key Missing

Run:

```bash
php artisan key:generate
```

### Migration or Seeding Issues

Verify the database configuration and check migration status:

```bash
php artisan migrate:status
```

For a fresh local development database, you can rebuild the schema and seed data using:

```bash
php artisan migrate:fresh --seed
```

> Warning: `migrate:fresh` drops all existing tables in the configured database. Do not run it on a database containing data you need.

---

## 🔒 Security Notes

- Keep `.env` out of version control.
- Never commit real database passwords, API keys, or private credentials.
- Change demo account passwords before any production use.
- Use HTTPS and secure environment configuration when deploying.
- Review authorization and account security before production deployment.

---

## 🖼️ Screenshots

Screenshots of the dashboard, medicine inventory, expiry alerts, and reports can be added here.

Example:

```markdown
![MediTrack Dashboard](docs/dashboard.png)
```

---

## 🎓 Project Information

**Project Name:** MediTrack – Medicine Stock & Expiry Tracker

**Project Type:** Full-Stack Web Application

**Domain:** Pharmacy & Inventory Management

**Framework:** Laravel

**Database:** MySQL

---

## 👩‍💻 Author

**Bhumika Patil**

GitHub: [Bhumika1604](https://github.com/Bhumika1604)

---

## 📄 License

This project is intended for educational and learning purposes. Add an appropriate license before permitting reuse or redistribution.
