# Admin Login & Signup — Test Report

> **Date:** March 2026
> **Project:** guesty-core (Laravel 12)
> **Tester:** Developer
> **Environment:** Local (http://localhost:8000)

---

## Test Summary

| Category | Total | Passed | Failed | Notes |
|----------|-------|--------|--------|-------|
| Registration | 5 | 5 | 0 | First admin flow + blocking |
| Login | 6 | 6 | 0 | Auth + redirects |
| Logout | 3 | 3 | 0 | Session + redirect |
| Admin User Management | 7 | 7 | 0 | CRUD operations |
| Security | 5 | 5 | 0 | Route protection |
| **Total** | **26** | **26** | **0** | |

---

## 1. Registration Tests

### TEST-REG-001: First Admin Registration (No Users Exist)
- **URL:** `GET /register`
- **Precondition:** Zero users in database
- **Steps:** Visit `/register` → Fill name, email, password, confirm password → Submit
- **Expected:** Account created, auto-login, redirect to `/client-login/guesty_properties`
- **Result:** ✅ PASSED
- **Notes:** Welcome message shown after first registration

### TEST-REG-002: Registration Blocked (Users Exist)
- **URL:** `GET /register`
- **Precondition:** At least 1 user exists in database
- **Steps:** Visit `/register`
- **Expected:** 302 redirect to login page, info flash message shown
- **Result:** ✅ PASSED
- **Notes:** Message: "Registration is disabled. Please contact an administrator."

### TEST-REG-003: Registration POST Blocked (Users Exist)
- **URL:** `POST /register`
- **Precondition:** At least 1 user exists
- **Steps:** Submit POST request to `/register` with valid data
- **Expected:** 302 redirect to login page, no account created
- **Result:** ✅ PASSED
- **Notes:** Server-side enforcement — cannot bypass by submitting form directly

### TEST-REG-004: Registration Validation
- **URL:** `POST /register`
- **Precondition:** Zero users in database
- **Steps:** Submit with empty fields / invalid email / short password / mismatched passwords
- **Expected:** Validation errors shown on form
- **Result:** ✅ PASSED
- **Notes:** All Laravel validation rules work correctly

### TEST-REG-005: Duplicate Email Prevention
- **URL:** `POST /register`
- **Precondition:** Zero users, then try same email twice
- **Steps:** Register once → Delete all users → Register again with same email
- **Expected:** Unique email validation works
- **Result:** ✅ PASSED

---

## 2. Login Tests

### TEST-LOGIN-001: Valid Login
- **URL:** `POST /client-login`
- **Steps:** Enter valid email + password → Submit
- **Expected:** Redirect to `/client-login/guesty_properties`
- **Result:** ✅ PASSED

### TEST-LOGIN-002: Invalid Login
- **URL:** `POST /client-login`
- **Steps:** Enter wrong email or password → Submit
- **Expected:** Error message, stay on login page
- **Result:** ✅ PASSED
- **Notes:** Error: "These credentials do not match our records."

### TEST-LOGIN-003: Login Page Renders Correctly
- **URL:** `GET /client-login/login`
- **Steps:** Visit login page
- **Expected:** AdminLTE login form displayed with email/password fields
- **Result:** ✅ PASSED

### TEST-LOGIN-004: Login Form Fields Empty (No Autofill)
- **URL:** `GET /client-login/login`
- **Steps:** Visit login page after previous login
- **Expected:** Email and password fields are empty
- **Result:** ✅ PASSED
- **Notes:** Fixed with `autocomplete="chrome-off"`, `readonly` trick, and JS clear script

### TEST-LOGIN-005: Default /login Route Returns 404
- **URL:** `GET /login`
- **Steps:** Visit `/login`
- **Expected:** 404 Not Found (legacy behavior match)
- **Result:** ✅ PASSED

### TEST-LOGIN-006: Flash Messages Display on Login Page
- **URL:** `GET /client-login/login` (after redirect from /register)
- **Steps:** Try `/register` when users exist → Check login page
- **Expected:** Info alert message visible
- **Result:** ✅ PASSED

---

## 3. Logout Tests

### TEST-LOGOUT-001: Logout Redirects to Login Page
- **URL:** `POST /logout`
- **Steps:** Click logout from admin panel
- **Expected:** Redirect to `/client-login/login` (not home page)
- **Result:** ✅ PASSED

### TEST-LOGOUT-002: Session Invalidated After Logout
- **URL:** `POST /logout`
- **Steps:** Logout → Try accessing `/client-login/users` directly
- **Expected:** Redirect to login page (session cleared)
- **Result:** ✅ PASSED

### TEST-LOGOUT-003: CSRF Token Regenerated
- **URL:** `POST /logout`
- **Steps:** Logout → Check session token
- **Expected:** New CSRF token generated
- **Result:** ✅ PASSED

---

## 4. Admin User Management Tests

### TEST-USER-001: View Users List
- **URL:** `GET /client-login/users`
- **Steps:** Login → Navigate to Admin Users
- **Expected:** Table with all admin users displayed
- **Result:** ✅ PASSED
- **Notes:** DataTable with name, email, and action columns

### TEST-USER-002: Admin Users Menu Visible in Sidebar
- **Steps:** Login → Check sidebar
- **Expected:** "Admin Users" menu item visible with users icon
- **Result:** ✅ PASSED

### TEST-USER-003: Create New Admin
- **URL:** `GET /client-login/users/create` → `POST /client-login/users`
- **Steps:** Click "Add User" → Fill name, email, password → Save
- **Expected:** New admin created, redirect to users list, success message
- **Result:** ✅ PASSED
- **Notes:** New admin can immediately log in

### TEST-USER-004: Create Form Fields Empty (No Autofill)
- **URL:** `GET /client-login/users/create`
- **Steps:** Visit create page while logged in
- **Expected:** Email and password fields are empty (not filled with current admin's credentials)
- **Result:** ✅ PASSED
- **Notes:** Fixed with `autocomplete="off"` on email and `autocomplete="new-password"` on password

### TEST-USER-005: Edit Existing Admin
- **URL:** `GET /client-login/users/{id}/edit` → `PUT /client-login/users/{id}`
- **Steps:** Click "Edit" on a user → Change email → Update
- **Expected:** Admin updated, redirect to users list, success message
- **Result:** ✅ PASSED
- **Notes:** Password field optional on edit (leave blank to keep current)

### TEST-USER-006: Delete Admin
- **URL:** `DELETE /client-login/users/{id}`
- **Steps:** Click "Delete" on a user → Confirm
- **Expected:** Admin deleted, redirect to users list, success message
- **Result:** ✅ PASSED
- **Notes:** Confirmation dialog prevents accidental deletion

### TEST-USER-007: Validation on Create
- **URL:** `POST /client-login/users`
- **Steps:** Submit with empty fields / duplicate email
- **Expected:** Validation errors shown
- **Result:** ✅ PASSED

---

## 5. Security Tests

### TEST-SEC-001: Admin Routes Protected by Auth Middleware
- **URL:** `GET /client-login/users` (not logged in)
- **Steps:** Try accessing admin pages without login
- **Expected:** 302 redirect to login page
- **Result:** ✅ PASSED

### TEST-SEC-002: /admin URL Redirects to /client-login
- **URL:** `GET /admin`
- **Steps:** Visit `/admin`
- **Expected:** 302 redirect to `/client-login`
- **Result:** ✅ PASSED

### TEST-SEC-003: Dashboard Logo Click Goes to /client-login
- **Steps:** Login → Click admin logo/icon in top-left
- **Expected:** Navigate to `/client-login` (dashboard)
- **Result:** ✅ PASSED
- **Notes:** Previously went to `/admin` (404)

### TEST-SEC-004: Registration Cannot Be Bypassed via POST
- **Steps:** Use curl/Postman to POST to `/register` when users exist
- **Expected:** Redirect to login, no account created
- **Result:** ✅ PASSED

### TEST-SEC-005: Passwords Hashed in Database
- **Steps:** Create admin → Check database
- **Expected:** Password stored as bcrypt hash, not plain text
- **Result:** ✅ PASSED

---

## Known Issues (None)

No known issues remaining for admin authentication and user management features.

---

## Test Environment Details

| Detail | Value |
|--------|-------|
| Laravel Version | 12.53.0 |
| PHP Version | 8.3.30 |
| Database | MySQL (phluxurystaysdb) |
| AdminLTE Version | 3.15.3 |
| Auth Package | laravel/ui v4.6.1 |
| Browser | Chrome/Firefox (latest) |
