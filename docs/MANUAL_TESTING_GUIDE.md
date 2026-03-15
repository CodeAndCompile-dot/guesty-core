# Manual Testing Guide — Guesty-Core (Pre-Production QA)

> **Project:** Guesty-Core (Laravel 12 rebuild of `projects/` legacy system)
> **Purpose:** Verify the rebuilt system behaves exactly like the legacy system before production deployment.
> **Audience:** QA engineers, developers, and non-technical stakeholders.
> **Date:** June 2025

---

## Table of Contents

1. [Pre-Deployment Checklist](#1-pre-deployment-checklist)
2. [Frontend Functionality Tests (Public Website)](#2-frontend-functionality-tests-public-website)
3. [Admin Panel Tests](#3-admin-panel-tests)
4. [Frontend Library Compatibility Tests](#4-frontend-library-compatibility-tests)
5. [iCal Calendar Sync Tests](#5-ical-calendar-sync-tests)
6. [Cron Job & Scheduled Task Verification](#6-cron-job--scheduled-task-verification)
7. [Guesty PMS Compatibility Tests](#7-guesty-pms-compatibility-tests)
8. [Email System Tests](#8-email-system-tests)
9. [Payment Testing (Stripe & PayPal)](#9-payment-testing-stripe--paypal)
10. [Error Handling & Edge-Case Tests](#10-error-handling--edge-case-tests)
11. [Final Production Readiness Checklist](#11-final-production-readiness-checklist)

---

## How to Use This Guide

- Work through each section **in order**.
- Each test has a **Steps** list and an **Expected Result**.
- Mark each test: **✅ PASS**, **❌ FAIL**, or **⚠️ SKIP** (with reason).
- If a test fails, record the actual behavior and screenshot it.
- The base URL is referred to as `{BASE_URL}` — replace it with your staging/production domain (e.g., `https://phluxurystays.com`).

---

## 1. Pre-Deployment Checklist

Complete every item below before running any functional tests.

### 1.1 Environment Variables (`.env`)

Open the `.env` file on the server and confirm **all** of the following keys are set.
If any key is missing or blank, the related feature **will not work**.

#### Core Laravel

| Variable | Example Value | How to Verify |
|---|---|---|
| `APP_NAME` | `PhluxuryStays` | Must match the site name |
| `APP_ENV` | `production` | Must be `production` (not `local`) |
| `APP_KEY` | `base64:...` | Run `php artisan key:generate --show` if empty |
| `APP_DEBUG` | `false` | **Must be `false` on production** |
| `APP_URL` | `https://phluxurystays.com` | Must be the live domain with HTTPS |
| `DB_HOST` / `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` | — | Verify database connection works |
| `MAIL_MAILER` | `smtp` | Must match your email provider |
| `MAIL_HOST` / `MAIL_PORT` / `MAIL_USERNAME` / `MAIL_PASSWORD` | — | Required for all email features |
| `MAIL_FROM_ADDRESS` / `MAIL_FROM_NAME` | — | Sender identity on outgoing emails |

#### Guesty PMS API

| Variable | Description | Required? |
|---|---|---|
| `GUESTY_CLIENT_ID` | Guesty Open API OAuth client ID | **Yes** |
| `GUESTY_CLIENT_SECRET` | Guesty Open API OAuth client secret | **Yes** |
| `GUESTY_BOOKING_CLIENT_ID` | Guesty Booking Engine API client ID | **Yes** |
| `GUESTY_BOOKING_CLIENT_SECRET` | Guesty Booking Engine API client secret | **Yes** |
| `GUESTY_OPEN_API_URL` | Base URL for Guesty Open API (default: `https://open-api.guesty.com`) | **Yes** |
| `GUESTY_BOOKING_API_URL` | Base URL for Guesty Booking API (default: `https://booking.guesty.com`) | **Yes** |
| `GUESTY_PAY_URL` | Base URL for Guesty Pay (default: `https://pay.guesty.com`) | **Yes** |
| `GUESTY_TOKEN_TTL` | Token cache TTL in seconds (default: `86400`) | Optional |
| `GUESTY_ACCOUNT_ID` | Guesty account ID | **Yes** |

#### Payment Gateway

| Variable | Description | Required? |
|---|---|---|
| `STRIPE_SECRET_KEY` | Stripe secret key (`sk_live_...`) | Yes, if using Stripe |
| `STRIPE_PUBLISHABLE_KEY` | Stripe publishable key (`pk_live_...`) | Yes, if using Stripe |
| `STRIPE_WEBHOOK_SECRET` | Stripe webhook signing secret | Optional |
| `PAYPAL_CLIENT_ID` | PayPal REST API client ID | Yes, if using PayPal |
| `PAYPAL_CLIENT_SECRET` | PayPal REST API client secret | Yes, if using PayPal |
| `PAYPAL_MODE` | `sandbox` or `live` | **Must be `live` on production** |
| `PAYMENT_GATEWAY` | `stripe` or `paypal` | Default payment method selection |
| `PAYMENT_CURRENCY` | `USD` | Currency code |

**Test:**
1. SSH into the server.
2. Run: `cd /path/to/guesty-core && cat .env | grep -E "GUESTY_|STRIPE_|PAYPAL_|PAYMENT_|APP_ENV|APP_DEBUG|APP_URL|DB_|MAIL_"`
3. **Expected:** Every variable listed above should have a non-empty value. `APP_DEBUG=false`, `APP_ENV=production`.

---

### 1.2 Storage & Directory Permissions

| Check | Command | Expected Result |
|---|---|---|
| Storage writable | `ls -la storage/` | `storage/app`, `storage/framework`, `storage/logs` owned by web server user (e.g., `www-data`) |
| Logs writable | `touch storage/logs/test.log && rm storage/logs/test.log` | No permission errors |
| Cache writable | `php artisan cache:clear` | "Application cache cleared successfully" |
| Views compilable | `php artisan view:clear && php artisan view:cache` | No errors |
| Config cacheable | `php artisan config:cache` | No errors |
| Upload dirs exist | `ls -la public/uploads/` | Directories: `properties/`, `blogs/`, `galleries/`, `attractions/`, `cms/`, `ical/`, `signature/`, `testimonials/` etc. |
| Upload dirs writable | `touch public/uploads/properties/test.txt && rm public/uploads/properties/test.txt` | No permission errors |
| Storage symlink | `php artisan storage:link` | Creates `public/storage → storage/app/public` |

---

### 1.3 Frontend Asset Accessibility

The public website uses static assets from `front/`, `toastr/`, `ckeditor/`, and `drag-drop-image-uploader/` directories. These assets **must be accessible** from the web root.

| Check | URL to Test | Expected Result |
|---|---|---|
| Front CSS | `{BASE_URL}/front/css/home.css` | CSS file loads (HTTP 200) |
| Front JS | `{BASE_URL}/front/js/` | JS directory accessible |
| Front images | `{BASE_URL}/front/images/` | Images directory accessible |
| Bootstrap | `{BASE_URL}/front/assets/bootstrap-5.3.0/css/bootstrap.min.css` | CSS file loads |
| jQuery | `{BASE_URL}/front/assets/jquery/jquery-3.6.0.min.js` | JS file loads |
| Font Awesome | `{BASE_URL}/front/assets/fontawesome-free-6.4.0-web/css/all.min.css` | CSS file loads |
| jQuery UI | `{BASE_URL}/front/assets/jquery-ui/jquery-ui.min.js` | JS file loads |
| Toastr CSS | `{BASE_URL}/toastr/toastr.css` | Toastr CSS loads |
| Toastr JS | `{BASE_URL}/toastr/toastr.js` | Toastr JS loads |
| CKEditor | `{BASE_URL}/drag-drop-image-uploader/ckeditor/ckeditor.js` | CKEditor JS loads |
| Image Uploader CSS | `{BASE_URL}/drag-drop-image-uploader/src/image-uploader.css` | CSS file loads |
| Image Uploader JS | `{BASE_URL}/drag-drop-image-uploader/src/image-uploader.js` | JS file loads |
| AdminLTE CSS/JS | Check via browser DevTools on admin page | No 404 errors for CSS/JS |

> **Note:** If any asset returns HTTP 404, the web server's document root or symlink configuration must be adjusted. These directories exist at the project root level (alongside `guesty-core/`), not inside `guesty-core/public/`. The web server (Apache/Nginx) must be configured to serve them.

---

### 1.4 Database

| Check | Command | Expected |
|---|---|---|
| Connection works | `php artisan migrate:status` | Lists all migrations with "Ran" status |
| No pending migrations | `php artisan migrate:status \| grep Pending` | No output (all migrations ran) |
| Seed data exists | Check `basic_settings` table has rows | At least 1 row with site settings |
| Admin user exists | Check `users` table | At least 1 admin user entry |

---

### 1.5 Composer & PHP

| Check | Command | Expected |
|---|---|---|
| PHP version | `php -v` | PHP 8.2+ (project uses 8.3) |
| Required extensions | `php -m \| grep -E "mbstring\|openssl\|pdo\|tokenizer\|xml\|curl\|gd\|zip"` | All extensions present |
| Composer packages | `composer install --no-dev --optimize-autoloader` | No errors |
| Laravel boots | `php artisan about` | Shows Laravel 12.x version, environment info |

---

## 2. Frontend Functionality Tests (Public Website)

### 2.1 Homepage

| # | Steps | Expected Result |
|---|---|---|
| 2.1.1 | Navigate to `{BASE_URL}/` | Homepage loads with header, banner/slider, property listings, footer |
| 2.1.2 | Check the page title in the browser tab | Matches the site name from settings |
| 2.1.3 | Verify all images load (check DevTools → Network tab for 404s) | No broken images |
| 2.1.4 | Verify the slider/carousel auto-rotates | Slides change automatically |
| 2.1.5 | Scroll to the footer | Footer shows contact info, social links, navigation links |
| 2.1.6 | Check responsive design: resize browser to mobile width (375px) | Layout adjusts, no horizontal scroll, menu collapses to hamburger |

---

### 2.2 Property Listings & Detail

| # | Steps | Expected Result |
|---|---|---|
| 2.2.1 | Click on **Properties** or navigate to the properties listing page | Property cards display with images, titles, locations, pricing |
| 2.2.2 | Click on a property card | Redirects to `{BASE_URL}/properties/detail/{seo_url}` |
| 2.2.3 | On the property detail page, verify: title, description, images gallery, amenities, location, calendar, pricing | All sections render correctly with data |
| 2.2.4 | Click through the image gallery (lightbox/fancybox) | Images open in a modal/lightbox overlay |
| 2.2.5 | Navigate to `{BASE_URL}/properties/location/{location-slug}` | Shows properties filtered by that location |

---

### 2.3 Booking Flow (Quote → Rental Agreement → Payment)

This is the **most critical** user journey. Test end-to-end.

| # | Steps | Expected Result |
|---|---|---|
| 2.3.1 | On a property detail page, select check-in and check-out dates and fill in guest count | Date picker works; guest count fields accept numbers |
| 2.3.2 | Click **Get Quote** / **Book Now** | AJAX call to `checkajax-get-quote` returns pricing breakdown (nightly rate, cleaning fee, taxes, total). No JS errors in console |
| 2.3.3 | Fill in guest details (first name, last name, email, phone) and submit | Form POSTs to `save-booking-data`. Redirects to booking preview |
| 2.3.4 | On the booking preview page (`booking/preview/{id}`) | Shows booking summary: property name, dates, guest info, cost breakdown |
| 2.3.5 | Click continue to rental agreement (`booking/rental-aggrement/{id}`) | Rental agreement form loads with signature pad |
| 2.3.6 | Sign the rental agreement, upload any required documents, submit | Form POSTs to `rental-aggrement-data-save`. Redirects to payment page |
| 2.3.7 | Payment page loads (Stripe or PayPal depending on gateway config) | Payment form renders without JS errors (see Section 9 for payment tests) |

---

### 2.4 Blog Pages

| # | Steps | Expected Result |
|---|---|---|
| 2.4.1 | Navigate to the blog listing page (CMS slug) | Blog posts display in a list/grid with thumbnails, titles, excerpts |
| 2.4.2 | Click on a blog post | Redirects to `{BASE_URL}/blog/{seo_url}` |
| 2.4.3 | On the blog detail page | Full blog content renders with images, correct formatting |
| 2.4.4 | Navigate to `{BASE_URL}/blogs/category/{category-slug}` | Shows blogs filtered by that category |

---

### 2.5 Attractions Pages

| # | Steps | Expected Result |
|---|---|---|
| 2.5.1 | Navigate to the attractions listing page | Attraction cards load with images and titles |
| 2.5.2 | Click on an attraction | Redirects to `{BASE_URL}/attractions/detail/{seo_url}` |
| 2.5.3 | Navigate to `{BASE_URL}/attractions/location/{location-slug}` | Attractions filtered by location |
| 2.5.4 | Navigate to `{BASE_URL}/attractions/category/{category-slug}` | Attractions filtered by category |

---

### 2.6 Contact & Forms

| # | Steps | Expected Result |
|---|---|---|
| 2.6.1 | Navigate to the Contact page | Contact form loads with name, email, phone, message fields |
| 2.6.2 | Submit the form **empty** | Validation errors appear for required fields |
| 2.6.3 | Fill in all fields with valid data and submit | Form POSTs to `contact-post`. Success message appears ("Thank you" or similar). Entry appears in admin under Contact Us Enquiries |
| 2.6.4 | If reCAPTCHA is enabled (check admin settings), verify the reCAPTCHA widget appears | Google reCAPTCHA v2 checkbox renders on the form |
| 2.6.5 | Submit the **Newsletter** form (usually in footer) | POSTs to `newsletter-post`. Success message. Entry appears in admin Newsletters |
| 2.6.6 | Submit the **Property Management** form | POSTs to `property-management-post`. Success message |
| 2.6.7 | Submit the **Onboarding** form | POSTs to `onboarding-post`. Success message |
| 2.6.8 | Submit the **Review** form | POSTs to `review-submit`. Success message |

---

### 2.7 CMS/Static Pages

| # | Steps | Expected Result |
|---|---|---|
| 2.7.1 | Navigate to About, FAQ, Gallery, Services, Meet the Team, or any other CMS-managed page | Page renders with CMS content, correct template |
| 2.7.2 | Gallery page: images display in grid, click opens lightbox | Gallery and lightbox both work |
| 2.7.3 | FAQ page: custom FAQ accordion or sections display | FAQ content renders, expand/collapse works (if applicable) |
| 2.7.4 | Meet the Team: navigate to `{BASE_URL}/meet-the-team/{member-slug}` | Individual team member page loads |
| 2.7.5 | Vacation page: navigate to `{BASE_URL}/vacation/{slug}` | Vacation detail page renders |

---

### 2.8 SEO & Utility Routes

| # | Steps | Expected Result |
|---|---|---|
| 2.8.1 | Navigate to `{BASE_URL}/sitemap.xml` | Valid XML sitemap renders with URLs |
| 2.8.2 | Navigate to `{BASE_URL}/robots.txt` | Valid robots.txt with correct directives |
| 2.8.3 | Check HTML `<meta>` tags on any page (View Source) | `meta_title`, `meta_keywords`, `meta_description` are populated |

---

## 3. Admin Panel Tests

### 3.1 Authentication

| # | Steps | Expected Result |
|---|---|---|
| 3.1.1 | Navigate to `{BASE_URL}/client-login/login` | Login form renders (AdminLTE styled) |
| 3.1.2 | Enter **wrong** credentials and submit | Error message: "These credentials do not match our records" |
| 3.1.3 | Enter **correct** admin credentials and submit | Redirects to admin dashboard at `{BASE_URL}/client-login/` |
| 3.1.4 | Navigate to `{BASE_URL}/login` | Returns **404** (this is intentional — admin login is at `/client-login/login`) |
| 3.1.5 | Click **Logout** | Logged out, redirected to login page |
| 3.1.6 | Try accessing `{BASE_URL}/client-login/` without logging in | Redirected to `/client-login/login` |
| 3.1.7 | Navigate to `{BASE_URL}/password/reset` | Password reset request form renders |
| 3.1.8 | Submit a valid email on the reset form | "We have emailed your password reset link!" message |

---

### 3.2 Dashboard & Settings

| # | Steps | Expected Result |
|---|---|---|
| 3.2.1 | After login, verify the dashboard loads | Dashboard page with AdminLTE layout, sidebar navigation |
| 3.2.2 | Click **Settings** in the sidebar | Settings form loads with site configuration fields |
| 3.2.3 | Update a setting (e.g., site name) and save | Success toast notification "Settings updated" |
| 3.2.4 | Navigate to **Change Password** | Form shows old password, new password, confirm password fields |
| 3.2.5 | Submit a valid password change | Success message; re-login works with new password |

---

### 3.3 CRUD Operations (All Admin Resources)

For **each** of the following admin resources, perform the **Create → Read → Update → Delete** test cycle:

| Resource | Admin URL | Key Fields to Test |
|---|---|---|
| **Users** | `/client-login/users` | Name, email, password, role |
| **Sliders** | `/client-login/sliders` | Title, image upload, active/deactive toggle |
| **CMS Pages** | `/client-login/cms` | Title, SEO URL, content (CKEditor), template selection |
| **Welcome Packages** | `/client-login/welcome_packages` | Title, content, property association |
| **Email Templates** | `/client-login/email-templetes` | Subject, body content |
| **FAQs** | `/client-login/faqs` | Question, answer, copy data |
| **Galleries** | `/client-login/galleries` | Title, images (drag-drop uploader), active/deactive |
| **Newsletters** | `/client-login/newsletters` | View subscribers list |
| **Our Clients** | `/client-login/our-clients` | Name, logo upload |
| **Testimonials** | `/client-login/testimonials` | Author, content, copy data |
| **Our Teams** | `/client-login/our-teams` | Name, role, photo upload |
| **Services** | `/client-login/services` | Title, description, CKEditor content |
| **Contact Enquiries** | `/client-login/contact-us-enquiries` | View/delete submitted contact forms |
| **Landing CMS** | `/client-login/landing_cms` | Page content with CKEditor |
| **SEO Pages** | `/client-login/seo_pages` | Meta title, description, keywords, CKEditor |
| **Onboarding Requests** | `/client-login/onboarding_requests` | View submitted onboarding forms |
| **Maximize Assets** | `/client-login/maximize-assets` | Content management |
| **Property Management Requests** | `/client-login/property_management_requests` | View submitted forms |
| **Locations** | `/client-login/locations` | Name, SEO URL, image, description |
| **Coupons** | `/client-login/coupons` | Code, discount type/value, date range |
| **Attractions** | `/client-login/attractions` | Title, description, category, location, images |
| **Attraction Categories** | `/client-login/attraction-categories` | Name, image |
| **Blogs** | `/client-login/blogs` | Title, category, content (CKEditor), featured image, active/deactive |
| **Blog Categories** | `/client-login/blog-category` | Name, copy data |
| **Guesty Properties** | `/client-login/guesty_properties` | View synced properties, edit local overrides (images, description), sub-location AJAX |

**For each resource, verify:**

| # | Steps | Expected |
|---|---|---|
| 3.3.1 | Navigate to the listing page | Table loads with existing records, pagination works |
| 3.3.2 | Click **Create New** / **Add** | Create form loads, all fields render correctly |
| 3.3.3 | Fill in all fields and **submit** | Record created, success toast appears, redirected to listing |
| 3.3.4 | Click **Edit** on a record | Edit form loads, pre-populated with existing data |
| 3.3.5 | Change a field and **save** | Record updated, success toast appears |
| 3.3.6 | Click **Delete** on a record | Confirmation prompt → record deleted → success toast |
| 3.3.7 | Select multiple records → **Delete Selected** | Bulk delete works via `multipleDelete` endpoint |
| 3.3.8 | Test **Copy Data** (where available: sliders, FAQs, galleries, testimonials, blogs, blog categories) | New record created with duplicated data |
| 3.3.9 | Test **Active/Deactive** toggle (where available: sliders, galleries, blogs, properties) | Status toggles, reflected in listing |

---

### 3.4 Properties (Detailed Admin Tests)

Properties have nested sub-resources. Test each thoroughly.

| # | Steps | Expected |
|---|---|---|
| 3.4.1 | Create a new property with all fields including images (drag-drop uploader) | Property created, images uploaded to `public/uploads/properties/` |
| 3.4.2 | Edit a property: update caption, reorder images via drag-drop | Caption/sort saved via AJAX (`update-property-caption-and-sorting`) |
| 3.4.3 | Delete a property image | Image removed via `image-delete-asset` endpoint |
| 3.4.4 | Navigate to **Calendar** tab (`properties/{id}/calendar`) | Calendar view loads (iframe for FullCalendar) |
| 3.4.5 | Add an iCal import URL (`properties/{id}/calendar/create`) | Import feed saved, events appear |
| 3.4.6 | Refresh an iCal import (`import-list-refresh/{id}`) | Events re-fetched from external URL |
| 3.4.7 | Self-iCal refresh (`self-ical-refresh`) | Local website events exported to `.ics` file |
| 3.4.8 | Navigate to **Rates** tab (`properties/{id}/rates`) | Rate list shows existing rates |
| 3.4.9 | Create a new rate with date range and price | Rate saved successfully |
| 3.4.10 | Edit and delete a rate | CRUD works for rates |
| 3.4.11 | Navigate to **Amenity Groups** (`properties/{id}/group-amenities`) | Amenity groups list loads |
| 3.4.12 | Create group → Add amenities to group → Active/Deactive → Delete | Full CRUD works for amenity hierarchy |
| 3.4.13 | Navigate to **Rooms** (`properties/{id}/rooms`) | Rooms list loads |
| 3.4.14 | Create room → Add sub-rooms → Active/Deactive → Delete | Full CRUD works for room hierarchy |
| 3.4.15 | Delete property spaces via `delete-property-space-single` | Space removed |

---

### 3.5 Booking Enquiries (Admin)

| # | Steps | Expected |
|---|---|---|
| 3.5.1 | Navigate to `{BASE_URL}/client-login/booking-enquiries` | Booking enquiries list loads |
| 3.5.2 | Click on a booking to view/edit | Booking detail shows guest info, dates, pricing |
| 3.5.3 | Create a new booking: fill in property, dates, guest details | AJAX calls `get-checkin-checkout-data-gaurav` for availability and `admin-checkajax-get-quote` for pricing |
| 3.5.4 | Click **Confirm** on a booking | Status updated, redirects back with success message |
| 3.5.5 | View bookings for a specific property (`booking-enquiries/properties/{id}`) | Filtered list of bookings for that property |
| 3.5.6 | Delete a booking enquiry | Record removed |

---

### 3.6 Media Center

| # | Steps | Expected |
|---|---|---|
| 3.6.1 | Navigate to `{BASE_URL}/client-login/media-center` | Media library loads showing uploaded files |
| 3.6.2 | Upload a new file | File uploaded via `new-file-uploads` endpoint, appears in library |
| 3.6.3 | Delete a file from media center | File removed via `medias-destroy` endpoint |

---

### 3.7 CKEditor Upload

| # | Steps | Expected |
|---|---|---|
| 3.7.1 | On any CKEditor field, click the image icon to upload | Upload dialog opens |
| 3.7.2 | Select/upload an image | Image uploaded via `ckeditor/upload`, inserted into editor |

---

## 4. Frontend Library Compatibility Tests

### 4.1 jQuery & Bootstrap (Public Pages)

| # | Steps | Expected |
|---|---|---|
| 4.1.1 | On any public page, open browser DevTools → Console | No JavaScript errors related to jQuery or Bootstrap |
| 4.1.2 | Open DevTools → Network → filter `.js` | `jquery-3.6.0.min.js` loads (HTTP 200) |
| 4.1.3 | Open DevTools → Network → filter `.css` | `bootstrap.min.css` (v5.3.0) loads (HTTP 200) |
| 4.1.4 | Test dropdown menus, mobile hamburger menu | Bootstrap JS interactions work (toggle, collapse) |
| 4.1.5 | Verify Font Awesome icons render (e.g., social media icons in footer) | Icons display correctly, not broken squares |

---

### 4.2 jQuery UI Datepicker (Admin)

| # | Steps | Expected |
|---|---|---|
| 4.2.1 | In admin panel, navigate to any page with date fields (Booking Enquiries → Create, Property Rates → Create) | Date input fields are present |
| 4.2.2 | Click on a date input field with class `.datepicker` | jQuery UI calendar popup appears |
| 4.2.3 | Select a date | Date populates the input field in correct format |
| 4.2.4 | Navigate months forward/backward in the picker | Calendar navigates correctly |
| 4.2.5 | Verify DateRangePicker on Property Rates (if CDN-loaded) | Date range selection works (uses Moment.js + daterangepicker from CDN) |

---

### 4.3 Toastr Notifications (Admin)

| # | Steps | Expected |
|---|---|---|
| 4.3.1 | In admin panel, create/update/delete any record | Green "success" toast notification slides in from top-right |
| 4.3.2 | Trigger an error (e.g., submit empty required form) | Red "danger/error" toast notification appears |
| 4.3.3 | Toast auto-dismisses after a few seconds | Toast fades out automatically |
| 4.3.4 | Open DevTools → Console | No errors related to `toastr is not defined` |

---

### 4.4 CKEditor (Rich Text Editor — Admin)

| # | Steps | Expected |
|---|---|---|
| 4.4.1 | Navigate to any admin form with a rich-text area (CMS, Blogs, Attractions, Services, Landing CMS, SEO Pages) | CKEditor toolbar loads (bold, italic, lists, image, link, etc.) |
| 4.4.2 | Type text with formatting (bold, italic, bullet list) | Formatting applied visually |
| 4.4.3 | Insert an image via CKEditor upload | Image uploads and appears in editor |
| 4.4.4 | Save the form, then re-open to edit | HTML content is preserved, CKEditor renders it correctly |
| 4.4.5 | Open DevTools → Console | No JS errors about `CKEDITOR is not defined` |

---

### 4.5 Drag-and-Drop Image Uploader (Admin Properties & Guesty Properties)

| # | Steps | Expected |
|---|---|---|
| 4.5.1 | Navigate to Properties → Create or Properties → Edit | Image upload area renders with drag-drop zone |
| 4.5.2 | Drag images from desktop into the upload zone | Images preview in the upload area |
| 4.5.3 | Click the upload zone to browse and select files | File browser opens, selected images preview |
| 4.5.4 | Submit the form with uploaded images | Images saved to `public/uploads/properties/` |
| 4.5.5 | On edit page, existing images display with remove/reorder options | Previous images shown, can be deleted or reordered |
| 4.5.6 | Navigate to Guesty Properties → Edit | Same drag-drop uploader works for Guesty property image overrides |

---

### 4.6 FullCalendar (Property Calendar — Admin)

| # | Steps | Expected |
|---|---|---|
| 4.6.1 | Navigate to Properties → select a property → Calendar tab | Calendar view loads (via iframe) |
| 4.6.2 | Calendar shows booked/blocked dates | Events displayed on correct dates with color coding |
| 4.6.3 | Navigate months forward/backward | Calendar updates correctly |

---

### 4.7 Owl Carousel / Slick Slider (Public Pages)

| # | Steps | Expected |
|---|---|---|
| 4.7.1 | On the homepage, verify the main slider/carousel | Slides auto-rotate, left/right navigation arrows work |
| 4.7.2 | If property images use a slider on the detail page | Image carousel slides correctly, thumbnails (if any) work |
| 4.7.3 | Open DevTools → Console | No errors related to Owl Carousel or Slick |

---

### 4.8 Fancybox / Lightbox (Image Gallery)

| # | Steps | Expected |
|---|---|---|
| 4.8.1 | On a property detail page or gallery page, click an image thumbnail | Fancybox lightbox overlay opens with the full-size image |
| 4.8.2 | Navigate through images with left/right arrows | Previous/next images load in the lightbox |
| 4.8.3 | Click X or click outside to close | Lightbox closes, returns to the page |

---

## 5. iCal Calendar Sync Tests

### 5.1 iCal Export (`.ics` File Generation)

| # | Steps | Expected |
|---|---|---|
| 5.1.1 | Find a property ID that has website bookings/events | — |
| 5.1.2 | Navigate to `{BASE_URL}/ical/{property_id}` | Browser downloads or displays a `.ics` file with `BEGIN:VCALENDAR` content |
| 5.1.3 | Open the `.ics` file in a text editor | Valid iCal format: contains `VEVENT` entries with `DTSTART`, `DTEND`, `SUMMARY` |
| 5.1.4 | Import the `.ics` URL into Google Calendar or another calendar app | Events appear on correct dates |

---

### 5.2 iCal Import (External Calendar Feeds)

| # | Steps | Expected |
|---|---|---|
| 5.2.1 | In admin, go to a property's Calendar tab → Import List | List of configured iCal import URLs |
| 5.2.2 | Add a new iCal import URL (e.g., Airbnb or VRBO calendar link) | URL saved |
| 5.2.3 | Click **Refresh** on the import | Events from external calendar appear on the property calendar |
| 5.2.4 | Navigate to `{BASE_URL}/refresh-calendar-data` | All iCal imports across all properties are refreshed. Page shows result message |

---

### 5.3 PriceLabs Sync

| # | Steps | Expected |
|---|---|---|
| 5.3.1 | Navigate to `{BASE_URL}/set-pricelab` | PriceLabs pricing data synced for properties. Success/result message |
| 5.3.2 | Verify updated pricing appears on property detail pages | Rates reflect PriceLabs data |

> **Note:** This only applies if PriceLabs is configured. If not used, mark this section as **⚠️ SKIP**.

---

## 6. Cron Job & Scheduled Task Verification

### 6.1 Laravel Scheduler Configuration

The system uses 3 scheduled Artisan commands defined in `routes/console.php`:

| Command | Schedule | Description |
|---|---|---|
| `communication:send-welcome-packages` | Daily at **07:00** | Sends welcome package emails to guests with upcoming check-ins |
| `communication:send-reminders` | Daily at **07:30** | Sends reminder emails to guests |
| `communication:send-review-requests` | Daily at **10:00** | Sends review request emails to guests after checkout |

All commands run with `withoutOverlapping()` to prevent duplicate execution.

---

### 6.2 Crontab Setup Verification

| # | Steps | Expected |
|---|---|---|
| 6.2.1 | SSH into the server | — |
| 6.2.2 | Run: `crontab -l` (as the web server user, e.g., `www-data`) | Should contain: `* * * * * cd /path/to/guesty-core && php artisan schedule:run >> /dev/null 2>&1` |
| 6.2.3 | If the crontab entry is missing, add it: `crontab -e` | Add the line from step 6.2.2 |

---

### 6.3 Manual Scheduler Test

| # | Steps | Expected |
|---|---|---|
| 6.3.1 | Run: `php artisan schedule:list` | Shows 3 commands with their next scheduled run times |
| 6.3.2 | Run: `php artisan communication:send-welcome-packages` | Command executes, logs output. Check `storage/logs/laravel.log` for results |
| 6.3.3 | Run: `php artisan communication:send-reminders` | Same — executes without errors |
| 6.3.4 | Run: `php artisan communication:send-review-requests` | Same — executes without errors |
| 6.3.5 | Verify emails were sent (check email provider logs or Mailtrap for staging) | Emails dispatched to qualifying guests |

---

### 6.4 Legacy Cron Endpoints (URL-Based)

These public URL endpoints also trigger the same operations (legacy compatibility for external cron services):

| # | URL | Expected |
|---|---|---|
| 6.4.1 | `{BASE_URL}/set-cron-job` | Triggers iCal cron processing |
| 6.4.2 | `{BASE_URL}/refresh-calendar-data` | Refreshes all iCal import feeds |
| 6.4.3 | `{BASE_URL}/send-welcome-packages` | Sends welcome package emails |
| 6.4.4 | `{BASE_URL}/send-reminder-email` | Sends reminder emails |
| 6.4.5 | `{BASE_URL}/send-review-email` | Sends review request emails |

> **Security Note:** These endpoints are public (no authentication required) for backward compatibility with cron services. They should ideally be protected by IP whitelisting or a secret token in production.

---

## 7. Guesty PMS Compatibility Tests

### 7.1 Token Refresh

| # | Steps | Expected |
|---|---|---|
| 7.1.1 | In admin panel, click **Refresh Token** button (or visit `{BASE_URL}/client-login/set-getToken`) | Success toast: "Open API token refreshed successfully" |
| 7.1.2 | Click **Refresh Booking Token** (or visit `{BASE_URL}/client-login/getBookingToken`) | Success toast: "Booking token refreshed successfully" |
| 7.1.3 | If tokens fail, check `.env` for correct `GUESTY_CLIENT_ID` and `GUESTY_CLIENT_SECRET` | Error message indicates which credential is wrong |

---

### 7.2 Property Sync

| # | Steps | Expected |
|---|---|---|
| 7.2.1 | In admin, click **Sync Properties** (or visit `{BASE_URL}/client-login/set-getPropertyData`) | Properties fetched from Guesty API. Success message: "Properties synced successfully (X properties)" |
| 7.2.2 | Navigate to Guesty Properties listing | Synced properties appear in the list with Guesty data |
| 7.2.3 | Open a Guesty property and verify details match Guesty dashboard | Title, description, images, amenities match |
| 7.2.4 | Check public website — property detail page shows Guesty data | Public-facing property pages render Guesty property data |

---

### 7.3 Booking Sync

| # | Steps | Expected |
|---|---|---|
| 7.3.1 | Click **Sync Bookings** (or visit `{BASE_URL}/client-login/set-getBookingData`) | Bookings fetched from Guesty API. Success message: "Bookings synced successfully (X bookings)" |
| 7.3.2 | Navigate to Booking Enquiries listing | Synced bookings appear with correct guest info, dates, amounts |
| 7.3.3 | Verify booking dates don't conflict with property calendar | Calendar shows booked dates as unavailable |

---

### 7.4 Review Sync

| # | Steps | Expected |
|---|---|---|
| 7.4.1 | Click **Sync Reviews** (or visit `{BASE_URL}/client-login/get-reviews-data`) | Reviews fetched from Guesty API. Success message: "Reviews synced successfully (X reviews)" |
| 7.4.2 | Check public website for guest reviews | Reviews display on property pages or review sections |

---

### 7.5 Public Guesty Sync Endpoints (Cron Compatibility)

These are the **unauthenticated** equivalents of the admin sync buttons (for external cron services):

| # | URL | Expected |
|---|---|---|
| 7.5.1 | `{BASE_URL}/set-getPropertyData` | Syncs properties (same as admin button) |
| 7.5.2 | `{BASE_URL}/set-getBookingData` | Syncs bookings |
| 7.5.3 | `{BASE_URL}/get-reviews-data` | Syncs reviews |
| 7.5.4 | `{BASE_URL}/set-token` | Refreshes Open API token |
| 7.5.5 | `{BASE_URL}/getBookingToken` | Refreshes Booking Engine token |

---

## 8. Email System Tests

### 8.1 Email Configuration

| # | Steps | Expected |
|---|---|---|
| 8.1.1 | Verify `.env` mail settings are configured | `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME` all set |
| 8.1.2 | On staging, consider using **Mailtrap** or **Mailhog** to intercept emails | All emails caught without sending to real addresses |

> **Note:** The system sends emails **synchronously** (no queue). All emails go through `EmailService::dispatch()` → `Mail::send('mail.dummyMail', ...)`. This means the page may be slightly slower during email dispatch, but emails are sent immediately.

---

### 8.2 Transactional Email Tests

Test each email trigger below. For each, verify the email is received (or caught by Mailtrap) with correct content.

| # | Trigger | Expected Email |
|---|---|---|
| 8.2.1 | Submit a **Contact form** on the public website | Contact confirmation email sent to admin with form data |
| 8.2.2 | Submit a **Newsletter subscription** | Newsletter subscription notification |
| 8.2.3 | Submit the **Booking flow** (save-booking-data) | Booking confirmation/inquiry email sent to guest and admin |
| 8.2.4 | Complete a **Rental Agreement** save | Rental agreement confirmation email |
| 8.2.5 | **Payment success** (after Stripe/PayPal payment) | Payment receipt email to guest |
| 8.2.6 | **Password reset** (`/password/reset`) | Password reset link email to the admin user |
| 8.2.7 | Run `php artisan communication:send-welcome-packages` | Welcome package emails sent to eligible guests (upcoming check-in) |
| 8.2.8 | Run `php artisan communication:send-reminders` | Reminder emails sent to eligible guests |
| 8.2.9 | Run `php artisan communication:send-review-requests` | Review request emails sent to guests after checkout |

**For each email, verify:**
- Email arrives (or is captured by Mailtrap)
- Subject line is correct
- Body contains expected data (guest name, property name, dates, amounts)
- Sender (`From`) matches `MAIL_FROM_ADDRESS` / `MAIL_FROM_NAME`
- No raw HTML or broken template in the email body

---

### 8.3 Email Templates (17 Templates)

The system has 17 email templates in `resources/views/mail/`. All emails render through `mail.dummyMail` as the wrapper. Verify the templates render correctly for each email type by triggering the related action (Section 8.2 above).

---

## 9. Payment Testing (Stripe & PayPal)

### 9.1 Gateway Configuration

| # | Steps | Expected |
|---|---|---|
| 9.1.1 | Check admin **Settings** for `which_payment_gateway` (or check `PAYMENT_GATEWAY` in `.env`) | Shows current gateway: `stripe` or `paypal` |
| 9.1.2 | Verify payment env vars are set (Section 1.1) | Stripe keys or PayPal keys configured |

---

### 9.2 Stripe Payment Flow

> **Prerequisites:** `PAYMENT_GATEWAY=stripe` or `which_payment_gateway=stripe` in settings. Use **Stripe test keys** (`sk_test_...` / `pk_test_...`) on staging.

| # | Steps | Expected |
|---|---|---|
| 9.2.1 | Complete the booking flow (Section 2.3) through to payment | Redirected to `{BASE_URL}/booking/payment/{booking_id}` |
| 9.2.2 | Stripe payment form loads | Card input fields render (Stripe.js Elements or legacy form) |
| 9.2.3 | Enter **test card**: `4242 4242 4242 4242`, exp: `12/34`, CVC: `123` | Card accepted, no validation errors |
| 9.2.4 | Submit payment | Loading spinner → redirects to `{BASE_URL}/payment/success/{id}` |
| 9.2.5 | Success/receipt page loads | Shows booking confirmation: property name, dates, amount paid |
| 9.2.6 | Check Stripe Dashboard (test mode) for the charge | Charge appears with correct amount and description |
| 9.2.7 | Enter a **declined card**: `4000 0000 0000 0002` | Error message: "Your card was declined" or similar |
| 9.2.8 | Submit payment with **empty card fields** | Client-side validation prevents submission |
| 9.2.9 | Test the PaymentIntent endpoint: POST to `{BASE_URL}/payment_init` with `total_amount=100` | JSON response with `clientSecret` |
| 9.2.10 | Test the SetupIntent endpoint: GET `{BASE_URL}/getIntendentData` | JSON response with `clientSecret` |

---

### 9.3 PayPal Payment Flow

> **Prerequisites:** `PAYMENT_GATEWAY=paypal` or `which_payment_gateway=paypal` in settings. Use **PayPal Sandbox** credentials on staging.

| # | Steps | Expected |
|---|---|---|
| 9.3.1 | Complete the booking flow through to payment | Redirected to `{BASE_URL}/booking/payment/paypal/{booking_id}` |
| 9.3.2 | PayPal payment page loads | PayPal button/form renders with correct amount |
| 9.3.3 | Click the PayPal payment button | Redirects to PayPal sandbox login |
| 9.3.4 | Login to PayPal sandbox, approve payment | Redirects back to site with `tx`, `st`, `amt` params |
| 9.3.5 | Payment recorded → redirects to `{BASE_URL}/payment/success/{id}` | Success page shows booking confirmation |
| 9.3.6 | Check PayPal Sandbox dashboard for the transaction | Transaction visible with correct amount |
| 9.3.7 | If `which_payment_gateway` is set to `stripe`, visit PayPal URL directly | Should redirect to Stripe payment page instead |

---

### 9.4 Payment Edge Cases

| # | Steps | Expected |
|---|---|---|
| 9.4.1 | Navigate to `{BASE_URL}/booking/payment/99999` (nonexistent booking ID) | Returns **404** page |
| 9.4.2 | Navigate to `{BASE_URL}/booking/payment/paypal/99999` | Returns **404** page |
| 9.4.3 | Navigate to `{BASE_URL}/payment/success/99999` | Returns **404** page |
| 9.4.4 | Try to pay for a booking that's already paid | Appropriate handling (redirect to receipt or error message) |

---

## 10. Error Handling & Edge-Case Tests

### 10.1 404 Pages

| # | Steps | Expected |
|---|---|---|
| 10.1.1 | Navigate to `{BASE_URL}/this-page-does-not-exist` | Custom 404 page renders (not a blank page or Laravel debug) |
| 10.1.2 | Navigate to `{BASE_URL}/properties/detail/nonexistent-slug` | 404 or "Property not found" message |
| 10.1.3 | Navigate to `{BASE_URL}/blog/nonexistent-slug` | 404 or "Blog not found" |
| 10.1.4 | Navigate to `{BASE_URL}/login` | Returns 404 (intentional — admin login is at `/client-login/login`) |

---

### 10.2 Form Validation

| # | Steps | Expected |
|---|---|---|
| 10.2.1 | Submit the **Contact form** with all fields empty | Validation error messages for each required field |
| 10.2.2 | Submit with an **invalid email** (e.g., `notanemail`) | "Must be a valid email" validation error |
| 10.2.3 | In admin, submit a **CMS page** with empty title | Validation prevents save, error message shown |
| 10.2.4 | In admin, submit a **Property** with missing required fields | Validation errors, form not saved |
| 10.2.5 | Test reCAPTCHA bypass: submit a public form without completing reCAPTCHA (if enabled) | Form rejected with reCAPTCHA error |

---

### 10.3 CSRF Protection

| # | Steps | Expected |
|---|---|---|
| 10.3.1 | Using a REST client (Postman/curl), POST to `{BASE_URL}/contact-post` without a CSRF token | HTTP 419 "Page Expired" response |
| 10.3.2 | Submit a form normally via the website | CSRF token included automatically, form submits successfully |

---

### 10.4 Authentication Guards

| # | Steps | Expected |
|---|---|---|
| 10.4.1 | Without logging in, try to access `{BASE_URL}/client-login/` | Redirected to `/client-login/login` |
| 10.4.2 | Without logging in, try to access `{BASE_URL}/client-login/properties` | Redirected to login |
| 10.4.3 | Without logging in, try to access `{BASE_URL}/client-login/booking-enquiries` | Redirected to login |
| 10.4.4 | Without logging in, **public** routes (homepage, blogs, booking flow) work normally | No redirect to login |

---

### 10.5 reCAPTCHA (Google)

> **Note:** reCAPTCHA configuration is stored in the **database** (`basic_settings` table), not in `.env`. Keys are read via `\ModelHelper::getDataFromSetting()`.

| # | Steps | Expected |
|---|---|---|
| 10.5.1 | In admin Settings, check if `g_captcha_enabled` is `yes` | If yes, reCAPTCHA should appear on public forms |
| 10.5.2 | Check `google_captcha_site_key` and `google_captcha_secret_key` in settings | Both keys must be set for reCAPTCHA to work |
| 10.5.3 | On public forms (contact, booking), verify reCAPTCHA widget appears | Google reCAPTCHA v2 checkbox visible |
| 10.5.4 | Submit form without completing reCAPTCHA | Validation error for reCAPTCHA |
| 10.5.5 | Complete reCAPTCHA and submit form | Form submits successfully |

> If reCAPTCHA is not enabled (`g_captcha_enabled` ≠ `yes`), mark this section as **⚠️ SKIP**.

---

### 10.6 Server Error Handling

| # | Steps | Expected |
|---|---|---|
| 10.6.1 | Ensure `APP_DEBUG=false` in `.env` | — |
| 10.6.2 | Trigger a server error (e.g., temporarily misconfigure DB credentials) | Custom error page renders, no stack trace or debug info exposed |
| 10.6.3 | Check `storage/logs/laravel.log` | Error is logged with full stack trace for debugging |
| 10.6.4 | Restore correct configuration | Site works normally again |

---

## 11. Final Production Readiness Checklist

Complete this **after** all tests above have passed.

### Environment

| # | Check | Status |
|---|---|---|
| 11.1 | `APP_ENV=production` | ☐ |
| 11.2 | `APP_DEBUG=false` | ☐ |
| 11.3 | `APP_URL` matches the live domain (with HTTPS) | ☐ |
| 11.4 | All Guesty API keys are set (production keys, not sandbox) | ☐ |
| 11.5 | Payment keys are **live** keys (not test/sandbox) — **or** sandbox for staging | ☐ |
| 11.6 | `PAYPAL_MODE=live` (not `sandbox`) — for production only | ☐ |
| 11.7 | Mail credentials are production SMTP (not Mailtrap) | ☐ |

### Performance & Caching

| # | Check | Command | Status |
|---|---|---|---|
| 11.8 | Config cached | `php artisan config:cache` | ☐ |
| 11.9 | Routes cached | `php artisan route:cache` | ☐ |
| 11.10 | Views cached | `php artisan view:cache` | ☐ |
| 11.11 | Composer optimized | `composer install --no-dev --optimize-autoloader` | ☐ |

### Security

| # | Check | Status |
|---|---|---|
| 11.12 | HTTPS is enforced (HTTP redirects to HTTPS) | ☐ |
| 11.13 | Database credentials are not exposed in any public file | ☐ |
| 11.14 | `.env` file is not accessible via browser (`{BASE_URL}/.env` returns 403/404) | ☐ |
| 11.15 | `storage/` directory is not accessible via browser | ☐ |
| 11.16 | Admin panel requires authentication (Section 10.4 passed) | ☐ |
| 11.17 | CSRF protection is active (Section 10.3 passed) | ☐ |
| 11.18 | `APP_DEBUG=false` confirmed (no stack traces to users) | ☐ |

### Infrastructure

| # | Check | Status |
|---|---|---|
| 11.19 | Crontab entry exists for Laravel scheduler (Section 6.2) | ☐ |
| 11.20 | `php artisan storage:link` has been run | ☐ |
| 11.21 | File upload directories exist and are writable (Section 1.2) | ☐ |
| 11.22 | Frontend assets are accessible (Section 1.3 — all URLs return 200) | ☐ |
| 11.23 | SSL certificate is valid and not expiring soon | ☐ |
| 11.24 | Server timezone matches expected timezone | ☐ |
| 11.25 | PHP error logging is enabled (`error_log` in php.ini or Laravel log channel) | ☐ |

### Data Integrity

| # | Check | Status |
|---|---|---|
| 11.26 | All database migrations have run (`migrate:status` shows no pending) | ☐ |
| 11.27 | Guesty properties are synced and display correctly | ☐ |
| 11.28 | Existing bookings/enquiries are preserved and accessible | ☐ |
| 11.29 | CMS content (pages, blogs, FAQs) displays correctly | ☐ |
| 11.30 | Uploaded images (properties, galleries, blogs) are visible | ☐ |

### Smoke Test (Final Go/No-Go)

Perform these 5 quick checks as the **absolute last step** before going live:

| # | Test | Status |
|---|---|---|
| 11.31 | Homepage loads in < 5 seconds | ☐ |
| 11.32 | Admin login works | ☐ |
| 11.33 | A property detail page loads with images and pricing | ☐ |
| 11.34 | The booking quote AJAX returns valid pricing | ☐ |
| 11.35 | An email sends successfully (test via contact form) | ☐ |

---

## Sign-Off

| Role | Name | Date | Signature |
|---|---|---|---|
| QA Tester | | | |
| Developer | | | |
| Project Manager | | | |

---

*Generated from codebase analysis of `guesty-core/` (Laravel 12.53.0, PHP 8.3.30).
Documents referenced: `PARITY_AUDIT_REPORT.md`, `PARITY_FIX_REPORT.md`.*
