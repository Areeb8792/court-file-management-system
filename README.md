# 🏛️ JurisFlow: Digital Judicial Workflow & Court File Management System

JurisFlow is a modern, enterprise-grade Digital Judicial Workflow and Court File Management SaaS platform built on **Laravel 12**. Engineered for supreme visual excellence, JurisFlow introduces a premium glassmorphic dark interface that streamlines the end-to-end litigation lifecycle. It connects judicial officials (Admins, Registry Clerks, and Judges), legal counsels, and public citizens within a unified secure portal.

---

## 🚀 Key Functional Modules

### 🛡️ 1. Security & Role-Based Access Control (RBAC)
- **Custom Security Middleware:** Powered by custom `'role'` middleware gates that filter all requests and resource routes.
- **Five Tailored Workspaces:**
  - **System Admin:** Overview of central registries, visual SVG analytics charts, global audit streams, and system settings.
  - **Registry Clerk:** Operates the case docket registry, logs newly filed lawsuits, and schedules trial hearings.
  - **Presiding Judge:** High-priority today's courtroom list, upcoming calendars, remarks tracking, and final written judgment archivers.
  - **Defense Attorney:** Counsel briefing portfolio, evidentiary submissions chamber, and scheduled trial dates.
  - **Citizen / Observer:** notice boards, legal assistance centers, and quick case lookups.

### 📂 2. Case Lifecycle Registry
- **Dynamic Case Code Generation:** Automatically calculates and registers a unique judicial case code (e.g., `CRT-2026-0001` to `CRT-2026-9999`) sequentialized by the active calendar year.
- **Litigation Class Selector:** filing options supporting **Criminal Cases**, **Civil Suits**, **Constitutional Writs**, and **Family Petitions**.
- **Urgency Priority Triage:** Triages files as *Low*, *Medium*, *High*, or *Urgent (Fast Track)* to prioritize the bench calendar.
- **State Transition Stepper:** Tracks cases through six progressive stages:
  $$\text{Filed} \rightarrow \text{Under Review} \rightarrow \text{Hearing Scheduled} \rightarrow \text{Evidence Submitted} \rightarrow \text{Judgment Pending} \rightarrow \text{Closed}$$

### 📅 3. Double-Booking Preventive Hearing Scheduler
- **Courtroom Booking Validation:** Automates scheduling checks to block courtroom or judge conflicts. If a trial booking is placed in a room or assigned to a judge within a overlapping **30-minute interval** of an existing hearing, the scheduler blocks the request and alerts the clerk.
- **Presiding Calendar Docket:** Renders today's schedules, room allocations, time labels, and active docket statuses.

### 🔒 4. Evidentiary Vault & Document Version Chains
- **Version Control Vault:** Preserves evidence document histories. Instead of deleting old records, uploading a revised file creates a linked **Version Chain**. The original remains `v1` (with `parent_id = null`), while the revision becomes `v2` (linked to `parent_id = v1`), preserving a chronological chain of custody.
- **In-Browser Inline Previews:** Streamlines PDF evidence documents in a safe in-browser iframe without forcing a computer download.
- **Legal Privacy Locks:** Restricts confidential counsel folders from the public timeline, while keeping them open to presiding judges and defense attorneys.

### 📝 5. Audited System Ledger & Activity Trails
- **Automated Event Logging:** Programmatically logs every case creation, staff assignment, hearing reschedule, status transition, document upload, and judgment pronunciation.
- **Security Audit Details:** Logs the exact timestamp, details of the action, the name of the user, and the **active IP address** to maintain a tamper-proof system audit log.

### 🌐 6. Public Anonymous Trial Tracker
- **Anonymous Lookup Channel:** A citizen-facing tracking portal at `/track` allows observers to track any trial timeline using a Case Number without authenticating.
- **Graphic Trial Progress Timelines:** Renders a visual vertical trial stepper showcasing the case status, scheduled hearing times, assigned judge, and the final pronounced written judgment once the case is archived.

---

## 🛠️ Technology Stack & Architecture

- **Backend core:** PHP 8.2+ / Laravel 12 (MVC Architecture)
- **Frontend library:** Blade Templates, Alpine.js, Tailwind CSS Play CDN (instant in-browser styling fallback)
- **Database Engine:** SQLite (configured for lightweight instant deployments)
- **Authentication Engine:** Laravel Breeze (highly secure auth gates and password reset streams)
- **Iconography engine:** Iconify Vector Library

---

## 👥 Seeded Authentication Accounts

The portal features an integrated **One-Click Quick-Login Matrix** directly on the login screen (`http://127.0.0.1:8001/login`). Click any of the role buttons at the bottom of the card to authenticate instantly:

| Persona | Role | Seeded Email | Password | Panel Workspace |
| :--- | :--- | :--- | :--- | :--- |
| **System Admin** | `admin` | `admin@court.gov` | `password` | Central dashboard, SVG analytics, global system audit ledgers. |
| **Registry Clerk** | `clerk` | `clerk@court.gov` | `password` | Lodge new cases, update registries, allocate courtrooms. |
| **Bench Judge** | `judge` | `judge@court.gov` | `password` | Renders presiding trials list, drafts rulings, archives folders. |
| **Advocate Lawyer** | `lawyer` | `lawyer@court.gov` | `password` | Renders client case briefs, uploads evidence documents. |
| **Public Observer** | `public` | `public@court.gov` | `password` | Notice boards, legal assistance centers, citizen case lookup. |

---

## 💻 Local Installation & Setup

Follow these simple steps to configure and run JurisFlow on your local machine:

### 1. Clone & Navigate
```bash
cd Court-file-Management
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Setup Environment Configuration
Copy the default environment file:
```bash
cp .env.example .env
```
Ensure your database driver in `.env` is set to **SQLite** (JurisFlow uses a local SQLite database for zero-config simplicity):
```env
DB_CONNECTION=sqlite
# Remove other DB_* credentials (DB_HOST, DB_PORT, DB_DATABASE, etc.)
```

### 4. Create SQLite Database File
```bash
touch database/database.sqlite
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Run Migrations & Seeders
Execute the migrations to compile tables, and run the database seeder to create seeded cases, hearings, documents, and role accounts:
```bash
php artisan migrate:fresh --seed
```

### 7. Link Public Storage Disk
Connect the storage directories to allow evidence vault uploads, downloads, and PDF inline previews:
```bash
php artisan storage:link
```

### 8. Run Development Server
Fire up the local artisan development server:
```bash
php artisan serve --port=8001
```

Open your browser and navigate to `http://127.0.0.1:8001` to instantly explore the **JurisFlow** portal!

---

## 📂 Repository Directory Map

- **[`app/Models/`](file:///home/areeb/Court-file-Management/app/Models/)**: Core Eloquent Models (`CourtCase.php`, `Hearing.php`, `Document.php`, `ActivityLog.php`, `User.php`).
- **[`app/Http/Controllers/`](file:///home/areeb/Court-file-Management/app/Http/Controllers/)**: Request handlers (`CaseController.php`, `HearingController.php`, `DocumentController.php`, `DashboardController.php`, `PublicTrackController.php`).
- **[`app/Http/Middleware/`](file:///home/areeb/Court-file-Management/app/Http/Middleware/)**: RBAC Security gateways (`RoleMiddleware.php`).
- **[`routes/web.php`](file:///home/areeb/Court-file-Management/routes/web.php)**: Mapped endpoint directories (contains all 47 application endpoints).
- **[`database/seeders/DatabaseSeeder.php`](file:///home/areeb/Court-file-Management/database/seeders/DatabaseSeeder.php)**: Generates initial mock trials, cases, and credentials.
- **[`resources/views/`](file:///home/areeb/Court-file-Management/resources/views/)**: Blade template folders:
  - **[`layouts/app.blade.php`](file:///home/areeb/Court-file-Management/resources/views/layouts/app.blade.php)**: Master dark glassmorphic framework.
  - **[`dashboards/`](file:///home/areeb/Court-file-Management/resources/views/dashboards/)**: Role-specific workspaces.
  - **[`cases/`](file:///home/areeb/Court-file-Management/resources/views/cases/)**: Case creation, registry directories, and details timelines.
  - **[`hearings/`](file:///home/areeb/Court-file-Management/resources/views/hearings/)**: Courtroom schedule sheets.
  - **[`public/`](file:///home/areeb/Court-file-Management/resources/views/public/)**: Observer public timelines.
