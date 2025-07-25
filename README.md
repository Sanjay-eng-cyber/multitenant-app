<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Laravel Multi-Tenant SaaS Task

### Features
- User authentication (Laravel Breeze API)
- Users can manage multiple companies
- Can switch between companies
- Data scoped to the active company

## 📡 API Endpoints

### Authentication

- `POST /register` → Register a new user
- `POST /login` → Log in and receive token
- `POST /logout` → Log out the authenticated user

---

### Company Endpoints

- `POST /companies` → Get a list of companies for the logged-in user
- `POST /company/store` → Create a new company
- `PUT /company/update/{id}` → Update an existing company
- `POST /company/delete/{id}` → Soft-delete a company
- `POST /company/{id}/switch` → Set the active company for the user

---

### Project Endpoints

- `POST /projects` → Get a list of all projects under the active company
- `POST /project/store` → Create a new project
- `Put /project/update/{id}` → Update a project by ID
- `POST /project/delete/{id}` → Delete a project by ID

---

### Invoice Endpoints

- `POST /invoices` → Get a list of all invoices under the active company
- `POST /invoice/store` → Create a new invoice (must belong to active company project)
- `Put /invoice/update/{id}` → Update an invoice by ID
- `POST /invoice/delete/{id}` → Delete an invoice by ID

---

### Notes

- All project and invoice actions are scoped to the currently active company.
- You must call `/company/{id}/switch` before interacting with scoped resources.
- All dates must use the `YYYY-MM-DD` format (e.g., `2025-07-25`).
- Requests must include these headers:

```http
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json

-----

## Multi-Tenant Logic & Data Scoping

This project follows a **multi-tenant SaaS architecture**, where each user can manage **multiple companies**, but all operational data (projects, invoices, etc.) is **strictly scoped to one "active" company** at a time.

---

### Core Logic

- Each authenticated user can **create and manage multiple companies**.
- The user can **switch between companies** using the `/company/{id}/switch` endpoint.
- The ID of the active company is stored in the `active_company_id` column in the `users` table.
- When the user performs operations like creating a project or invoice, the system **automatically attaches the active company ID** to that data.

---

### Data Scoping Rules

- **Projects and Invoices are always linked to the active company**.
- The API checks that:
  - `project_id` used in an invoice **belongs to the active company**.
  - You **cannot access or modify** projects or invoices from other companies.
- Each API controller checks ownership by verifying `company_id` before performing any action.

---

### Example

1. User logs in and switches to `Company A`.
2. When calling `POST /project/store`, the new project is automatically saved with `company_id = Company A`.
3. The user switches to `Company B` using `/company/2/switch`.
4. Now any list/create/update/delete actions affect **only** `Company B`.

This ensures strict **data isolation per company** and maintains the **integrity of tenant-specific data**.

---
