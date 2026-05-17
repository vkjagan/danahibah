# DanaHibah™ — Digital Donation Governance Web Application
## Project Plan Document

**Project Name:** DanaHibah™ Web Application  
**Client / Company:** DanaHibah™ (Solutions by Arisio Sdn Bhd)  
**Document Version:** 1.0  
**Prepared Date:** 17 May 2026  
**Status:** Draft — Pending Review  

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Company Background](#2-company-background)
3. [Project Objectives](#3-project-objectives)
4. [Project Scope](#4-project-scope)
5. [Technical Stack](#5-technical-stack)
6. [System Architecture](#6-system-architecture)
7. [Module Breakdown](#7-module-breakdown)
8. [Database Design Standards](#8-database-design-standards)
9. [Security Requirements](#9-security-requirements)
10. [UI/UX Guidelines](#10-uiux-guidelines)
11. [Development Phases & Milestones](#11-development-phases--milestones)
12. [Coding Standards](#12-coding-standards)
13. [Testing Strategy](#13-testing-strategy)
14. [Deployment Instructions](#14-deployment-instructions)
15. [Advanced Features (Phase 2+)](#15-advanced-features-phase-2)
16. [Assumptions & Constraints](#16-assumptions--constraints)

---

## 1. Executive Summary

DanaHibah™ is a Malaysian Islamic institution-focused digital platform that modernises donation collection for **mosques (masjid) and surau** across Malaysia. The platform combines smart IoT donation hardware with a cloud-based web application to deliver transparent, accountable, and auditable donation governance.

This document describes the full project plan for the **DanaHibah™ Web Application** — a PHP-based enterprise-grade admin and management portal that interfaces with the DanaHibah™ Cloud™, enabling real-time monitoring, reporting, audit trails, user management, and multi-branch governance for mosque committees and administrators.

---

## 2. Company Background

| Field | Details |
|---|---|
| **Brand Name** | DanaHibah™ |
| **Solutions Provider** | Arisio Sdn Bhd |
| **Tagline** | Secure. Transparent. Amanah. |
| **Website** | www.danahibah.com |
| **Contact** | hello@danahibah.com · +60 12-345 6789 |
| **Focus Market** | Masjid, Surau, Wakaf Institutions, Islamic NGOs, State Religious Councils |

### Vision
> To support the digital transformation of mosque and surau donation management across Malaysia through trusted and accountable technology systems.

### Mission
> To strengthen **amanah**, governance, and transparency in mosque and surau donation collection through practical digital infrastructure designed for Malaysian Islamic institutions.

### Core Business Areas
- **Smart Donation Hardware** — Cash acceptors, QR code payment terminals with real-time IoT connectivity (4G/5G), counterfeit detection, tamper alerts, digital receipts, and CCTV integration.
- **DanaHibah Cloud™** — Centralised cloud platform for monitoring, analytics, reporting, and governance.
- **Governance & Reporting** — Structured workflows for collection, verification, approval, banking reconciliation, and digital audit trails.

---

## 3. Project Objectives

1. Build a **production-ready, enterprise-grade PHP web application** for DanaHibah™ management.
2. Enable mosque/surau committees to **monitor donations in real-time** from any device.
3. Provide **role-based access control** for donors, committee members, and administrators.
4. Generate **automated daily, weekly, and monthly reports** with export capabilities.
5. Maintain a **complete audit trail** for all transactions, approvals, and system actions.
6. Support **multi-branch management** across multiple mosque/surau locations.
7. Ensure **enterprise-grade security** (CSRF, XSS, SQL injection prevention, session management).
8. Deliver a **fully responsive UI** using Bootstrap 5 that works on Desktop, Tablet, and Mobile.
9. Integrate **DataTables, AJAX, and JSON APIs** for a seamless user experience.
10. Sync with **GitHub** for version control and collaborative development.

---

## 4. Project Scope

### In Scope
- PHP 8+ web application with MySQL database (MySQLi Procedural)
- Admin portal with left sidebar layout and top navbar
- Authentication system (login, sessions, CSRF, password hashing)
- User & Role management module
- Dashboard with live collection stats and charts
- Collection management module
- Branch/location management module
- Report generation with Excel/CSV/PDF export
- Audit trail and activity logging
- Configuration and settings module
- Help/documentation system
- SEO configuration
- .htaccess and environment config for deployment
- GitHub sync

### Out of Scope (Phase 1)
- Mobile app (Android/iOS)
- Hardware firmware development
- SMS gateway integration *(Phase 2)*
- Multi-language support *(Phase 2)*
- PWA features *(Phase 2)*
- Two-factor authentication *(Phase 2)*

---

## 5. Technical Stack

| Layer | Technology |
|---|---|
| **Backend** | PHP 8+ |
| **Database** | MySQL (MySQLi Procedural — NO PDO, NO OOP MySQLi) |
| **Frontend** | Bootstrap 5 (latest), jQuery, AJAX |
| **Icons** | Bootstrap Icons |
| **Tables** | DataTables plugin (server-side processing) |
| **Charts** | Chart.js or ApexCharts |
| **Typography** | Google Fonts (Inter / Roboto / Outfit) |
| **Version Control** | GitHub |
| **Server** | Apache (XAMPP for dev, cPanel/VPS for production) |
| **Rewrite Rules** | .htaccess (mod_rewrite) |

---

## 6. System Architecture

```
+---------------------------------------------------------+
|                   DanaHibah™ Web App                    |
|                                                         |
|  +-------------+   +--------------+   +-------------+  |
|  |  Frontend   |   |   Backend    |   |  Database   |  |
|  | Bootstrap 5 |<--+   PHP 8+     |<--+    MySQL    |  |
|  |  jQuery     |   |  MySQLi      |   |  InnoDB     |  |
|  |  DataTables |   |  Procedural  |   |  UTF8MB4    |  |
|  |  AJAX/JSON  |   |              |   |             |  |
|  +-------------+   +--------------+   +-------------+  |
|                           |                             |
|              +------------+------------+                |
|              |    Core Framework       |                |
|              |  config.php             |                |
|              |  db_connect.php         |                |
|              |  auth.php               |                |
|              |  functions.php          |                |
|              |  header.php / footer.php|                |
|              |  sidebar.php / navbar   |                |
|              +-------------------------+                |
+---------------------------------------------------------+
```

### Directory Structure
```
danahibah/
├── assets/
│   ├── css/
│   ├── js/
│   ├── images/
│   └── fonts/
├── includes/
│   ├── config.php
│   ├── db_connect.php
│   ├── auth.php
│   ├── functions.php
│   ├── header.php
│   ├── footer.php
│   ├── sidebar.php
│   └── navbar.php
├── modules/
│   ├── dashboard/
│   ├── users/
│   ├── collections/
│   ├── branches/
│   ├── reports/
│   ├── audit/
│   ├── settings/
│   └── help/
├── ajax/
├── api/
├── uploads/
├── logs/
├── .htaccess
├── index.php
└── login.php
```

---

## 7. Module Breakdown

### 7.1 Authentication Module
- Secure login with session management
- Password hashing (bcrypt)
- Session timeout handling
- CSRF token generation and validation
- Login attempt protection (lockout after N failed attempts)
- Remember-me functionality
- Logout with session destruction

### 7.2 Dashboard Module
- Live collection summary (Today / This Week / This Month / This Year)
- Total collections by branch/location
- Collection by channel (Cash / QR Payment)
- Real-time charts (line, bar, pie/donut)
- Recent transactions table
- Quick stats widgets (Total Donors, Total Branches, Pending Approvals)
- Alert notifications panel

### 7.3 User Management Module

| Feature | Description |
|---|---|
| Add/Edit/Delete Users | Full CRUD with soft delete |
| Activate/Deactivate | Toggle user status |
| Role Management | Admin, Committee, Viewer, etc. |
| Permission Management | Module-level granular permissions |
| User Profile Page | View and edit own profile |
| Change Password | Secure password update |
| Profile Image Upload | With file type/size validation |

### 7.4 Collection Management Module
- View all donation transactions
- Filter by date, branch, channel, status
- Transaction details (amount, time, device, channel)
- Approval workflow (Collected → Verified → Approved → Banked)
- Reconciliation status
- Export to Excel / CSV / PDF

### 7.5 Branch / Location Management
- Add/Edit/Delete mosque or surau branches
- Branch details (name, address, PIC, contact)
- Device assignment per branch
- Per-branch collection statistics
- Multi-branch monitoring dashboard

### 7.6 Device Management
- Register DanaHibah™ hardware devices
- Link devices to branches
- Device status (Online / Offline / Tampered)
- Last sync timestamp
- Device audit log

### 7.7 Reports Module
- Daily / Weekly / Monthly / Custom date range reports
- Collection summary by branch
- Collection by channel report
- Audit report for authorities
- Export: Excel, CSV, PDF, Print
- Schedule report emails *(Phase 2)*

### 7.8 Audit Trail Module
- Log all add/edit/delete actions with timestamps
- Track user logins and logouts
- Track failed login attempts
- Track configuration changes
- Track approval workflow steps
- Filterable and exportable audit log table

### 7.9 Configuration / Settings Module

| Setting Group | Options |
|---|---|
| Application | App name, logo, timezone, date format |
| Email | SMTP settings, from name/email |
| Theme | Color scheme, dark mode toggle |
| Security | Session timeout, max login attempts |
| SEO | Meta title, description, keywords, OG tags |
| Pagination | Default rows per page |
| DataTables | Default sorting, column visibility |

### 7.10 Help / Documentation Module
- Auto-generated help section for every module
- Inline tooltips on form fields
- User guide module (step-by-step)
- FAQ section
- Documentation-ready layout

---

## 8. Database Design Standards

| Standard | Requirement |
|---|---|
| **Charset** | UTF8MB4 |
| **Engine** | InnoDB |
| **Indexing** | Proper indexes on foreign keys, searchable columns |
| **Foreign Keys** | Enforced where applicable |
| **Timestamps** | `created_at`, `updated_at` (DATETIME) on all tables |
| **Soft Delete** | `deleted_at` column (NULL = active) |
| **Audit Columns** | `created_by`, `updated_by` (user ID reference) |

### Core Tables (Minimum)
```
users               - System users and auth
roles               - Role definitions
permissions         - Module-level permissions
user_roles          - User <-> Role mapping
branches            - Mosque/surau locations
devices             - Hardware device registry
collections         - Donation transaction records
collection_approvals - Workflow approval steps
audit_logs          - System audit trail
login_logs          - Login history
settings            - Key-value app configuration
reports             - Saved/scheduled reports
```

---

## 9. Security Requirements

| Area | Implementation |
|---|---|
| **Authentication** | Secure session, bcrypt password hashing |
| **CSRF Protection** | Token per form, validated server-side |
| **XSS Prevention** | `htmlspecialchars()` on all output |
| **SQL Injection** | MySQLi prepared statements throughout |
| **Session Security** | Session timeout, `session_regenerate_id()` |
| **File Upload** | Whitelist MIME types, rename files, store outside webroot |
| **Input Sanitisation** | Server-side validation on all inputs |
| **Login Protection** | Rate-limiting, account lockout |
| **Role-Based Access** | Module/page-level permission checks |
| **Activity Logging** | Log all sensitive actions |

---

## 10. UI/UX Guidelines

### Layout
- **Left Sidebar** — collapsible navigation menu with module icons
- **Top Navbar** — breadcrumb, notifications bell, user profile dropdown
- **Content Area** — cards, modals, tabs, accordions as appropriate
- **Footer** — app version, copyright

### Design Principles
- Clean, professional, trust-inspiring aesthetic aligned with DanaHibah™ brand
- **Brand Colours:** Dark Green `#1B4332`, Gold/Amber `#D4A017`, White `#FFFFFF`
- Modern typography: **Inter** or **Outfit** via Google Fonts
- Responsive across Desktop (>=1200px), Tablet (768–1199px), Mobile (<768px)

### Standard Page Layout (All Inner Pages)
```
+------------------------------------------+
|  Page Title + Description                |
|  Breadcrumb: Home > Module > Page        |
+------------------------------------------+
|  [+ Add New]              [Search/Filter]|
+------------------------------------------+
|  DataTable with export buttons           |
|  Pagination + search                     |
+------------------------------------------+
```

### Standard Form Layout
- Page header with title, description, breadcrumb
- Back button (top left), Save button (top right), Cancel beside Save
- Floating labels (Bootstrap 5)
- Required field indicators `*`
- Client-side + server-side validation
- Success and error alerts (Bootstrap Alerts/Toasts)

### Bootstrap 5 Components Required
Cards, Modals, Alerts, Toasts, Accordions, Tabs, Breadcrumbs, Pagination,
Tooltips, Dropdowns, Offcanvas, Floating Labels, Input Groups, Validation Classes,
Badges, Tables, Buttons, Spinners, Loaders.

---

## 11. Development Phases & Milestones

### Phase 1 — Foundation & Core Framework
**Duration:** Week 1–2

| Task | Details |
|---|---|
| Project setup | Directory structure, GitHub repo init |
| Core includes | config.php, db_connect.php, functions.php |
| Layout framework | header, footer, sidebar, navbar templates |
| Database schema | All core tables with migrations SQL |
| Authentication | Login, logout, session, CSRF, password hash |
| Dashboard skeleton | Responsive layout with placeholder widgets |

> **Milestone:** Working login + layout shell deployed on dev server

---

### Phase 2 — User & Permission System
**Duration:** Week 3

| Task | Details |
|---|---|
| Users CRUD | Add, edit, delete, activate/deactivate |
| Roles & Permissions | Role definitions, module-level permissions |
| User Profile | Profile page, change password, photo upload |
| Activity Log | Login log, user action log |

> **Milestone:** Full user management with role-based access

---

### Phase 3 — Branch & Device Management
**Duration:** Week 4

| Task | Details |
|---|---|
| Branch CRUD | Add/edit/delete branches |
| Device registry | Register devices, link to branches |
| Device status | Online/offline monitoring |

> **Milestone:** Multi-branch structure ready

---

### Phase 4 — Collection & Approval Module
**Duration:** Week 5–6

| Task | Details |
|---|---|
| Transaction list | DataTable with server-side processing |
| Collection details | Full transaction detail view |
| Approval workflow | Collect → Verify → Approve → Bank |
| AJAX submissions | Async status updates, JSON responses |
| Filters & search | Date, branch, channel, status |

> **Milestone:** Core donation management fully operational

---

### Phase 5 — Reports & Audit Trail
**Duration:** Week 7

| Task | Details |
|---|---|
| Report builder | Date range, branch, channel filters |
| Export buttons | Excel, CSV, PDF, Print |
| Audit trail table | All system actions, filterable |
| Login log report | Failed/successful login history |

> **Milestone:** Full reporting and audit capability

---

### Phase 6 — Dashboard (Live Data)
**Duration:** Week 8

| Task | Details |
|---|---|
| Live charts | Chart.js integration (line, bar, donut) |
| Stats widgets | Real-time card widgets |
| AJAX refresh | Auto-refresh dashboard data |
| Notification center | System alerts and notifications |

> **Milestone:** Live dashboard with real-time data

---

### Phase 7 — Configuration, SEO & Help
**Duration:** Week 9

| Task | Details |
|---|---|
| Settings module | All setting groups with dynamic loading |
| SEO config | Dynamic meta tags, OG tags |
| .htaccess | SEO-friendly URLs, security headers |
| Help system | Tooltips, user guide, FAQ |

> **Milestone:** System fully configurable and documented

---

### Phase 8 — QA, Security Audit & Deployment
**Duration:** Week 10

| Task | Details |
|---|---|
| Code review | No duplicates, clean architecture |
| Security audit | CSRF, XSS, SQLi, session checks |
| Performance | Query optimisation, pagination, caching |
| UAT | User acceptance testing with client |
| GitHub push | Final clean commit, tagged release |
| Production deploy | cPanel/VPS deployment, .env setup |
| Backup script | DB backup, file backup |

> **Milestone:** Production-ready release v1.0

---

## 12. Coding Standards

| Standard | Rule |
|---|---|
| **DB Access** | MySQLi Procedural ONLY — no PDO, no OOP MySQLi |
| **Includes** | Centralised reusable include files |
| **SQL** | Always use prepared statements |
| **Output** | Always sanitise with `htmlspecialchars()` |
| **Functions** | Centralised helper functions in `functions.php` |
| **Constants** | Use constants for global config values |
| **Indentation** | 4 spaces, consistent throughout |
| **Comments** | Comment all important logic and function purposes |
| **Code Duplication** | Minimal — extract repeated logic into functions |
| **AJAX Responses** | Always return `{ status, message, data }` JSON |

---

## 13. Testing Strategy

| Type | Method |
|---|---|
| **Unit Testing** | Manual PHP function testing |
| **Form Validation** | Test required fields, invalid inputs, edge cases |
| **Security Testing** | SQL injection, XSS, CSRF bypass attempts |
| **Role Testing** | Verify each role sees only permitted modules |
| **Cross-Browser** | Chrome, Firefox, Edge, Safari |
| **Responsive Testing** | Desktop, Tablet, Mobile viewports |
| **Export Testing** | Verify Excel, CSV, PDF exports are accurate |
| **Performance** | Large dataset pagination, query explain plans |
| **UAT** | Client walkthrough and sign-off on all modules |

---

## 14. Deployment Instructions

### Development Environment
- XAMPP (Apache + MySQL) on Windows
- PHP 8+ enabled
- `.htaccess` with `mod_rewrite` enabled

### Production Environment
- cPanel or VPS (Ubuntu/CentOS)
- Apache with `mod_rewrite`
- PHP 8+ with extensions: `mysqli`, `mbstring`, `gd`, `zip`, `openssl`
- SSL certificate (HTTPS enforced via .htaccess)
- `config.php` environment-aware (dev vs production)
- DB migration SQL script run on production MySQL
- File permissions: `uploads/` → 755, `logs/` → 755
- `.htaccess` blocks direct access to `/includes`, `/logs`, `/api`

### GitHub Workflow
```
main          <- production-stable
develop       <- active development
feature/*     <- individual feature branches
```

---

## 15. Advanced Features (Phase 2+)

| Feature | Priority |
|---|---|
| Multi-language support (BM / EN) | High |
| Two-factor authentication (2FA) | High |
| SMS integration (donation alerts) | Medium |
| Email templates & scheduled reports | Medium |
| PWA (Progressive Web App) | Medium |
| Dark mode toggle | Medium |
| QR code donation slip generation | Medium |
| AI anomaly detection alerts | Medium |
| Drag-and-drop file manager | Low |
| API authentication (Bearer token) | Low |
| Scheduler / Cron job support | Low |
| Backup manager UI | Low |
| Excel import/export for bulk data | Low |

---

## 16. Assumptions & Constraints

### Assumptions
- DanaHibah™ hardware devices push data to the cloud/database via API or IoT gateway (outside Phase 1 scope).
- Client will provide branding assets (logos, colour codes) before Phase 1 begins.
- MySQL database will be hosted on the same server as the PHP application.
- GitHub repository access will be shared between the development team.

### Constraints
- **No PDO** — all database access must use MySQLi Procedural.
- **No ORM** — no Laravel, no Eloquent, no Doctrine.
- **Bootstrap 5 only** — no custom CSS frameworks or TailwindCSS.
- Deployment must support shared hosting (cPanel) as a minimum.

---

## Sign-Off

| Role | Name | Date | Signature |
|---|---|---|---|
| Project Manager | | | |
| Lead Developer | | | |
| Client Representative | | | |
| QA Lead | | | |

---

*Document prepared based on:*  
- *DanaHibah_Company_Summary.docx*  
- *PHP_Web_Application_AI_Prompt_Guide.docx*  
- *Visual references: site1.jpeg, site2.jpeg, site3.jpeg*

*DanaHibah™ — Secure. Transparent. Amanah.*
