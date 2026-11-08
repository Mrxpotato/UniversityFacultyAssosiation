# University Faculty Association Management System

A web-based management system for University Faculty Associations, built with **Laravel 12** and **Tailwind CSS**. This system digitizes and streamlines the administrative processes of a university faculty association — from member registration and claims management to financial transparency and professional development tracking.

---

## Table of Contents

- [About the System](#about-the-system)
- [Features](#features)
- [User Roles](#user-roles)
- [Tech Stack](#tech-stack)
- [System Requirements](#system-requirements)
- [Installation & Setup](#installation--setup)
- [Running the Application](#running-the-application)
- [Database Structure](#database-structure)
- [Claim Workflow](#claim-workflow)
- [Directory Structure](#directory-structure)
- [Testing](#testing)
- [License](#license)

---

## About the System

The **University Faculty Association Management System** is designed to help faculty associations manage their membership, financial operations, and institutional activities from a single, centralized platform. It supports multi-campus operations with a structured role hierarchy that ensures proper access control at every level.

---

## Features

### 👥 Member Management
- Faculty member registration with full profile (name, employee ID, gender, civil status, academic rank, college, campus, etc.)
- Membership status tracking (`active`, `inactive`, `resigned`, etc.)
- Beneficiary management per faculty member
- Philippine Standard Geographic Code (PSGC) address integration
- Photo upload support
- Pending registration queue with approval workflow
- Officer and campus position assignments

### 📋 Claims Management
- Submit financial claims (e.g., burial, medical, assistance)
- Multi-stage claim workflow: **Pending → Under Review → Approved → Billing & Collection → Collection Monitoring → Check Prepared → Released**
- Claim remanding with reasons
- Claim rejection with explanations
- Supporting document attachments
- Auto-generated unique claim numbers (`CLM-XXXXXXXX`)
- Deduction management (fixed or percentage) per claim
- Claims monitoring dashboard

### 💰 Financial Management
- **Collections** — Record and track income transactions
- **Releases** — Record and track fund disbursements
- **Fund Transactions** — Unified ledger for all financial activity
- **Monthly Deductions** — Track salary deductions per faculty member
- **Dues Management** — Manage and monitor membership dues
- **Deduction Settings** — Configure deduction rules per claim type

### 📢 Transparency & Communication
- **Transparency Posts** — Publish financial reports and announcements publicly or to members
- **Memos** — Official memos from officers to faculty members
- **Resolutions** — Document and archive association resolutions

### 🎓 Training & Seminars
- Create and manage training events, seminars, workshops, conferences, and webinars
- Per-campus or university-wide events
- Faculty registration for events with participant limit enforcement
- Training announcements visible to all members
- Registration fee tracking
- Status tracking: `Upcoming`, `Ongoing`, `Completed`, `Cancelled`

### 🏫 Campus & Academic Structure
- Multi-campus support with PSGC geolocation data (region, province, city, barangay)
- College management per campus
- Academic rank management with salary grade support
- System-wide settings (feature toggles, organization name, etc.)

### 🔒 Audit & Security
- Full audit logging for all significant actions
- Role-based access control
- Soft deletes across major models
- Notification system for key events

---

## User Roles

The system uses a structured role hierarchy:

| Role | Description | Key Permissions |
|------|-------------|-----------------|
| `super_admin` | System Administrator | Full access to all features across all campuses |
| `university_president` | Association President | Manage claims (approve/reject/remand), manage registrations, view all reports |
| `university_treasurer` | Association Treasurer | Release claims, manage dues and collections, financial operations |
| `campus_president` | Campus-level President | Manage members and registrations for their assigned campus |
| `faculty` | Regular Faculty Member | Submit claims, view own profile, register for trainings, view transparency posts |

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| **Framework** | Laravel 12 (PHP 8.2+) |
| **Frontend** | Blade Templates, Tailwind CSS, Vite |
| **Database** | SQLite (default) — configurable to MySQL/PostgreSQL |
| **Authentication** | Laravel Breeze |
| **Queue** | Laravel Queue (database driver) |
| **Address Data** | Philippine PSGC integration |
| **Testing** | PHPUnit 11 |
| **Dev Tools** | Laravel Pail (log viewer), Laravel Pint (code style), Laravel Sail |

---

## System Requirements

- **PHP** >= 8.2
- **Composer** >= 2.x
- **Node.js** >= 18.x & npm
- **SQLite** (bundled with PHP) or MySQL 8+ / PostgreSQL 14+

---

## Installation & Setup

### 1. Clone the Repository

```bash
git clone <repository-url>
cd UniversityFacultyAssociation
```

### 2. Automated Setup (Recommended)

Run the built-in setup script which handles everything automatically:

```bash
composer run setup
```

This will:
1. Install PHP dependencies (`composer install`)
2. Copy `.env.example` to `.env` (if not present)
3. Generate application key (`php artisan key:generate`)
4. Run all database migrations (`php artisan migrate`)
5. Install Node dependencies (`npm install`)
6. Build frontend assets (`npm run build`)

### 3. Manual Setup (Alternative)

```bash
# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create the SQLite database file (if using SQLite)
touch database/database.sqlite

# Run migrations
php artisan migrate

# (Optional) Seed the database with sample data
php artisan db:seed

# Install Node dependencies and build assets
npm install
npm run build
```

### 4. Environment Configuration

Edit `.env` to configure your database and other settings:

```env
APP_NAME="University Faculty Association"
APP_URL=http://localhost:8000

# SQLite (default)
DB_CONNECTION=sqlite

# OR MySQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=university_fa
# DB_USERNAME=root
# DB_PASSWORD=

MAIL_MAILER=log  # Use 'smtp' in production
```

---

## Running the Application

### Development Mode (All Services)

```bash
composer run dev
```

This concurrently starts:
- **PHP development server** (`php artisan serve`) — http://localhost:8000
- **Queue worker** (`php artisan queue:listen`)
- **Log viewer** (`php artisan pail`)
- **Vite dev server** (`npm run dev`) — Hot Module Replacement

### Individual Services

```bash
# Start only the PHP server
php artisan serve

# Start Vite (for CSS/JS hot reload)
npm run dev

# Start queue worker (for notifications, background jobs)
php artisan queue:listen --tries=1
```

---

## Database Structure

The system uses **SQLite** by default (stored at `database/database.sqlite`). The schema consists of **46 migrations** covering:

| Table | Description |
|-------|-------------|
| `users` | Authentication accounts with roles |
| `faculty_profiles` | Detailed faculty member profiles |
| `campuses` | Multi-campus with PSGC geo-data |
| `colleges` | Colleges per campus |
| `academic_ranks` | Academic rank levels with salary grades |
| `beneficiaries` | Dependents/beneficiaries per faculty |
| `claim_types` | Configurable claim categories |
| `claims` | Claim records with full workflow status |
| `dues` | Membership dues records |
| `transparency_posts` | Public/member-facing announcements |
| `trainings_seminars` | Training and seminar events |
| `training_registrations` | Faculty event registrations |
| `training_announcements` | Announcements for training events |
| `memos` | Official memos |
| `resolutions` | Association resolutions |
| `collections` | Income/collection records |
| `releases` | Fund disbursement records |
| `fund_transactions` | Unified financial ledger |
| `monthly_deductions` | Per-faculty monthly salary deductions |
| `deduction_settings` | Deduction configuration per claim type |
| `system_settings` | Application-wide configuration |
| `pending_registrations` | Queue for new member applications |
| `audit_logs` | Full audit trail |
| `notifications` | In-app notifications |
| `ph_provinces` / `ph_cities` / `ph_barangays` | Philippine PSGC address tables |

---

## Claim Workflow

Claims follow a strict multi-stage workflow:

```
[Faculty Submits]
       ↓
   PENDING
       ↓ (reviewed by officer)
  UNDER REVIEW
       ↓ (approved by President / Super Admin)
   APPROVED
       ↓
BILLING & COLLECTION
       ↓
COLLECTION MONITORING
       ↓
  CHECK PREPARED
       ↓ (released by Treasurer / Super Admin)
   RELEASED ✓

At any stage (except Released):
  → REJECTED ✗  (with reason)
  → REMANDED ↩  (returned for correction, can be resubmitted)
```

---

## Directory Structure

```
UniversityFacultyAssociation/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Application controllers
│   │   │   ├── Admin/            # Admin-only controllers
│   │   │   └── Auth/             # Authentication controllers
│   │   ├── Middleware/           # Custom middleware
│   │   └── Requests/             # Form request validation
│   ├── Models/                   # Eloquent models (26 models)
│   ├── Notifications/            # Laravel notification classes
│   ├── Policies/                 # Authorization policies
│   ├── Providers/                # Service providers
│   ├── Rules/                    # Custom validation rules
│   ├── Services/                 # Business logic services
│   └── View/                     # View composers
├── database/
│   ├── migrations/               # 46 migration files
│   ├── seeders/                  # Database seeders
│   └── factories/                # Model factories
├── resources/
│   └── views/                    # Blade templates
├── routes/
│   ├── web.php                   # Web routes
│   └── auth.php                  # Authentication routes
├── public/                       # Publicly accessible files
├── storage/                      # Uploaded files and logs
└── tests/                        # PHPUnit tests
```

---

## Testing

```bash
# Run all tests
composer run test

# Or directly
php artisan test
```

---

## License

This project is proprietary software developed for internal use by the University Faculty Association.

---

*Built with ❤️ using [Laravel](https://laravel.com) — The PHP framework for web artisans.*
