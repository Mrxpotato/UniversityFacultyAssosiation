# University Faculty Association (UFA) Management System
## System Documentation

**Prepared by:** Development Team
**System Version:** 1.0 (Laravel 12)
**Date:** June 2026
**Classification:** Internal Use

---

## Table of Contents

1. [System Overview](#1-system-overview)
2. [Objectives](#2-objectives)
3. [Technology Stack](#3-technology-stack)
4. [System Requirements](#4-system-requirements)
5. [User Roles & Access Control](#5-user-roles--access-control)
6. [System Modules](#6-system-modules)
7. [Claims Workflow](#7-claims-workflow)
8. [Database Structure](#8-database-structure)
9. [Security & Audit](#9-security--audit)
10. [Installation & Deployment](#10-installation--deployment)
11. [Campus Directory](#11-campus-directory)

---

## 1. System Overview

The **University Faculty Association (CSU-UFA) Management System** is a web-based platform built to digitize, streamline, and centralize the administrative operations of the Cagayan State University Faculty Association. Prior to this system, faculty association processes — including membership enrollment, claims filing, financial reporting, and event management — were handled manually through paper forms and physical ledgers, resulting in delays, data inconsistencies, and a lack of transparency.

This system replaces those manual processes with a unified digital platform that supports multi-campus operations, enforces role-based access control, and provides real-time visibility into all association activities.

> **Mission Statement:** *"Committed to Service, United for Upliftment, Faculty in Action"* — The system upholds this mission by ensuring every faculty member has quick, transparent, and accountable access to the benefits and services of the association.

---

## 2. Objectives

The UFA Management System was developed with the following objectives:

1. **Digitize Membership Records** — Maintain accurate, searchable faculty profiles across all campuses.
2. **Automate Claims Processing** — Implement a structured multi-stage workflow from submission to fund release.
3. **Ensure Financial Transparency** — Publish financial reports and fund transactions accessible to all members.
4. **Enforce Role-Based Governance** — Ensure only authorized officers can approve, release, or manage sensitive records.
5. **Support Professional Development** — Manage trainings, seminars, and workshops with registration tracking.
6. **Provide Communication Channels** — Enable official memos, resolutions, and announcements to reach members digitally.
7. **Enable Multi-Campus Operations** — Support all 9 CSU campuses under a unified system with campus-scoped access.

---

## 3. Technology Stack

| Layer | Technology | Purpose |
|---|---|---|
| **Framework** | Laravel 12 (PHP 8.2+) | Backend application framework |
| **Frontend** | Blade Templates | Server-side rendered UI |
| **CSS Framework** | Tailwind CSS | Utility-first styling |
| **JavaScript** | Alpine.js | Lightweight reactive UI components |
| **Build Tool** | Vite | Frontend asset bundling & HMR |
| **Database** | SQLite (default) / MySQL / PostgreSQL | Data persistence |
| **Authentication** | Laravel Breeze | Session-based auth with role support |
| **Queue** | Laravel Queue (database driver) | Background job processing |
| **Address Data** | Philippine PSGC | Region, province, city, barangay lookups |
| **Icons** | Google Material Icons | UI iconography |
| **Testing** | PHPUnit 11 | Unit and feature testing |

---

## 4. System Requirements

### Server Requirements

| Requirement | Minimum |
|---|---|
| PHP | >= 8.2 |
| Composer | >= 2.x |
| Node.js | >= 18.x |
| Database | SQLite (bundled) OR MySQL 8+ / PostgreSQL 14+ |

### Client Requirements

| Requirement | Details |
|---|---|
| Browser | Chrome 90+, Firefox 88+, Edge 90+, Safari 14+ |
| Screen | Fully responsive — mobile, tablet, and desktop |

---

## 5. User Roles & Access Control

The system implements a **structured 5-tier role hierarchy**. Each role grants specific permissions, ensuring least-privilege access at every level.

### Role Hierarchy

| Role | Label | Scope | Key Permissions |
|---|---|---|---|
| `super_admin` | **System Administrator** | All campuses | Full access to all modules and settings |
| `university_president` | **University President** | All campuses | Approve/reject/remand claims; manage all member registrations; view all reports |
| `university_treasurer` | **University Treasurer** | All campuses | Release claims; manage dues, collections, deductions, fund monitoring |
| `campus_president` | **Campus President** | Single campus | Manage members and pending registrations for their campus |
| `faculty` | **Faculty Member** | Own records | Submit claims; view own profile, beneficiaries, deductions; register for trainings |

### Permission Matrix

| Action | Super Admin | Univ. President | Univ. Treasurer | Campus President | Faculty |
|---|:---:|:---:|:---:|:---:|:---:|
| View Dashboard | ✅ | ✅ | ✅ | ✅ | ✅ |
| Manage All Members | ✅ | ✅ | ✅ | Campus only | ❌ |
| Approve/Reject Claims | ✅ | ✅ | ❌ | ❌ | ❌ |
| Release Claims | ✅ | ❌ | ✅ | ❌ | ❌ |
| Manage Dues & Collections | ✅ | ❌ | ✅ | ❌ | ❌ |
| Manage Transparency Posts | ✅ | ❌ | ❌ | ❌ | ❌ |
| Manage Trainings | ✅ | ❌ | ❌ | ❌ | ❌ |
| Assign Officers | ✅ | ❌ | ❌ | ❌ | ❌ |
| Configure Claim Types | ✅ | ❌ | ❌ | ❌ | ❌ |
| System Settings | ✅ | ❌ | ❌ | ❌ | ❌ |
| Submit Own Claims | ✅ | ✅ | ✅ | ✅ | ✅ |
| View Transparency Wall | ✅ | ✅ | ✅ | ✅ | ✅ |
| Register for Trainings | ✅ | ✅ | ✅ | ✅ | ✅ |

### Officer Position Notes

UFA officers hold **two concurrent designations**:
- A **campus position** (e.g., Campus President of Aparri) — governs system role and access scope.
- A **UFA officer position** (e.g., President, VP, Secretary, Treasurer, Auditor, Board Member, PIO) — their designation within the association's governing board.

The **University President** and **University Treasurer** UFA positions carry elevated system permissions (claim approval and claim release, respectively). All other UFA positions maintain campus-level access.

---

## 6. System Modules

### 6.1 Public Landing Page

The system's homepage (`/`) is publicly accessible and serves as the entry point for all users.

**Key Sections:**
- **Hero Section** — Animated banner with UFA branding, tagline, and calls-to-action (Member Login / Request Account).
- **Statistics** — Animated counters: 500+ Active Members, 9 Campuses, ₱2M+ Claims Disbursed, 50+ Trainings Held.
- **Member Benefits** — Cards: Medical Assistance, Retirement Benefits, Professional Development, Death Aid, Financial Transparency, Strong Community.
- **How It Works** — 4-step visual process: Request Account → Pay Dues → File Claims → Receive Benefits.
- **Campus Network** — Grid of all 9 CSU campuses.
- **Quick Links** — Direct access to Transparency Wall, Trainings, and Campus Directory.

---

### 6.2 Authentication & Registration

#### Login
Faculty members and officers log in using their **Employee ID** and password. Inactive accounts are blocked from logging in.

#### Registration Workflow (Pending Registration Queue)

New faculty members do not receive an account immediately. The process is:

1. Faculty submits a registration request via the public form.
2. The request enters a **Pending Registrations queue** visible to Super Admin, University President, and the relevant Campus President.
3. An officer reviews and **approves or rejects** the request.
4. Upon approval, a user account is created and the faculty profile is activated.

This approval flow prevents unauthorized access and ensures data accuracy before account creation.

---

### 6.3 Dashboard

The dashboard is **role-adaptive** — it displays data relevant to the logged-in user's role.

**Officers see:** Member count, pending registration alerts, claims summary (by status), recent financial activity, quick-action buttons.

**Faculty members see:** Personal profile summary, own claim statuses, upcoming training events, recent transparency posts.

---

### 6.4 Member Management

**Access:** Super Admin, University President, University Treasurer (all campuses); Campus President (their campus only).

#### Faculty Profile Fields

| Category | Fields |
|---|---|
| **Personal** | Prefix (Dr./Mr./Ms./Engr.), Surname, First Name, Middle Name, Extension, Gender, Civil Status, Birth Date, Birth Place |
| **Contact** | Phone Number, Home Address (PSGC-linked Province, City, Barangay, Street) |
| **Employment** | Employee ID, Date of Service, Academic Rank, College, Campus |
| **Membership** | Membership Status (Active/Inactive/Resigned/Retired/Deceased), Membership Date |
| **Position** | UFA Officer Position, Campus Position |
| **Photo** | ID Picture Upload |
| **Notes** | Internal admin notes |

The system automatically computes: **Age**, **Years of Service**, and **7th Day of Service** from the stored dates.

#### Member Actions
- **View / Edit Profile** — Full profile with all fields editable.
- **Beneficiary Management** — Add/edit dependents (spouse, children, parents) per member.
- **Archive/Deactivate** — Soft-delete or deactivate without destroying records.
- **Search & Filter** — By name, employee ID, campus, college, rank, or membership status.

---

### 6.5 Claims Management

Claims management is the **core module** of the system. Faculty members submit financial assistance requests that move through a structured review-to-release workflow.

#### Claim Types (Configurable by Super Admin)
- **Medical Assistance** — Hospitalization and health emergency support.
- **Death Aid** — Funeral and bereavement assistance for member or dependents.
- **Retirement Benefits** — Special package upon separation from service.
- Other assistance categories (fully configurable).

Each claim type has configurable **required documents** and **deduction rules**.

#### Submitting a Claim (Faculty)
1. Select claim type.
2. Optionally link a beneficiary (for dependent-related claims).
3. Enter claim amount and description.
4. Upload supporting documents.
5. Submit — auto-generates a unique **Claim Number** (`CLM-XXXXXXXX`).

#### Claims Monitoring (Officer View)
Officers see all claims with filtering by status, campus, and claim type. Role permissions strictly govern which actions each officer can take at each stage.

---

### 6.6 Financial Management

**Access:** Super Admin and University Treasurer.

| Sub-Module | Description |
|---|---|
| **Deductions Collection** | Tracks membership dues payments per faculty member per period. |
| **Deduction Settings** | Configures deduction rules per claim type — fixed peso amount or percentage of claim. |
| **Fund Monitoring** | Unified financial ledger of all collections and releases; shows association financial position. |
| **Collections** | Individual income/collection transaction records. |
| **Releases** | Individual fund disbursement records per claim. |
| **Monthly Deductions** | Per-faculty monthly salary deduction records (separate from claim deductions). |

---

### 6.7 Transparency & Communication

**Access:** Super Admin (manage); All authenticated users (view).

#### Transparency Wall
The public financial bulletin board of the association. Posts include quarterly statements, fund summaries, and announcements. Visibility can be set to **Public** (anyone) or **Members Only**.

#### Memos
Official internal memos from officers to all members or specific campuses. Date-stamped and archived.

#### Resolutions
Formal association resolutions (board decisions, policy changes) documented and archived for institutional memory.

---

### 6.8 Trainings & Seminars

**Admin:** Content → Trainings Management
**Faculty:** Resources → Trainings & Seminars

#### Event Fields

| Field | Description |
|---|---|
| Title & Type | Training, Seminar, Workshop, Conference, or Webinar |
| Scope | Per-campus or University-wide |
| Schedule | Date, time, and venue |
| Participant Limit | Maximum registrants enforced |
| Registration Fee | Optional fee per participant |
| Status | Upcoming, Ongoing, Completed, Cancelled |
| Announcements | Rich-text announcements per event |

Faculty members browse upcoming events and register online. The system enforces participant limits and prevents duplicate registrations.

---

### 6.9 Officer Management

**Access:** Super Admin only.

#### Section 1 — UFA Officer Positions

Assigns association-wide positions to existing campus presidents:

| Position | System Impact |
|---|---|
| **President** | Elevates to `university_president` — claim approval/rejection access |
| **Treasurer** | Elevates to `university_treasurer` — claim release and financial access |
| **Vice President, Secretary, Auditor, Board Members, PIO** | Maintain `campus_president` level access |

#### Section 2 — Campus Presidents

Assigns a campus president to each CSU campus chapter. Any active member of that campus can be designated, which elevates their role to `campus_president`.

> **Rule:** All UFA officers must first be a Campus President. UFA officer positions are overlaid on top of the campus role.

---

### 6.10 Campus Management

**Access:** Super Admin only (management); All users (directory view).

#### Admin Functions
- Create and edit campus records with full PSGC geolocation data.
- Manage colleges per campus.
- Configure academic ranks (with salary grade).

#### Public Campus Directory
Accessible by all members — shows each campus with location info and assigned president.

---

### 6.11 My Account (Faculty Portal)

Available to all authenticated users (non-admin sidebar section):

| Page | Description |
|---|---|
| **My Profile** | View and edit personal profile information |
| **Beneficiaries** | Add, edit, or remove registered dependents |
| **My Claims** | Submit new claims; track all claim statuses with full history timeline |
| **Salary Deductions** | View all salary deductions applied to the account |

---

### 6.12 System Settings

**Access:** Super Admin only.

| Setting | Description |
|---|---|
| **Organization Name** | Display name of the association used system-wide |
| **Registration Toggle** | Enable/disable the public faculty registration form |
| **Process Toggles** | Feature flags for specific system processes |

---

## 7. Claims Workflow

Claims follow a **strict 7-stage linear workflow** with branching rejection and remand paths at any stage.

```
[Faculty Submits Claim]
         │
         ▼
    ┌─────────┐
    │ PENDING │  ← Auto-generated CLM-XXXXXXXX
    └────┬────┘
         │ Officer initiates review
         ▼
  ┌─────────────┐
  │ UNDER REVIEW│  ← Reviewed by Univ. President / Super Admin
  └──────┬──────┘
         │ Approved
         ▼
   ┌──────────┐
   │ APPROVED │  ← Approved by Univ. President / Super Admin
   └─────┬────┘
         │ Billing initiated
         ▼
  ┌──────────────────────┐
  │ BILLING & COLLECTION │  ← Billing documents prepared
  └──────────┬───────────┘
             │
             ▼
  ┌──────────────────────┐
  │ COLLECTION MONITORING│  ← Deductions/dues being collected
  └──────────┬───────────┘
             │ Check prepared
             ▼
     ┌───────────────┐
     │ CHECK PREPARED│  ← Payment check ready
     └───────┬───────┘
             │ Released by Treasurer / Super Admin
             ▼
       ┌──────────┐
       │ RELEASED │ ✅  ← Funds disbursed to claimant
       └──────────┘

     ── At ANY stage (except Released) ──

  ┌──────────┐         ┌──────────┐
  │ REJECTED │ ✗       │ REMANDED │ ↩
  └──────────┘         └──────────┘
  Claim closed         Returned to faculty
  with reason          for correction;
                       can be resubmitted
```

### Stage Reference

| Stage | Actor | Action |
|---|---|---|
| **Pending** | System | Claim submitted; awaiting officer review |
| **Under Review** | Super Admin / Univ. President | Claim is being actively evaluated |
| **Approved** | Super Admin / Univ. President | Claim approved for disbursement |
| **Billing & Collection** | Super Admin / Univ. Treasurer | Billing documents being prepared |
| **Collection Monitoring** | Super Admin / Univ. Treasurer | Deductions/collections being processed |
| **Check Prepared** | Super Admin / Univ. Treasurer | Payment check prepared |
| **Released** | Super Admin / Univ. Treasurer | Funds disbursed to claimant |
| **Rejected** | Any authorized officer | Claim denied with stated reason |
| **Remanded** | Any authorized officer | Returned to faculty for correction/resubmission |

---

## 8. Database Structure

The system uses **46 database migrations**. Default database is **SQLite**; configurable to MySQL or PostgreSQL for production.

### Core Tables

| Table | Description |
|---|---|
| `users` | Authentication accounts — role, employee ID, campus, active status |
| `faculty_profiles` | Full faculty data — personal, employment, membership, officer positions |
| `campuses` | Campus records with PSGC geographic data |
| `colleges` | Colleges organized per campus |
| `academic_ranks` | Academic rank definitions with salary grade |
| `beneficiaries` | Dependents registered per faculty member |

### Claims Tables

| Table | Description |
|---|---|
| `claim_types` | Configurable claim categories with required documents |
| `claims` | All claim records with full workflow status, dates, amounts, deductions, actor references |
| `deduction_settings` | Deduction rules (fixed or percentage) per claim type |
| `monthly_deductions` | Per-faculty monthly salary deduction records |

### Financial Tables

| Table | Description |
|---|---|
| `dues` | Membership dues records per faculty per period |
| `collections` | Income/collection transaction records |
| `releases` | Fund disbursement records |
| `fund_transactions` | Unified financial ledger combining all activity |

### Communication Tables

| Table | Description |
|---|---|
| `transparency_posts` | Financial reports and announcements |
| `memos` | Official memos from officers |
| `resolutions` | Formal association resolutions |

### Training Tables

| Table | Description |
|---|---|
| `trainings_seminars` | Training and seminar event records |
| `training_registrations` | Faculty registrations per event |
| `training_announcements` | Announcements tied to specific events |

### System Tables

| Table | Description |
|---|---|
| `pending_registrations` | Pending faculty registration queue |
| `system_settings` | Key-value store for configurable settings |
| `audit_logs` | Immutable audit trail of all significant actions |
| `notifications` | In-app notification records per user |
| `ph_provinces` / `ph_cities` / `ph_barangays` | Philippine PSGC address reference tables |

---

## 9. Security & Audit

### Role-Based Access Control
- Every route and controller is guarded by middleware and policy checks.
- Campus Presidents are strictly scoped to their campus — cannot view or modify other campuses' records.
- Soft deletes are used across all major models — records are never permanently destroyed.

### Audit Logging
All significant actions are recorded in `audit_logs`, capturing:
- **Who** — User ID and name.
- **What** — Action type and description.
- **When** — Exact timestamp.
- **Changes** — Before and after state where applicable.

### Account Security
- Passwords are **bcrypt-hashed** (never stored in plaintext).
- Inactive accounts (`is_active = false`) are blocked from login.
- Employee ID uniqueness enforced at database and application level.
- Session-based authentication via Laravel Breeze.

### Data Integrity
- Financial amounts use decimal precision (2 decimal places).
- Claim numbers are auto-generated unique identifiers (`CLM-XXXXXXXX`).
- Database foreign key constraints enforce referential integrity.
- Unique indexes prevent duplicate records on critical fields.

---

## 10. Installation & Deployment

### Automated Setup (Recommended)

```bash
git clone <repository-url>
cd UniversityFacultyAssociation
composer run setup
```

The `composer run setup` script automatically:
1. Installs PHP dependencies
2. Copies `.env.example` → `.env`
3. Generates the application key
4. Runs all 46 database migrations
5. Installs Node.js dependencies
6. Builds all frontend assets

### Development Mode

```bash
composer run dev
```

Concurrently starts:
- **PHP server** at `http://localhost:8000`
- **Queue worker** for background notifications
- **Laravel Pail** log viewer
- **Vite** dev server with Hot Module Replacement

### Environment Configuration

```env
APP_NAME="University Faculty Association"
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite   # or mysql / pgsql for production

MAIL_MAILER=smtp       # Use 'log' for development
```

---

## 11. Campus Directory

The CSU-UFA system serves **9 campuses** of Cagayan State University:

| Campus | Officer Name | Employee ID | System Role |
|---|---|---|---|
| Piat Campus | Dr. Ricardo Casaway | `PRES-PIAT` | University President |
| Andrews Campus | Dr. Jaypee Alegado | `TRES-ANDREWS` | University Treasurer |
| Aparri Campus | Dr. Billy S. Javier | `VP-APARRI` | Campus President |
| Carig Campus | Ms. Helen Dangani | `ATRES-CARIG` | Campus President |
| Gonzaga Campus | Dr. Bert Bermudez | `AUD-GONZAGA` | Campus President |
| Lal-lo Campus | Dr. Domingo Dela Vega | `BM-LALLO` | Campus President |
| Lasam Campus | Dr. Jehan Gerardo | `SEC-LASAM` | Campus President |
| Sanchez Mira Campus | Dr. Freddic Masuli | `PIO-SANCHEZ` | Campus President |
| Solana Campus | Mr. Stanley Follante | `BM-SOLANA` | Campus President |

> **Note:** The University President (Piat Campus) and University Treasurer (Andrews Campus) are university-level officers who simultaneously serve as campus heads for their respective campuses.

---

## Appendix A — Navigation Reference

| Section | Menu Item | Role Access |
|---|---|---|
| **Main** | Dashboard | All |
| **Administration** | Claims & Finance | Officers only |
| | — Claims Monitoring | All officers |
| | — Claim Types | Super Admin only |
| | — Deductions Collection | Super Admin, Treasurer |
| | — Deduction Settings | Super Admin, Treasurer |
| | — Fund Monitoring | Super Admin, Treasurer |
| | Members & Campus | Super Admin, President, Campus President |
| | — Faculty Members | All officers |
| | — Pending Registrations | Super Admin, President, Campus President |
| | — Campus Management | Super Admin only |
| | Content | Super Admin only |
| | — Transparency Posts | Super Admin only |
| | — Trainings Management | Super Admin only |
| | — Transparency Wall (admin) | Super Admin only |
| | Officer Management | Super Admin only |
| | System Settings | Super Admin only |
| **My Account** | My Profile | All non-Super Admin |
| | Beneficiaries | All non-Super Admin |
| | My Claims | All non-Super Admin |
| | Salary Deductions | All non-Super Admin |
| **Resources** | Transparency Wall | All |
| | Trainings & Seminars | All |
| | Campuses | All |
| | Officers | All |

---

## Appendix B — Key Business Rules

1. Claims cannot be edited once they pass the Pending or Remanded stage.
2. A **remanded** claim can be corrected and resubmitted by the faculty member.
3. A **rejected** claim is permanently closed; a new claim must be filed.
4. Only the **University Treasurer** or **Super Admin** can release a claim (final disbursement).
5. Only the **University President** or **Super Admin** can approve or reject claims.
6. Campus Presidents can only see members and registrations from **their own campus**.
7. Employee IDs must be **unique** across all user accounts.
8. All UFA officer positions except President and Treasurer maintain `campus_president` level access.
9. Deactivated accounts (`is_active = false`) cannot log in but their records are preserved.
10. The registration form can be disabled by Super Admin via System Settings.

---

*Document generated for the University Faculty Association Management System — CSU-UFA.*
*Built with Laravel 12 · Tailwind CSS · Alpine.js*
