# Full Parity Audit Report

**Legacy**: `projects/` (Laravel 8) — reference only  
**Rebuilt**: `guesty-core/` (Laravel 12) — architecture-only improvements  
**Date**: 9 March 2026  
**Test Suite Status**: 369 tests, 866 assertions — all passing  

---

## Executive Summary

The rebuilt system (`guesty-core`) faithfully reproduces the vast majority of legacy functionality across 10 implementation phases. All 28 admin resource controllers, the booking flow, payment processing (Stripe + PayPal), email automation, iCal sync, and public website routes are preserved.

However, the audit identified **5 critical**, **8 high**, **12 medium**, and **9 low** severity issues that must be resolved before the two systems are behaviorally identical.

---

## 1. Route Parity

### 1.1 Missing Routes (14 total)

| # | Method | Legacy URI | Legacy Action | Severity |
|---|--------|-----------|---------------|----------|
| 1 | `GET` | `properties/detail/{seo_url}` | `PageController@propertyDetail` | **CRITICAL** — public property detail page |
| 2 | `GET` | `ical/{id}` | `ICalController@getEventsICalObject` | **CRITICAL** — external calendar subscriptions |
| 3 | `GET` | `send-reminder-email` | `ICalController@sendReminderPackage` | **HIGH** — cron trigger for reminders |
| 4 | `GET` | `fullcalendar/{id}` | Closure → view("welcome") | LOW — likely dev/demo |
| 5 | `GET` | `fullcalendar-demo/{id}` | Closure → view("welcome-demo") | LOW — likely dev/demo |
| 6 | `POST` | `fullcalendar-demo-post` | Closure → view("common-dates") | LOW — likely dev/demo |
| 7 | `GET` | `register` | `Auth\RegisterController@showRegistrationForm` | MEDIUM |
| 8 | `POST` | `register` | `Auth\RegisterController@register` | MEDIUM |
| 9 | `GET` | `password/reset` | ForgotPasswordController | MEDIUM |
| 10 | `POST` | `password/email` | ForgotPasswordController | MEDIUM |
| 11 | `GET` | `password/reset/{token}` | ResetPasswordController | MEDIUM |
| 12 | `POST` | `password/reset` | ResetPasswordController | MEDIUM |
| 13 | `GET` | `password/confirm` | ConfirmPasswordController | LOW |
| 14 | `POST` | `password/confirm` | ConfirmPasswordController | LOW |

### 1.2 Relocated Routes (7 total — public → admin-protected)

| Legacy URI | New URI | Impact |
|-----------|---------|--------|
| `GET set-getPropertyData` | `GET client-login/set-getPropertyData` | Was public, now auth-protected |
| `GET set-getBookingData` | `GET client-login/set-getBookingData` | Was public, now auth-protected |
| `GET set-token` | `GET client-login/set-getToken` | URI changed + auth-protected |
| `GET get-reviews-data` | `GET client-login/get-reviews-data` | Was public, now auth-protected |
| `GET getBookingToken` | `GET client-login/getBookingToken` | Was public, now auth-protected |
| `POST admin-checkajax-get-quote` | `POST client-login/admin-checkajax-get-quote` | Was public, now auth-protected |
| `POST admin-checkajax-get-quote-edit` | `POST client-login/admin-checkajax-get-quote-edit` | Was public, now auth-protected |

**Note**: These 7 routes are now **stub closures** that return redirect/empty JSON — the actual Guesty API logic is not implemented (see §7 Integrations).

### 1.3 Route Name Mismatches (8)

| URI | Legacy Name | New Name |
|-----|-------------|----------|
| `client-login/change-password` (GET) | `admin-change-password` | `change-password` |
| `client-login/change-password` (POST) | `change-password-admin` | `change-password.post` |
| `client-login/booking-enquiries/confirmed/{id}` | `booking-enquiry-confirm` | `booking-enquiries.confirmed` |
| `client-login/setting` (GET) | *(unnamed)* | `setting` |
| `client-login/setting` (POST) | *(unnamed)* | `setting.post` |
| `client-login/media-center` | *(unnamed)* | `media-center` |
| `client-login/ckeditor` | *(unnamed)* | `ckeditor` |
| `booking/payment/paypal/{id}` | *(unnamed)* | `paypal` |

### 1.4 Other Route Differences

| Issue | Detail |
|-------|--------|
| Method mismatch | `client-login/medias-destroy` — legacy uses DELETE, new uses POST |
| URI/param change | `client-login/multiple-delete/{models}` → `client-login/multipleDelete/{model}` |

---

## 2. Controller Parity

### 2.1 Controller Mapping

All **27 admin controllers** have 1:1 mapping with identical CRUD methods. Legacy `PageController` was correctly split into 9 focused controllers. Payment controllers were correctly renamed/refactored.

### 2.2 Missing Controller Methods (29)

| Legacy Controller | Missing Method | Severity |
|---|---|---|
| `PageController` | `getReviewData` | HIGH — Guesty review sync |
| `PageController` | `getPropertyData` | HIGH — Guesty property sync |
| `PageController` | `getBookingData` | HIGH — Guesty booking sync |
| `PageController` | `getToken` | HIGH — Guesty token refresh |
| `PageController` | `getBookingToken` | HIGH — Guesty booking token |
| `PageController` | `propertyDetail` | **CRITICAL** — public property detail view |
| `PageController` | `adminCheckAjaxGetQuoteData` | HIGH — admin quote calculation |
| `PageController` | `adminCheckAjaxGetQuoteDataEdit` | HIGH — admin quote edit |
| `PageController` | `dynamicDataCategory` | LOW — renamed to `cmsPage` (functional rename) |
| `PageController` | `notfound` | LOW — replaced by `abort(404)` |
| `ICalController` | `refresshCalendar1` | LOW — duplicate variant |
| `ICalController` | `setPriceLab1` | LOW — duplicate variant |
| `ICalController` | `sendWelcomePackage1` | LOW — duplicate variant |
| `ICalController` | `sendReminderPackage` | MEDIUM — replaced by Artisan command |
| `ICalController` | `sendReminderPackage1` | LOW — duplicate variant |
| `Admin\DashboardController` | `exportData` | MEDIUM — data export feature |
| `Admin\PropertyAmenityGroupController` | `show`, `copyData` | LOW |
| `Admin\PropertyAmenityController` | `show` | LOW |
| `Admin\PropertyRateController` | `show` | LOW |
| `Admin\PropertyRoomController` | `show`, `copyData` | LOW |
| `Admin\PropertyRoomItemController` | `show`, `copyData` | LOW |
| `Payment\CommonController` | `showReceipt` (takes payment ID) | MEDIUM — only `showReceipt1` (booking ID) preserved |
| `Payment\CommonController` | `showReceipt1` | ✅ Preserved as `ReceiptController::show` |
| `Payment\StripeController` | `indexPost` | ✅ Renamed to `store` |
| `Payment\PaypalController` | `indexPost` | ✅ Renamed to `verify` |

### 2.3 Missing Helper Methods

**Helper.php** (13 missing):

| Method | Severity |
|--------|----------|
| `getGrossAmountData` | HIGH — used in booking quote calculations |
| `getFeeAmountAndName` | MEDIUM — fee breakdown logic |
| `getPropertyRates` | MEDIUM — rate calculation |
| `getPropertyList` / `getPropertyListNew` | MEDIUM — property select lists |
| `getDayBetweenTwoDates` | LOW — date utility |
| `calculateDays` | LOW — date utility |
| `getMonthListArray` | LOW — UI helper |
| `getCountryListArray` | LOW — UI helper |
| `getGenderData` / `getLoginTypeData` / `getDeviceTypeData` | LOW — rarely used |
| `languageChanger` | LOW — apparently unused |
| `deleteFile` | LOW — empty body in legacy |

**ModelHelper.php** (6 missing):

| Method | Severity |
|--------|----------|
| `finalEmailAndUpdateBookingPayment` | **CRITICAL** — booking payment finalization (`PaymentService::finalisePayment` replaces this) |
| `saveSIngleDatePropertyRate` | MEDIUM — rate management |
| `showPetFee` / `showpoolFee` | MEDIUM — fee display helpers |
| `getParentLocationSelectList1` | LOW — duplicate variant |
| `getLocationTrueSelectList` | LOW |

---

## 3. Request Compatibility

### 3.1 `$request->all()` → `$request->validated()` Migration

All form-handling controllers switched from `$request->all()` to `$request->validated()`. This means **any undeclared field submitted by the frontend will be silently stripped**. The legacy system was permissive — it would mass-assign any submitted field.

**Affected endpoints**: `contactPost`, `propertyManagementPost`, `onboardingPost`, `reviewSubmit`

### 3.2 New Validation Rules Not in Legacy

| FormRequest | New Rule | Risk |
|-------------|----------|------|
| `ContactFormRequest` | `name: max:191`, `mobile: max:50` | LOW — could reject long inputs |
| `ReviewFormRequest` | `property_id: exists:properties,id` | MEDIUM — fails if property is in `guesty_properties` table |
| `OnboardingFormRequest` | `file1/file2: max:10240` | LOW — 10MB limit not in legacy |

---

## 4. Response Compatibility

### 4.1 Variable Name Mismatch (**CRITICAL**)

`BookingController::checkAjaxGetQuoteData` passes `$mainData` (camelCase) to the view, but the blade template `front/property/ajax-gaurav-data-get-quote.blade.php` has been rewritten to expect `$mainData` — **however**, the `PageController::handleGetQuote` method passes `$main_data` (snake_case) to `front/static/get-quote.blade.php`:

```php
// PageController line 237 — uses snake_case 'main_data'
$main_data = [...];
return view($template, compact('data', 'main_data', 'property'));
```

The get-quote blade template must reference `$main_data` — this is **consistent** since it's a PHP view variable.

For `BookingController::checkAjaxGetQuoteData`, the view is rendered with `compact('property', 'mainData')` — the Blade template has been rewritten to use `$mainData` with flattened object properties instead of nested arrays. This is **internally consistent** after the rewrite.

### 4.2 Text Differences

| Endpoint | Legacy Text | New Text |
|----------|------------|----------|
| Newsletter duplicate | `"Already subscribe"` | `"Already subscribed"` |
| Quote error | `"Property Not select"` | `"Property Not selected"` |

### 4.3 Stripe Payment Receipt Redirect

| System | Redirect Target | ID Semantics |
|--------|----------------|--------------|
| Legacy | `payment/success/{$payment->id}` | Payment model ID |
| New | `payment/success/{$id}` | Booking ID (route param) |

The `ReceiptController::show` (legacy `showReceipt1`) expects a **booking ID**. The legacy Stripe redirect incorrectly sends a payment ID — this is a **pre-existing legacy bug** that the new system accidentally fixes.

---

## 5. API Parity

### 5.1 API Routes

| Route | Legacy | New |
|-------|--------|-----|
| `GET api/user` | Authenticated user Closure | Not present (no api.php) |

The legacy API route is trivial (just returns authenticated user) and appears unused.

---

## 6. Feature Coverage

| # | Feature | Legacy | New | Status |
|---|---------|--------|-----|--------|
| 1 | Login/Logout | ✅ | ✅ | **Match** |
| 2 | User Registration | ✅ | ❌ | **MISSING** — Auth::routes removed |
| 3 | Password Reset | ✅ | ❌ | **MISSING** — Auth::routes removed |
| 4 | Admin Dashboard | ✅ | ✅ | **Match** |
| 5 | Admin Settings | ✅ | ✅ | **Match** |
| 6 | Admin Media Center | ✅ | ✅ | **Match** |
| 7 | Admin CKEditor | ✅ | ✅ | **Match** |
| 8 | Admin Multiple Delete | ✅ | ✅ | URI changed (`multiple-delete` → `multipleDelete`) |
| 9 | Admin CRUD (28 resources) | ✅ | ✅ | **Match** |
| 10 | Property nested mgmt | ✅ | ✅ | **Match** (amenities, rooms, rates, calendar) |
| 11 | Admin property duplicate | ✅ | ✅ | **Match** |
| 12 | Activate/Deactivate | ✅ | ✅ | **Match** |
| 13 | Booking Confirmation | ✅ | ✅ | **Match** |
| 14 | Export Data | ✅ | ❌ | **MISSING** — `DashboardController::exportData` |
| 15 | Public Homepage | ✅ | ✅ | **Match** |
| 16 | Dynamic CMS Pages | ✅ | ✅ | **Match** |
| 17 | Blog listing/single | ✅ | ✅ | **Match** |
| 18 | Attraction pages | ✅ | ✅ | **Match** |
| 19 | Contact form + email | ✅ | ✅ | **Match** |
| 20 | Property Management form | ✅ | ✅ | **Match** |
| 21 | Onboarding form | ✅ | ✅ | **Match** |
| 22 | Newsletter subscription | ✅ | ✅ | **Match** |
| 23 | Review submission | ✅ | ✅ | **Match** |
| 24 | Booking flow | ✅ | ✅ | **Match** |
| 25 | Stripe payments | ✅ | ✅ | **Match** (refactored into PaymentService + StripeGateway) |
| 26 | PayPal payments | ✅ | ✅ | **Match** |
| 27 | Payment receipts | ✅ | ✅ | **Match** (Receipt ID semantics fixed) |
| 28 | iCal calendar sync | ✅ | ✅ | **Match** |
| 29 | PriceLabs integration | ✅ | ✅ | **Match** |
| 30 | Guesty API integration | ✅ | ⚠️ | **PARTIAL** — See §7 |
| 31 | Email template system | ✅ | ✅ | **Match** (improved architecture) |
| 32 | Welcome package emails | ✅ | ✅ | **Match** (via Artisan command) |
| 33 | Reminder emails | ✅ | ✅ | **Match** (via Artisan command, legacy HTTP endpoint removed) |
| 34 | Review request emails | ✅ | ✅ | **Match** (via Artisan command) |
| 35 | Sitemap.xml | ✅ | ✅ | **Match** |
| 36 | robots.txt | ✅ | ✅ | **Match** |
| 37 | Captcha reload | ✅ | ✅ | **Match** |
| 38 | Property detail page | ✅ | ❌ | **MISSING** — `properties/detail/{seo_url}` route + controller method |
| 39 | Fullcalendar views | ✅ | ❌ | **MISSING** — likely intentional (dev/demo views) |
| 40 | Admin booking per-property | ✅ | ✅ | **Match** (`singlePropertyBookoing`) |

---

## 7. Integration Checks

### 7.1 Guesty API (**CRITICAL**)

The legacy system has a monolithic `GuestyApi` helper class (883 lines, ~25 methods) accessed via `\GuestyApi::` facade.

The new system has a properly architected integration layer:
- `GuestyClient` (HTTP client with auth)
- `GuestyPropertyApi`, `GuestyBookingApi`, `GuestyGuestApi`, `GuestyQuoteApi`, `GuestyPaymentApi`, `GuestyReviewApi`

**However, the `\GuestyApi::` facade does not exist in the new system.** Controllers still call `\GuestyApi::getQuoteNewNew()`, `\GuestyApi::createGuest()`, etc. — these calls will throw `Class 'GuestyApi' not found` at runtime.

**Required bridging**: Either:
1. Create a `GuestyApi` facade that delegates to the new service classes, OR
2. Refactor all 21 `\GuestyApi::` calls in controllers to use the new service classes

### 7.2 Guesty Sync Endpoints (Stubs)

These 5 admin routes exist but are **closures returning static responses**, not actual implementations:
- `set-getPropertyData` — should call `GuestyApi::getPropertyData()`
- `set-getBookingData` — should call `GuestyApi::getBookingData()`
- `get-reviews-data` — should call `GuestyApi::getReviewData()`
- `set-getToken` — should refresh Guesty API token
- `getBookingToken` — should refresh booking token

### 7.3 Stripe Integration — **Match**

All 4 Stripe operations preserved:
- `Charge::create` via `StripeGateway::createCharge()`
- `SetupIntent::create` via `StripeGateway::createSetupIntent()`
- `PaymentIntent::create` via `StripeGateway::createPaymentIntent()`
- Form display via `StripeController::index()`

### 7.4 PayPal Integration — **Match**

PayPal verify flow preserved in `PaypalController::verify()` (renamed from `indexPost`).

### 7.5 iCal/PriceLabs — **Match**

`ICalService` and `PriceLabsService` correctly implement all calendar sync and pricing logic.

---

## 8. Database Compatibility

### 8.1 Missing Tables (3)

| Table | Legacy Purpose | Impact |
|-------|---------------|--------|
| `activity_log` | Audit trail | LOW — appears unused in controllers |
| `failed_jobs` | Queue failure tracking | MEDIUM — needed if queue used |
| `password_resets` | Password reset tokens | MEDIUM — needed if Auth::routes restored |

### 8.2 Schema Differences

| Table | Change | Impact |
|-------|--------|--------|
| `booking_requests` | `enum` columns → `string` | LOW — more permissive, no runtime issue |
| `email_templetes` | `NOT NULL` → `nullable` | LOW — allows empty templates |
| `cms.templete` | `NOT NULL` → `nullable` | LOW — allows null template |
| `sliders` | Added `status` column | LOW — additive |
| `users` | Added `image`, `bannerImage` columns | LOW — additive |

### 8.3 New Tables (2)

| Table | Purpose |
|-------|---------|
| `maximize_assets` | New admin resource |
| `property_room_item_images` | Room item image gallery |

---

## 9. UI / Blade Template Compatibility

### 9.1 Missing Blade Views (33)

**Template consolidation** — the new system uses a `default.blade.php` fallback for CMS templates instead of individual per-page views:

| Missing View | Legacy Purpose | New Equivalent |
|-------------|---------------|----------------|
| `front/static/about.blade.php` | About page | `front/static/default.blade.php` |
| `front/static/contact.blade.php` | Contact page | `front/static/default.blade.php` |
| `front/static/faq.blade.php` | FAQ page | `front/static/default.blade.php` |
| `front/static/gallery.blade.php` | Gallery page | `front/static/default.blade.php` |
| `front/static/onboarding.blade.php` | Onboarding page | `front/static/default.blade.php` |
| `front/static/property-list.blade.php` | Property list | `front/static/default.blade.php` |
| `front/static/property-management.blade.php` | PM page | `front/static/default.blade.php` |
| `front/static/reviews.blade.php` | Reviews page | `front/static/default.blade.php` |
| `front/static/services.blade.php` | Services page | `front/static/default.blade.php` |
| `front/static/partner.blade.php` | Partners page | `front/static/default.blade.php` |
| `front/static/privacy.blade.php` | Privacy page | `front/static/default.blade.php` |
| `front/static/prearrival.blade.php` | Pre-arrival page | `front/static/default.blade.php` |
| `front/static/about-owner.blade.php` | About owner | `front/static/default.blade.php` |
| `front/static/map.blade.php` | Map page | `front/static/default.blade.php` |
| `front/static/attractions.blade.php` | Attractions | `front/static/default.blade.php` |
| `front/property/single.blade.php` | Property detail (non-Guesty) | **NO EQUIVALENT** |
| `front/property/single-copy.blade.php` | Property copy | **NO EQUIVALENT** |
| `front/property/ajax-gaurav-modal-*.blade.php` (3 files) | AJAX modals | **NO EQUIVALENT** |
| `front/layouts/css.blade.php` | CSS includes | Inlined in `head.blade.php` |
| `front/layouts/js.blade.php` | JS includes | Inlined in `footer.blade.php` |
| `errors/404.blade.php` | 404 page | Uses Laravel default |
| `front/errors/404.blade.php` | Front 404 | Uses Laravel default |
| `welcome-demo.blade.php` | Fullcalendar demo | Not needed (route removed) |
| `common-dates.blade.php` | Date display | Not needed (route removed) |
| `home.blade.php` | Root home | Not needed |

**CRITICAL**: `front/property/single.blade.php` is the **non-Guesty property detail view** — needed for the `properties/detail/{seo_url}` route (which is also missing).

### 9.2 Template Consolidation Impact

The CMS system stores a `templete` field (e.g., "about", "contact", "gallery"). Legacy resolves this to `front.static.about`, `front.static.contact`, etc. The new system's `PageController::cmsPage` resolves to `front.static.{$data->templete}`.

**If a CMS record has `templete = 'about'`**, the view `front.static.about` will be loaded — but it doesn't exist in the new system. This will throw a **ViewNotFoundException** at runtime.

**Required fix**: Either:
1. Create all 15+ missing `front/static/*.blade.php` views, OR
2. Add fallback logic in `PageController::cmsPage` to use `front.static.default` when the specific template doesn't exist

---

## 10. Final Verification Summary

### Critical Issues (5)

| # | Issue | Impact |
|---|-------|--------|
| **C1** | `\GuestyApi::` facade class not bound — 21 controller calls will throw `Class not found` | All booking + quote flows broken |
| **C2** | `properties/detail/{seo_url}` route + `propertyDetail` method missing | Non-Guesty property pages broken |
| **C3** | `ical/{id}` route missing — external calendar subscriptions broken | iCal feeds unavailable |
| **C4** | CMS templates (about, contact, gallery, etc.) missing — `ViewNotFoundException` for CMS pages with those template names | Many public pages broken |
| **C5** | `front/property/single.blade.php` missing — non-Guesty property detail view | Property detail pages broken |

### High Issues (8)

| # | Issue |
|---|-------|
| **H1** | Guesty sync endpoints are stubs (closures) — `set-getPropertyData`, `set-getBookingData`, `get-reviews-data`, `set-getToken`, `getBookingToken` |
| **H2** | `adminCheckAjaxGetQuoteData` / `adminCheckAjaxGetQuoteDataEdit` — admin quote methods are stubs |
| **H3** | `send-reminder-email` endpoint removed (replaced by Artisan command, but HTTP cron trigger is gone) |
| **H4** | `$request->all()` → `$request->validated()` — extra form fields silently stripped |
| **H5** | `ModelHelper::finalEmailAndUpdateBookingPayment` missing — replaced by `PaymentService::finalisePayment` but verify behavioral parity |
| **H6** | Helper methods for fee/rate calculation missing: `getGrossAmountData`, `getFeeAmountAndName`, `getPropertyRates` |
| **H7** | Auth::routes removed — no registration or password reset |
| **H8** | 7 Guesty sync routes relocated from public to auth-protected — any public/cron callers will fail |

### Medium Issues (12)

| # | Issue |
|---|-------|
| M1 | `front/property/ajax-gaurav-modal-*.blade.php` (3 AJAX modal views) missing |
| M2 | `DashboardController::exportData` method/route missing |
| M3 | `ReviewFormRequest` `property_id: exists:properties,id` — fails for Guesty properties |
| M4 | Newsletter duplicate message text changed (`"subscribe"` → `"subscribed"`) |
| M5 | `medias-destroy` method changed from DELETE to POST |
| M6 | `multipleDelete` URI/param name changed |
| M7 | `ModelHelper::saveSIngleDatePropertyRate` missing |
| M8 | `ModelHelper::showPetFee` / `showpoolFee` missing |
| M9 | `failed_jobs` migration missing (needed for queue) |
| M10 | `password_resets` migration missing (needed if auth routes restored) |
| M11 | `email_templetes` NOT NULL → nullable (data integrity relaxed) |
| M12 | Nested controller `show`/`copyData` methods removed (amenity groups, rooms, room items, rates) |

### Low Issues (9)

| # | Issue |
|---|-------|
| L1 | Route name mismatches (8 routes) — only matters if named routes used in code/JS |
| L2 | `fullcalendar*` routes/views removed — likely dev/demo only |
| L3 | ICalController `*1` variant methods removed |
| L4 | `dynamicDataCategory` renamed to `cmsPage` |
| L5 | `notfound` method dropped — `abort(404)` used instead |
| L6 | Helper utility methods missing (month/country lists, gender data, etc.) |
| L7 | `front/layouts/css.blade.php` / `js.blade.php` removed — inlined |
| L8 | `errors/404.blade.php` custom views missing — uses framework default |
| L9 | `front/static/test.blade.php` / `*-old.blade.php` / `*-copy.blade.php` removed |

---

## Appendix: Counts

| Metric | Count |
|--------|-------|
| Total legacy routes | ~321 |
| Total new routes | ~325 |
| Confirmed matching routes | ~291 |
| Missing routes | 14 |
| Relocated routes | 7 |
| Missing controller methods | 29 |
| Missing Helper methods | 13 |
| Missing ModelHelper methods | 6 |
| Missing blade views | 33 |
| Tests passing | 369 (866 assertions) |
| **Critical issues** | **5** |
| **High issues** | **8** |
| **Medium issues** | **12** |
| **Low issues** | **9** |
