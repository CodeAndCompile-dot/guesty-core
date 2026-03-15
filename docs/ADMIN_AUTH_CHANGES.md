# Admin Authentication & User Management — Change Log

> **Date:** March 2026
> **Project:** guesty-core (Laravel 12 rebuild)
> **Legacy:** projects/ (Laravel 9)

---

## 1. Registration Restricted to First Admin Only

### What Changed
- **File:** `app/Http/Controllers/Auth/RegisterController.php`
- **Before:** Anyone could access `/register` and create an admin account (open registration)
- **After:** Registration is only allowed when **zero users exist** in the database

### Why
- The legacy project had open registration with only a JavaScript redirect to "hide" the page — this was insecure because anyone could POST directly to `/register` and create an admin account
- In production, only trusted admins should be able to create new admin accounts

### How It Works
1. `showRegistrationForm()` — checks `User::count() > 0`. If users exist, redirects to login with an info message
2. `register()` — same check on POST. Blocks form submission if users exist
3. First admin registers normally, gets auto-logged in with a welcome message

### Impact
- First deployment: visit `/register` to create the initial admin
- After that: `/register` is permanently blocked for public access
- New admins are created through the admin dashboard (see section 3)

---

## 2. Login Page Changes

### What Changed
- **File:** `resources/views/vendor/adminlte/auth/login.blade.php`
- **File:** `app/Http/Controllers/Auth/LoginController.php`

### Changes Made

| Change | File | Why |
|--------|------|-----|
| Added flash message display (success/error/info alerts) | `login.blade.php` | Messages from registration redirect were not visible on login page |
| Added `autocomplete="chrome-off"` on email field | `login.blade.php` | Prevent browser from auto-filling saved credentials |
| Added `autocomplete="new-password"` on password field | `login.blade.php` | Prevent browser from auto-filling saved password |
| Added `readonly` + `onfocus` trick on both fields | `login.blade.php` | Extra anti-autofill layer; fields become editable on click |
| Added JavaScript to clear fields on page load | `login.blade.php` | Final fallback to ensure fields are empty |
| Custom `logout()` method redirects to login page | `LoginController.php` | Previously redirected to site home page (`/`) |

---

## 3. Admin User Management via Dashboard

### What Changed
- **File:** `config/adminlte.php` — Uncommented "Admin Users" sidebar menu item
- **Route:** `/client-login/users` (already existed, was hidden from sidebar)

### How Admins Add New Admins
1. Login at `/client-login/login`
2. Click **"Admin Users"** in the sidebar (icon: users)
3. Click **"Add User"** button
4. Fill in: Name, Email, Password
5. Click **Save** → new admin is created and can log in immediately

### Features Available
| Feature | Route | Method |
|---------|-------|--------|
| List all admins | `GET /client-login/users` | `UserController@index` |
| Add new admin | `GET /client-login/users/create` | `UserController@create` |
| Save new admin | `POST /client-login/users` | `UserController@store` |
| Edit admin | `GET /client-login/users/{id}/edit` | `UserController@edit` |
| Update admin | `PUT /client-login/users/{id}` | `UserController@update` |
| Delete admin | `DELETE /client-login/users/{id}` | `UserController@destroy` |

### Anti-Autofill on Admin Creation Form
- **File:** `resources/views/admin/users/form.blade.php`
- Email field: `autocomplete="off"` — prevents browser from filling current admin's email
- Password field: `autocomplete="new-password"` — tells browser this is for a NEW account

---

## 4. Dashboard URL Fix

### What Changed
- **File:** `config/adminlte.php`
- **Before:** `'dashboard_url' => 'admin'` — clicking the logo/icon went to `/admin` (404 error)
- **After:** `'dashboard_url' => 'client-login'` — clicking the logo goes to `/client-login` (dashboard)

### Why
- The admin panel lives under `/client-login/` prefix, not `/admin`
- Legacy project had the same config but it worked because the root `index.php` handled routing differently
- Added redirect: `/admin` → `/client-login` for backward compatibility

### Route Added
- **File:** `routes/web/auth.php`
- `Route::get('admin', fn() => redirect('/client-login'))` — catches old `/admin` URLs

---

## 5. Register Page Flash Messages

### What Changed
- **File:** `resources/views/auth/register.blade.php`
- Added flash message display (success/error/info alerts) at top of form

### Why
- When registration is blocked and user is redirected to login, they need to see the "Registration is disabled" message
- Same message component added to register view for consistency

---

## 6. Legacy Project Comparison

| Feature | Legacy (Laravel 9) | New (Laravel 12) |
|---------|-------------------|-------------------|
| Registration | Open, hidden by JS redirect | Blocked after first admin (server-side) |
| Registration security | Frontend-only (insecure) | Backend enforcement (secure) |
| Adding new admins | Not possible via UI (menu commented out) | Admin dashboard → "Admin Users" |
| Login redirect after logout | Home page | Login page |
| Login form autofill | Default browser behavior | Blocked with multiple techniques |
| Dashboard logo click | `/admin` (happened to work) | `/client-login` (correct URL) |
| `/admin` URL | No explicit route | Redirects to `/client-login` |

---

## Files Modified Summary

| File | Type | Changes |
|------|------|---------|
| `app/Http/Controllers/Auth/RegisterController.php` | Controller | Registration restriction logic |
| `app/Http/Controllers/Auth/LoginController.php` | Controller | Custom logout redirect |
| `resources/views/vendor/adminlte/auth/login.blade.php` | View | Flash messages, anti-autofill |
| `resources/views/auth/register.blade.php` | View | Flash messages |
| `resources/views/admin/users/form.blade.php` | View | Anti-autofill on create form |
| `config/adminlte.php` | Config | Dashboard URL fix, Users menu enabled |
| `routes/web/auth.php` | Routes | `/admin` redirect route |
