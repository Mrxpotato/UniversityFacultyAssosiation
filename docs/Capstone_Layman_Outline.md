# University Faculty Association Management System — Capstone Outline (Non-Technical)

A plain-language overview for the capstone paper / presentation. Avoids heavy technical jargon.

---

## 1. Project Overview

### The Gap (Problem)
The CSU Faculty Association ran its operations entirely on paper. Membership records, financial claims, and fund ledgers were tracked in physical forms and handwritten logbooks spread across 9 campus chapters. This caused slow processing, lost or duplicated records, unclear claim status, and no way to track how funds were managed.

### The Need
A single system that lets the association manage **members, financial claims, and funds in one place** — where every campus shares the same records, the right officer can only do what their role allows, and members can see how association money is spent.

### What Makes This Different
- **A complete claims process** — a claim doesn't just get saved; it moves through a clear step-by-step pipeline (submitted → reviewed → approved → billing → collection → check prepared → released), with options to reject or return it for correction.
- **Clear role hierarchy** — the system mirrors how the association is really governed: a System Administrator, University President, University Treasurer, Campus Presidents, and regular faculty. Presidents of each campus can only see and manage their own campus's members.
- **Transparency as a core feature** — financial reports and announcements can be published for everyone or for members only, so members are never in the dark about association funds.
- **Safety-first record keeping** — every important action is recorded (who did it and what changed), and records are never permanently deleted.
- **Philippine address support** — addresses follow the country's standard province/city/barangay format, and campuses have geographic location data.

### The Solution
An easy-to-use web system where faculty can create their profiles, file claims with supporting documents, check claim progress, register for trainings, and view financial reports — while officers process those claims and manage finances through an approved, step-by-step workflow.

### The Benefit
- **Faster service** — claims and member registrations are processed in days instead of weeks.
- **Accountable finances** — every peso received and released is tracked in one central fund record.
- **Reliable records** — centralized, searchable, and never accidentally lost.
- **Transparent governance** — members can see reports and hold officers accountable.

---

## 2. System Architecture (in Simple Terms)

Think of the system as three parts working together:

1. **What the user sees** (the screen, viewed in any web browser) — pages for logging in, seeing the dashboard, filing claims, viewing reports, and managing members.
2. **The brain** (the application) — where all the rules live:
   - It decides who can do what (a regular member cannot approve claims; only the system administrator or university president can).
   - It enforces campus boundaries (a Campus President only sees their own campus's data).
   - It controls the claims workflow step-by-step so claims cannot skip stages.
3. **The memory** (the database) — where all records are stored: members, claims, finances, announcements, trainings, and the log of every action.

**How a claim travels through the system:** A faculty member fills out the claim form → the system checks that all needed information is complete → the claim is stored and given a unique claim number → the responsible officer gets a notification → the claim moves through review, approval, billing, collection, and finally release — with each step recorded and only allowed if the user's role permits it.

The system also includes safeguards: a security check (captcha) on login/registration, secure password storage, and protection against common web attacks.

---

## 3. The Database Component

### 3a. How the Data Is Connected (the "picture" of the database)

The database is like a set of interconnected file folders:

- **One "User" record** connects to **one "Faculty Profile"** (a member's full information). That profile connects to their **Campus**, their **College**, and their **Academic Rank**.
- **Each faculty profile** can have **many Beneficiaries** (dependents), **many Claims**, and **many Dues payments**.
- **Each Claim** belongs to a member and a **Claim Type** (medical, death aid, etc.); it can optionally point to a **Beneficiary**, and it records who reviewed, approved, and released it.
- **Training Events** connect to **Registrations**, and each registration links back to a faculty member.
- Most announcements and reports (transparency posts, memos, resolutions, collections, releases, training announcements) record **who created them**.
- Every action gets written to a central **activity log** so the history can be traced.

In short: the database is normalized — each person, claim, and report is stored once, and related records point to each other. This prevents duplicate and conflicting data.

### 3b. Use Cases (what each role does with the system)

| Role | What they can do |
|---|---|
| **Visitor / New Faculty** | Browse public pages, view transparency wall and trainings, submit a registration request |
| **Campus President** | Approve/reject registration requests and manage members **of their own campus only** |
| **University President** | Approve, reject, or return claims; manage registrations of all campuses |
| **University Treasurer** | Release claims, manage dues and deductions, monitor funds |
| **System Administrator** | Everything: system settings, officers, claim types, campuses, trainings, transparency content |
| **Faculty Member** | Update own profile, add beneficiaries, file and track claims, view own salary deductions, register for trainings |

### 3c. Data Dictionary (main tables and what they hold)

| Table (folder) | What it stores | Key fields |
|---|---|---|
| **users** | Login accounts | Employee ID (used for login), name, password, role, campus |
| **faculty_profiles** | Full member record | Name, gender, civil status, birth date/place, address, phone, photo, employee ID, date of service, rank, college, campus, membership status/date, officer positions, notes |
| **campuses** | The 9 CSU campuses | Name, code, address, contact info, geographic/PSGC data, GPS coordinates |
| **colleges** | Colleges under each campus | Name, code, campus |
| **academic_ranks** | Instructor → Professor levels | Name, level, monthly dues, salary grade |
| **beneficiaries** | Member's dependents | Full name, relationship, birth date, contact, primary flag |
| **claim_types** | Claim categories | Name, code, max amount, whether a beneficiary is required, required documents |
| **claims** | All claim applications | Unique claim number, member, claim type, amount, deduction & net amount, dates at each stage, status, reasons (if rejected/returned), who reviewed/approved/released |
| **dues** | Membership payments | Member, amount, month, due/paid date, payment status |
| **deduction_settings** | Rules for claim deductions | Per rank and claim type: percentage or fixed amount |
| **monthly_deductions** | Monthly salary deductions per member | Member, month, gross salary, deduction amount, status |
| **fund_transactions** | Central money ledger | Type (incoming/outgoing), category, amount, description, which record it came from, who recorded it |
| **transparency_posts** | Financial reports & announcements | Title, type, visibility (public/members only), author, published flag |
| **memos, resolutions** | Official documents | Document number, title, content, category, status (draft/published/archived) |
| **collections, releases** | Published financial reports | Report number, title, period covered, total amount, status |
| **trainings_seminars, training_registrations, training_announcements** | Training events & registrations | Event details, limits, fees, status; who registered; announcements |
| **pending_registrations** | Waitlist for new members | Applicant details, campus, employee ID, uploaded ID photos, approval status |
| **system_settings** | System toggles | Key-value pairs like "registration on/off" |
| **audit_logs** | Activity trail | Who, what action, when, what changed, IP address |
| **ph_provinces, ph_cities, ph_barangays** | Standard Philippine address lists | Province → city → barangay hierarchy |

---

## 4. The Database Project — Features & Status

### Features
- **Complete record structure** — 46 organized database tables covering members, claims, finances, communications, trainings, and system settings — all built steadily over time (the file versions show how features were added: role hierarchy, claim workflow stages, deduction rules, Philippine address support, unified money ledger).
- **Ready for demonstration** — the database comes **already filled with realistic sample data**: 9 campuses, all 10 officers and a sample faculty member, example claims, published financial reports, and upcoming/completed trainings. This makes live demos and screenshots easy.
- **Automatic safety rules** — when a member is removed, their related items behave safely; important numbers (employee ID, claim numbers) are unique so nothing gets mixed up; records are never permanently destroyed.
- **One place for all money** — a single fund ledger holds every incoming and outgoing amount, linked to the claim, dues, or collection that caused it.
- **Performance ready** — commonly searched fields are indexed so lookups stay fast even with many records.

### Status

| Item | Status |
|---|---|
| Database created | ✅ Fully set up (`database.sqlite` exists, ~1 MB, has the sample data) |
| One-command setup | ✅ `composer run setup` builds everything; `composer run dev` starts the app |
| Sample accounts for the demo | ✅ All login with password `password` — e.g. `ADMIN-001` (Admin), `PRES-PIAT` (Univ. President), `TRES-ANDREWS` (Treasurer), `FAC-001` (Faculty) |
| Countries/provinces reference data | ✅ Philippine standard address data included |

### Note for the paper
The tables named **Collections** and **Releases** are actually **published financial reports** (for the Transparency Wall), not the raw income/expense records. The actual day-to-day money movements are stored in the **fund transactions** table. Use this wording to avoid a technical panel catching a mismatch.