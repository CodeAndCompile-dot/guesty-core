# Rebuild Verification Checklist

> Cross-reference of every feature from the original system against the rebuilt modules.
> Use this during rebuild testing — tick each box only after manual / automated verification.
> **Original system: ~318 features | 51 modules**

---

## How to Use

1. After deploying each phase, go through the corresponding section below
2. Test each feature — mark `[x]` when verified working
3. Any `[ ]` still unchecked = **regression risk** — do not ship that phase

---

## Phase 1 — Auth & User

### Authentication & Authorization (Module 50)

- [ ] Admin login page renders
- [ ] Admin login with valid credentials → redirect to admin dashboard
- [ ] Admin login with invalid credentials → error, stays on login
- [ ] Admin logout → redirect to login page
- [ ] `auth` middleware blocks unauthenticated access to all `/admin/*` routes
- [ ] `guest` middleware redirects authenticated users away from login page
- [ ] Password stored with bcrypt hashing
- [ ] Session-based authentication persists across requests
- [ ] AdminLTE layout renders correctly with sidebar menu
- [ ] `MyMenuFilter` filters sidebar items correctly

### Admin — User Management (Module 43)

- [ ] User listing page — displays all admin users
- [ ] User creation — form renders with name, email, password fields
- [ ] User store — saves new user with bcrypt-hashed password
- [ ] User edit — form pre-populated with existing data
- [ ] User update — updates user; password only updated if provided (non-empty)
- [ ] User update — password preserved if field left empty
- [ ] User delete — removes user from database
- [ ] Admin password change page renders
- [ ] Admin password change — validates current password
- [ ] Admin password change — saves new password with bcrypt

---

## Phase 2 — Settings, Media & Standalone Content

### Admin — Dashboard & Settings (Module 11)

- [ ] Admin dashboard renders (redirects to Guesty properties listing)
- [ ] Media center — lists all uploaded files
- [ ] Media center — upload new file
- [ ] Media center — delete single file
- [ ] Media center — bulk delete multiple files
- [ ] Site settings page — displays all configuration fields
- [ ] Site settings update — saves site name, logo, favicon, contact info, social links
- [ ] Site settings update — saves SEO meta, Google Analytics, API keys, email settings
- [ ] Site settings — logo upload works
- [ ] Site settings — favicon upload works
- [ ] Data export functionality works

### Admin — CMS Pages (Module 25)

- [ ] CMS page listing — displays all pages
- [ ] CMS page creation — form with 23+ image upload fields
- [ ] CMS page store — saves with unique `seo_url` validation
- [ ] CMS page edit — form pre-populated
- [ ] CMS page update — conditional image re-uploads for all section fields
- [ ] CMS page update — preserves existing images when no new upload
- [ ] CMS page delete — removes page
- [ ] Image fields: `bannerImage`, `section_image_one` through `section_image_ten`
- [ ] Image fields: `vacation_one_image` through `vacation_four_image`, `ogimage`

### Admin — SEO CMS Pages (Module 26)

- [ ] SEO page listing
- [ ] SEO page creation with unique `seo_url`
- [ ] SEO page — dynamic attraction sections (JSON array with per-item image)
- [ ] SEO page — dynamic video sections (JSON array)
- [ ] SEO page update — image preservation on edit
- [ ] SEO page delete
- [ ] Multiple image fields: `image`, `bannerImage`, `vacation_one_image` through `vacation_four_image`

### Admin — Landing CMS Pages (Module 27)

- [ ] Landing page listing
- [ ] Landing page creation with unique `seo_url`
- [ ] Landing page — dynamic attraction sections (JSON with per-item image)
- [ ] Landing page — dynamic video sections (JSON)
- [ ] Landing page update — hidden field image preservation on edit
- [ ] Landing page delete

### Admin — Slider (Module 29)

- [ ] Slider listing — all homepage sliders
- [ ] Slider creation with image upload
- [ ] Slider edit
- [ ] Slider update with image upload
- [ ] Slider delete
- [ ] Slider activate
- [ ] Slider deactivate
- [ ] Slider duplicate/copy

### Admin — FAQ (Module 30)

- [ ] FAQ listing
- [ ] FAQ creation with question/answer
- [ ] FAQ edit
- [ ] FAQ update
- [ ] FAQ delete
- [ ] FAQ duplicate/copy

### Admin — Gallery (Module 28)

- [ ] Gallery listing
- [ ] Gallery creation with image upload
- [ ] Gallery edit
- [ ] Gallery update with image upload
- [ ] Gallery delete
- [ ] Gallery activate
- [ ] Gallery deactivate
- [ ] Gallery duplicate/copy

### Admin — Testimonials (Module 31)

- [ ] Testimonial listing
- [ ] Testimonial creation with image upload
- [ ] Testimonial edit
- [ ] Testimonial update with image upload
- [ ] Testimonial delete
- [ ] Testimonial duplicate/copy

### Admin — Our Team (Module 32)

- [ ] Team member listing
- [ ] Team member creation — validates `seo_url` unique, `first_name`, `last_name`, `email`
- [ ] Team member creation — profile image upload
- [ ] Team member edit
- [ ] Team member update with validation and image upload
- [ ] Team member delete

### Admin — Our Clients (Module 33)

- [ ] Client listing
- [ ] Client creation with image upload
- [ ] Client edit
- [ ] Client update with image upload
- [ ] Client delete

### Admin — Services (Module 34)

- [ ] Service listing
- [ ] Service creation with unique `seo_url`, image upload
- [ ] Service edit
- [ ] Service update — `seo_url` uniqueness check excluding current record
- [ ] Service update — image upload
- [ ] Service delete

### Admin — Email Templates (Module 36)

- [ ] Template listing
- [ ] Template creation
- [ ] Template edit — placeholder support visible
- [ ] Template update
- [ ] Template delete
- [ ] Templates support 30+ dynamic placeholders

### Admin — Contact Us Requests (Module 37)

- [ ] Request listing — all contact form submissions
- [ ] Request view — individual request details
- [ ] Request delete

### Admin — Newsletter (Module 38)

- [ ] Subscriber listing
- [ ] Subscriber add with unique email validation
- [ ] Subscriber edit
- [ ] Subscriber update
- [ ] Subscriber delete

### Admin — Onboarding Requests (Module 39)

- [ ] Request listing
- [ ] Request creation with file uploads (file1, file2)
- [ ] Request edit
- [ ] Request update with file re-uploads
- [ ] Request delete

### Admin — Property Management Requests (Module 40)

- [ ] Request listing
- [ ] Request creation
- [ ] Request edit
- [ ] Request update
- [ ] Request delete

### Admin — Welcome Packages (Module 41)

- [ ] Package listing
- [ ] Package creation with image and banner image upload
- [ ] Package edit
- [ ] Package update with image re-uploads
- [ ] Package delete

### Admin — Maximize Assets (Module 42)

- [ ] Asset listing
- [ ] Asset creation
- [ ] Asset edit
- [ ] Asset update
- [ ] Asset delete

### Admin — CKEditor (Module 44)

- [ ] CKEditor standalone page renders
- [ ] CKEditor inline image upload — saves to `public/uploads/` with timestamp filename
- [ ] CKEditor image upload — returns JavaScript callback for editor integration

---

## Phase 3 — Location & Property (Core Entity)

### Admin — Property Management (Module 12)

- [ ] Property listing — all properties in admin table
- [ ] Property creation form — multi-field form renders
- [ ] Property store — saves with unique `seo_url`
- [ ] Property store — name, description, location assignment
- [ ] Property store — multiple gallery image upload (bulk)
- [ ] Property store — multiple fee items saved
- [ ] Property store — multiple space items saved
- [ ] Property store — pricing configuration saved
- [ ] Property edit — form pre-populated with all sub-entities
- [ ] Property update — galleries updated with image re-upload
- [ ] Property update — fees updated
- [ ] Property update — spaces updated
- [ ] Property delete — removes property and all associated data
- [ ] Property activate — set status to active/visible
- [ ] Property deactivate — set status to inactive/hidden
- [ ] Property duplicate/copy — clones property with all data
- [ ] Gallery caption/sort AJAX update — `updateCaptionSOrt()`
- [ ] Gallery image delete — individual image removal
- [ ] Property space delete — individual space removal
- [ ] Multiple image upload during create/update

### Admin — Property Rates (Module 16)

- [ ] Rate group listing — all rate groups for a property
- [ ] Rate group creation form — start date, end date, pricing type
- [ ] Rate group store — date overlap validation (unique start/end per property)
- [ ] Rate group store — **default** pricing mode (single price + base price)
- [ ] Rate group store — **day-of-week** pricing mode (Mon–Sun individual prices)
- [ ] Rate group store — auto-generates per-day `PropertyRate` records
- [ ] Rate group edit
- [ ] Rate group update — same overlap validation
- [ ] Rate group update — re-generates per-day rates
- [ ] Rate group delete — cascade deletes all per-day `PropertyRate` records
- [ ] Rate group duplicate/copy
- [ ] Timestamp storage — Unix timestamps alongside date strings

### Admin — Property Rooms (Module 17)

- [ ] Room listing — all rooms for a property
- [ ] Room creation — title, image, banner image
- [ ] Room edit
- [ ] Room update with image uploads
- [ ] Room delete
- [ ] Room activate
- [ ] Room deactivate
- [ ] Room duplicate/copy

### Admin — Property Room Items (Module 18)

- [ ] Room item listing — all items within a room
- [ ] Room item creation with image upload
- [ ] Room item edit
- [ ] Room item update with image upload
- [ ] Room item delete
- [ ] Room item activate
- [ ] Room item deactivate
- [ ] Room item duplicate/copy

### Admin — Property Amenity Groups (Module 19)

- [ ] Amenity group listing — groups for a property
- [ ] Amenity group creation — name, image, banner
- [ ] Amenity group edit
- [ ] Amenity group update with image uploads
- [ ] Amenity group delete — cascade deletes all child amenities
- [ ] Amenity group activate
- [ ] Amenity group deactivate
- [ ] Amenity group duplicate/copy

### Admin — Property Amenities (Module 20)

- [ ] Amenity listing — amenities within a group
- [ ] Amenity creation — name, image, banner
- [ ] Amenity edit
- [ ] Amenity update with image uploads
- [ ] Amenity delete
- [ ] Amenity activate
- [ ] Amenity deactivate
- [ ] Amenity duplicate/copy
- [ ] Three-level nested navigation: Property → Group → Amenity

### Admin — Coupons (Module 35)

- [ ] Coupon listing
- [ ] Coupon creation — `code` required, `type` (percentage/fixed), `property_id`
- [ ] Coupon creation — image upload
- [ ] Coupon edit
- [ ] Coupon update
- [ ] Coupon delete
- [ ] Property-specific coupon association

### Property Listing & Search — Public (Module 2, partial)

- [ ] Property detail page — gallery, amenities, rooms, rates, reviews, calendar, related properties
- [ ] Property location page — properties filtered by location
- [ ] Local property rate calendar — day-by-day pricing for non-Guesty properties

### Helper — General Utility (Module 46, partial)

- [ ] Gross amount calculation for local properties (day-of-week / flat rate + cleaning + tax + additional fees)
- [ ] Property rate calendar data for front-end display
- [ ] Property list retrieval for dropdowns
- [ ] SEO URL resolver (CMS page by slug)
- [ ] Category retrieval for dropdowns
- [ ] Select list builders for various models

### Helper — ModelHelper (Module 47, partial)

- [ ] Per-day rate generation (`saveSIngleDatePropertyRate()` logic)
- [ ] Settings retrieval from `BasicSetting`
- [ ] Product image retrieval for a property
- [ ] Select list helpers
- [ ] Location/category lookups

---

## Phase 4 — Attractions & Blog

### Admin — Attractions (Module 21)

- [ ] Attraction listing
- [ ] Attraction creation — SEO URL, name, category, location, description, image uploads
- [ ] Attraction edit
- [ ] Attraction update — conditional image re-upload
- [ ] Attraction delete
- [ ] Validation: `seo_url` unique, `name` required, `attraction_category_id` required

### Admin — Attraction Categories (Module 22)

- [ ] Category listing
- [ ] Category creation with image upload
- [ ] Category edit
- [ ] Category update — deletes old image file on re-upload
- [ ] Category delete
- [ ] Old image cleanup on disk when image replaced

### Admin — Blog (Module 23)

- [ ] Blog listing
- [ ] Blog creation — SEO URL, title, content, category, featured image, banner
- [ ] Blog edit
- [ ] Blog update with image uploads
- [ ] Blog delete
- [ ] Blog activate (publish)
- [ ] Blog deactivate (unpublish)
- [ ] Blog duplicate/copy

### Admin — Blog Categories (Module 24)

- [ ] Category listing
- [ ] Category creation with image upload
- [ ] Category edit
- [ ] Category update with image upload
- [ ] Category delete
- [ ] Category duplicate/copy

### Public — Attractions (Module 1, partial)

- [ ] Single attraction page by `seo_url` slug
- [ ] Attractions filtered by location
- [ ] Attractions filtered by category

### Public — Blog (Module 1, partial)

- [ ] Blog listing page — paginated
- [ ] Blog category filtering
- [ ] Single blog page by `seo_url` slug

---

## Phase 5 — Guesty PMS Integration

### Guesty PMS Integration (Module 7 + Module 45)

**Authentication & Tokens:**
- [ ] OAuth2 client credentials authentication (client_id + client_secret → Bearer token)
- [ ] Open API token retrieval (`open-api.guesty.com`)
- [ ] Booking Engine API token retrieval (`booking.guesty.com`)
- [ ] Dual API support — Open API + Booking Engine API
- [ ] Token caching / refresh on expiry

**Property Data:**
- [ ] Property data fetching — full details including pictures, amenities, terms
- [ ] AJAX property data fetch — returns Guesty property for client-side rendering

**Availability & Pricing:**
- [ ] Availability querying — date-range availability checks
- [ ] Per-night pricing — day-by-day rate retrieval from Guesty calendar
- [ ] Calendar fee data retrieval
- [ ] Additional fee retrieval (`getAdditionalFeeData`, `getAdditionalFeeDataAll`)
- [ ] Fee calculation — cleaning fees, additional fees, taxes from Guesty
- [ ] Search availability via Guesty Booking Engine

**Guest Management:**
- [ ] Guest creation in Guesty PMS
- [ ] Guest data retrieval from Guesty

**Reservation Lifecycle:**
- [ ] Reservation creation in Guesty
- [ ] Reservation confirmation in Guesty
- [ ] Booking data push to Guesty (`setBookingData`, `setBookingDataNew`)
- [ ] Full Guesty booking save (`saveBookingUsingGuestyData`)
- [ ] Paginated booking retrieval (skip/limit)
- [ ] Booking data retrieval by reservation ID

**Quotes:**
- [ ] Quote generation via Guesty Booking Engine (`getQuoteNewNew`)
- [ ] Quote generation alternate methods (`getQuouteNew`, `getQuouteNewNew`)

**Payment:**
- [ ] Payment attachment to Guesty reservation
- [ ] Payment ID retrieval from Guesty reservation
- [ ] Mark reservation as paid in Guesty (`paidAPi`)
- [ ] Guesty Pay tokenization (JavaScript card tokenization widget)

**Utility:**
- [ ] Generic API wrapper (`customAPI`) for ad-hoc Guesty calls

**Review:**
- [ ] Review data retrieval from Guesty for a property

### Admin — Guesty Property Management (Module 13)

- [ ] Guesty property listing (via dashboard redirect)
- [ ] Guesty property edit — local overrides: booklet PDF, banner, feature image, OG image, rental agreement PDF
- [ ] Guesty property update — file uploads for all override fields
- [ ] Sub-location AJAX dropdown — returns sub-locations by parent location
- [ ] No create/delete in admin — Guesty properties managed in Guesty

### API Endpoints — Guesty (Module 51, partial)

- [ ] `/getPropertyData/{id}` — fetch property from Guesty
- [ ] `/getToken` — get Guesty Open API token
- [ ] `/getBookingToken` — get Guesty Booking Engine token
- [ ] `/getBookingData/{id}` — fetch booking from Guesty
- [ ] `/getReviewData/{id}` — fetch reviews from Guesty
- [ ] `/getSubLocationList` — AJAX sub-location dropdown

---

## Phase 6 — iCal, PriceLabs & Calendar

### iCal / Calendar Sync (Module 8 + Module 49)

**iCal Feed Management:**
- [ ] iCal feed parsing — parses `.ics` files from external URLs
- [ ] iCal event extraction — DTSTART, DTEND, SUMMARY, UID
- [ ] iCal event storage in `ical_events` table
- [ ] iCal import list management — tracks feed URLs per property
- [ ] Single feed refresh — parse URL, upsert events
- [ ] Bulk feed refresh — all `IcalImportList` records refreshed
- [ ] Master cron job triggers iCal refresh (now Artisan command)

**iCal Availability Checking:**
- [ ] iCal date conflict check — requested dates vs existing events
- [ ] Detailed iCal conflict check — granular date-range overlap detection
- [ ] iCal check-in/check-out validation

**iCal Export:**
- [ ] iCal file generation — `.ics` export for a property
- [ ] Export includes all bookings and blocked dates
- [ ] Self iCal file regeneration

### Admin — Property Calendar (Module 15)

- [ ] Calendar event listing — all iCal events for a property
- [ ] iCal import list — view all feed URLs for a property
- [ ] Add iCal import — new feed URL with unique validation, auto-refresh on save
- [ ] Refresh single iCal feed — manual re-import trigger
- [ ] Refresh self iCal — regenerate property's own export file
- [ ] Delete iCal import — removes feed URL and all associated events

### PriceLabs Dynamic Pricing (Module 9)

- [ ] PriceLabs price sync — fetch from `api.pricelabs.co/v1/listing_prices`
- [ ] Date-range rate updates — updates `PropertyRate` and `PropertyRateGroup`
- [ ] Minimum stay sync — syncs minimum night requirements from PriceLabs
- [ ] PriceLabs sync now runs as Artisan command (not cron URL)

### Cron → Artisan Command Migration

- [ ] `GET /set-cron-job` → `php artisan ical:refresh` (hourly)
- [ ] `GET /set-pricelab` → `php artisan pricelabs:sync` (daily)
- [ ] Old cron URL routes removed from `web.php`
- [ ] `php artisan schedule:run` configured in server crontab

---

## Phase 7 — Booking & Availability ⚠️ CRITICAL

### Booking & Reservation (Module 3)

**Booking Creation:**
- [ ] Save booking data (initial flow) — guest info, dates, pricing, coupon, unique reference
- [ ] Save booking data (Guesty flow) — Guesty-specific fields
- [ ] Booking reference generation — auto-generated unique reference numbers
- [ ] Guest count tracking — adults, children, infants, pets
- [ ] Dual property system — local properties vs Guesty-managed properties

**Pricing Calculation:**
- [ ] Cleaning fee calculation from property configuration
- [ ] Tax calculation based on property tax rate
- [ ] Additional fees — line-item fees attached to properties
- [ ] Coupon code application — percentage discount
- [ ] Coupon code application — fixed amount discount
- [ ] Coupon validation — correct property match
- [ ] Minimum night enforcement
- [ ] Gross amount for local: day-of-week pricing mode
- [ ] Gross amount for local: flat rate pricing mode
- [ ] Guesty quote pricing (via Guesty Booking Engine)

**Availability:**
- [ ] AJAX availability check (public) — date availability + pricing quote
- [ ] AJAX availability check (admin — create)
- [ ] AJAX availability check (admin — edit)
- [ ] Guesty availability check — property available for date range
- [ ] iCal availability cross-check — conflict detection for local properties
- [ ] Minimum nights validation

**Booking Lifecycle:**
- [ ] Booking status workflow — pending → confirmed → cancelled
- [ ] Update payment on booking — attaches payment reference after gateway return
- [ ] Post-payment quote retrieval — final Guesty quote after payment

**Rental Agreement:**
- [ ] Rental agreement display for a booking
- [ ] Rental agreement save — SSN, emergency contact, vehicle info, pet info, e-signature
- [ ] Booking preview page — pricing breakdown before payment

### Admin — Booking Request Management (Module 14)

- [ ] Booking listing — all booking requests
- [ ] Admin booking creation form
- [ ] Admin booking store — full guest details, property, dates, pricing
- [ ] Booking edit form
- [ ] Booking update
- [ ] Booking cancellation/delete — sends cancellation email, cancels in Guesty if applicable
- [ ] Booking confirmation — mark as confirmed
- [ ] Single property booking view — bookings filtered by property
- [ ] AJAX check-in/check-out data — availability/pricing for admin forms

### API Endpoints — Booking (Module 51, partial)

- [ ] `/checkAjaxGetQuoteData` — public availability/quote check
- [ ] `/adminCheckAjaxGetQuoteData` — admin create quote check
- [ ] `/adminCheckAjaxGetQuoteDataEdit` — admin edit quote check
- [ ] `/saveBookingData` — save booking via API
- [ ] `/updatepaymentBookingData` — update payment on booking via API

### Helper — Guesty Availability (Module 46, partial)

- [ ] Guesty availability check with day-by-day breakdown (`getGrossDataCheckerDays`)

---

## Phase 8 — Payment (Stripe + PayPal) ⚠️ CRITICAL

### Payment — Stripe (Module 4)

- [ ] Stripe payment page renders with booking summary
- [ ] Stripe direct charge via `Stripe\Charge::create`
- [ ] Stripe PaymentIntent creation for advanced flows
- [ ] Stripe SetupIntent creation for saving card without charging
- [ ] Post-payment booking update — Stripe transaction ID + payment status
- [ ] Post-payment email trigger — confirmation emails sent
- [ ] Post-payment Guesty sync — reservation created + confirmed in Guesty
- [ ] Guesty payment attachment — payment record attached to Guesty reservation

### Payment — PayPal (Module 5)

- [ ] PayPal payment page renders with PayPal JS SDK button
- [ ] PayPal payment verification — transaction details validated
- [ ] Post-payment booking update — PayPal transaction ID saved
- [ ] Post-payment email trigger
- [ ] Post-payment Guesty sync

### Payment — Receipts (Module 6)

- [ ] Booking receipt page (type 1) — formatted receipt after payment
- [ ] Booking receipt page (type 2) — alternative layout, handles Guesty and local
- [ ] Dynamic receipt content — booking, property, and payment details with line items

### Helper — ModelHelper Post-Payment (Module 47, partial)

- [ ] Post-payment processing — update booking status
- [ ] Post-payment processing — send confirmation emails to guest and admin
- [ ] Post-payment processing — trigger Guesty reservation creation and confirmation
- [ ] Post-payment processing — attach payment to Guesty reservation

---

## Phase 9 — Email Automation & Scheduled Jobs

### Automated Emails & Notifications (Module 10)

**Scheduled Emails:**
- [ ] Welcome package email — sent X days before check-in
- [ ] Welcome package (non-redirect variant) — returns JSON
- [ ] Reminder email — sent X days before arrival
- [ ] Reminder (non-redirect variant) — returns JSON
- [ ] Post-checkout review request email — sent after checkout
- [ ] Review email (non-redirect variant) — returns JSON

**Template Engine:**
- [ ] Template-based email rendering from `EmailTemplete` model
- [ ] 30+ placeholder replacement: `{name}`, `{email}`, `{phone}`, `{check_in}`, `{check_out}`
- [ ] Placeholders: `{property_name}`, `{total_amount}`, `{booking_id}`, `{adults}`, `{children}`
- [ ] Placeholders: `{infants}`, `{pets}`, `{cleaning_fee}`, `{tax}`, `{base_price}`
- [ ] Placeholders: `{additional_fee}`, `{message}`, `{star_rating}`, `{comment}`, `{review_link}`
- [ ] Placeholders: `{site_name}`, `{site_url}`, `{coupon_discount}`
- [ ] Direct HTML email with optional file attachments
- [ ] Gmail SMTP integration

**Event-Triggered Emails:**
- [ ] Booking confirmation email — to guest + admin after payment
- [ ] Booking cancellation email — to guest when admin cancels
- [ ] Contact form notification — admin email on new submission
- [ ] Newsletter subscription email — confirmation to subscriber
- [ ] Onboarding request email — admin notification with file attachments
- [ ] Property management inquiry email — admin notification
- [ ] Review submission email — admin notification

### Cron → Artisan Command Migration

- [ ] `GET /send-welcome-packages` → `php artisan email:welcome-packages` (daily)
- [ ] `GET /send-reminder-email` → `php artisan email:reminders` (daily)
- [ ] `GET /send-review-email` → `php artisan email:review-requests` (daily)
- [ ] Old cron URL routes removed
- [ ] All commands registered in `Console/Kernel.php` schedule

---

## Phase 10 — Public Website & Final Cleanup

### Public Website (Module 1)

- [ ] Homepage rendering — CMS content, properties, sliders, testimonials, FAQs, blogs, galleries, attractions, locations, team, clients
- [ ] Dynamic CMS page rendering — route-based lookup by `seo_url` slug
- [ ] SEO CMS page rendering — dedicated SEO-optimized pages
- [ ] Single team member profile page
- [ ] Vacation data page — dynamic vacation rental content
- [ ] XML Sitemap generation — `sitemap.xml` with all public URLs
- [ ] CAPTCHA reload — AJAX endpoint for Google reCAPTCHA refresh

### Public Forms

- [ ] Contact Us form — validates name/email/phone/message with reCAPTCHA
- [ ] Contact Us form — stores `ContactusRequest`
- [ ] Contact Us form — sends admin notification email
- [ ] Property Management inquiry form — with reCAPTCHA
- [ ] Property Management form — stores `PropertyManagementRequest`
- [ ] Property Management form — sends email
- [ ] Onboarding request form — multi-field with file uploads
- [ ] Onboarding form — reCAPTCHA validation
- [ ] Onboarding form — stores `OnboardingRequest`
- [ ] Onboarding form — sends email
- [ ] Newsletter subscription — unique email validation
- [ ] Newsletter subscription — stores `NewsLetter`
- [ ] Newsletter subscription — sends confirmation email
- [ ] Guest review submission — star rating, comment linked to booking
- [ ] Guest review — stores `Review`
- [ ] Guest review — sends admin notification email

### Legacy Code Deletion Verification

- [ ] `PageController.php` (884 lines) — deleted, all routes served by new controllers
- [ ] `ICalController.php` — deleted, replaced by admin calendar controller + Artisan commands
- [ ] `Payment/CommonController.php` — deleted, replaced by `ReceiptController`
- [ ] `GuestyApi` facade + helper (883 lines) — deleted, replaced by 7 integration classes
- [ ] `Helper` facade + helper (325 lines) — deleted, logic moved to services
- [ ] `LiveCart` facade + helper (763 lines) — deleted, replaced by iCal integration classes
- [ ] `MailHelper` facade + helper — deleted, replaced by `EmailService`
- [ ] `ModelHelper` facade + helper (243 lines) — deleted, logic moved to services + actions
- [ ] `Upload` helper — deleted, replaced by `UploadService`
- [ ] `BookingRequest-old.php` — deleted

---

## Cross-Cutting Concerns (Verify After ALL Phases)

### Route Integrity

- [ ] All route **names** unchanged from original — `route()` calls in Blade work
- [ ] All route **URLs** unchanged — bookmarked links, external references work
- [ ] No 404 for any previously working URL
- [ ] Admin routes still behind `auth` middleware
- [ ] `{seo_url}` catch-all route still at bottom of route file

### View Compatibility

- [ ] All `compact()` variable names unchanged
- [ ] All `$setting_data` global variable available in every view
- [ ] Flash message keys unchanged: `success`, `danger`
- [ ] AdminLTE sidebar renders correctly with all menu items
- [ ] All Blade `@section`, `@yield` names unchanged

### File Upload Integrity

- [ ] All uploads go to `public/uploads/{folder}` — same paths as before
- [ ] Existing uploaded files accessible at same URLs
- [ ] Old image deletion on re-upload works (where applicable)
- [ ] CKEditor image upload returns correct JavaScript callback

### Database Integrity

- [ ] All table names unchanged
- [ ] All column names unchanged
- [ ] All foreign key relationships return same data
- [ ] No orphaned records after cascade deletes
- [ ] `BasicSetting` key-value store fully functional

### Performance

- [ ] No N+1 query regressions (check with Debugbar)
- [ ] Token caching for Guesty API calls working
- [ ] iCal bulk refresh completes within acceptable time
- [ ] Admin listing pages load within 2 seconds

### Security

- [ ] Admin routes protected by `auth` middleware
- [ ] reCAPTCHA validation on all public forms
- [ ] Cron URL endpoints removed — replaced by Artisan commands
- [ ] Stripe keys not exposed in frontend (only publishable key)
- [ ] CSRF protection on all POST routes

---

## Summary

| Phase | Features | Verified | Remaining |
|---|---|---|---|
| Phase 1 — Auth & User | 10 | _/10 | _ |
| Phase 2 — Settings & Content | 99 | _/99 | _ |
| Phase 3 — Location & Property | 68 | _/68 | _ |
| Phase 4 — Attractions & Blog | 28 | _/28 | _ |
| Phase 5 — Guesty Integration | 37 | _/37 | _ |
| Phase 6 — iCal & Calendar | 20 | _/20 | _ |
| Phase 7 — Booking | 32 | _/32 | _ |
| Phase 8 — Payment | 16 | _/16 | _ |
| Phase 9 — Email & Jobs | 22 | _/22 | _ |
| Phase 10 — Public & Cleanup | 26 | _/26 | _ |
| Cross-Cutting | 20 | _/20 | _ |
| **TOTAL** | **378** | **_/378** | **_** |
