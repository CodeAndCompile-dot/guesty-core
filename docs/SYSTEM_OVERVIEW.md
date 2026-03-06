# SYSTEM OVERVIEW — Bentonville Lodging Co.

> Auto-generated from codebase analysis. This document describes the **existing** system as-is, without redesign recommendations.

---

## 1. Project Purpose

Bentonville Lodging Co. is a **vacation rental / short-term property management** website built on Laravel. It serves two audiences:

- **Guests (public):** Browse vacation rental properties in the Bentonville, Arkansas area (and surrounding towns), check availability, get quotes, book stays, sign rental agreements, and pay online.
- **Admin (back-office):** Manage properties, bookings, pricing/calendar, CMS pages, blogs, galleries, email templates, coupons, and more via a protected admin panel at `/client-login`.

The system integrates heavily with **Guesty PMS** (Property Management System) for property data, availability, pricing, reservations, and payment processing, and also supports a local/manual property management flow with iCal calendar sync and PriceLabs dynamic pricing.

---

## 2. Main Modules / Domains

| Module | Description |
|---|---|
| **Property Management** | Two property systems: local `Property` (manual) and `GuestyProperty` (synced from Guesty API). Each has galleries, amenities, rooms, rates, fees, calendar. |
| **Booking & Reservations** | Quote generation, booking creation, rental agreement signing, payment processing. Bookings stored in `BookingRequest`. |
| **Payment Processing** | Stripe (direct), PayPal, and Guesty Pay tokenization flows. |
| **Calendar / Availability** | iCal import/export, Guesty availability sync, PriceLabs dynamic pricing integration. |
| **CMS / Content** | Static pages (`Cms`), landing pages (`LandingCms`), SEO pages (`SeoCms`), blogs, FAQs, galleries, testimonials, sliders, services. |
| **Attractions & Locations** | Location-based and category-based attraction listings. |
| **Email System** | Template-based email engine (`EmailTemplete` model + `MailHelper`) with placeholder substitution. |
| **Cron / Scheduled Tasks** | Calendar refresh, welcome package emails, payment reminder emails, review request emails, PriceLabs price sync. |
| **Admin Panel** | AdminLTE-based back-office for all CRUD operations, settings, media center, user management. |
| **Onboarding** | Property owner onboarding form collecting sensitive financial data. |
| **Property Management Requests** | Lead capture for property management services. |

---

## 3. External Integrations

### 3.1 Guesty PMS (Primary Integration)
- **Open API** (`open-api.guesty.com/v1/`) — OAuth2 client credentials
  - Sync property listings, availability/pricing calendar, reservations, reviews, guests
  - Create reservations, confirm bookings, manage guest payment methods
  - Get quotes (`/v1/quotes`)
  - Fetch additional fees, financial data per listing
- **Booking Engine API** (`booking.guesty.com/`) — Separate OAuth2 scope (`booking_engine:api`)
  - Create instant bookings from quotes
  - Get payment provider per listing
- **Guesty Pay** (`pay.guesty.com/api/tokenize/v2`) — Card tokenization for reservations
- **Token management:** Tokens stored in `basic_settings` table (`API-TOKEN-DATA`, `BOOKING-API-TOKEN-DATA`)

### 3.2 Stripe
- Direct charge flow via `Stripe\Charge::create`
- Payment Intent flow via `Stripe\PaymentIntent::create`
- Setup Intent creation for card setup
- Secret key stored in `basic_settings` (`stripe_secret_key`)

### 3.3 PayPal
- Client-side PayPal button integration (redirect-based)
- Verifies `st == "COMPLETED"` from PayPal return

### 3.4 PriceLabs
- Dynamic pricing API (`api.pricelabs.co/v1/listing_prices`)
- Syncs nightly prices and min-stay to local `PropertyRate` records
- API key stored in `basic_settings` (`pricelab_access_token`)

### 3.5 Google reCAPTCHA
- Server-side verification on contact form and property management form
- Keys stored in `basic_settings` (`google_captcha_site_key`, `google_captcha_secret_key`)

### 3.6 Gmail SMTP
- Email sending via `smtp.gmail.com:587`

### 3.7 iCal
- Import external iCal feeds (Airbnb, VRBO, etc.) to block dates
- Export iCal feeds for each property

---

## 4. Main Entities (Models)

### Core Business Models

| Model | Table | Purpose |
|---|---|---|
| `GuestyProperty` | `guesty_properties` | Properties synced from Guesty PMS |
| `Property` | `properties` | Locally-managed properties (manual) |
| `BookingRequest` | `booking_requests` | All booking/reservation records |
| `Payment` | `payments` | Payment transaction records |
| `Coupon` | `coupons` | Discount coupons |

### Property Sub-Models

| Model | Table | Purpose |
|---|---|---|
| `PropertyGallery` | `property_galleries` | Property images |
| `PropertyAmenityGroup` | `property_amenity_groups` | Amenity category groups |
| `PropertyAmenity` | `property_amenities` | Individual amenities |
| `PropertyRoom` | `property_rooms` | Room definitions |
| `PropertyRoomItem` | `property_room_items` | Sub-room / bed details |
| `PropertyRoomItemImage` | — | Room item images |
| `PropertyFee` | `property_fees` | Custom fees (cleaning, pet, etc.) |
| `PropertyRate` | `property_rates` | Per-date nightly pricing |
| `PropertyRateGroup` | `properties_rates_group` | Rate range definitions |
| `PropertySpace` | `property_spaces` | Property spaces (outdoor areas, etc.) |

### Guesty Sync Models

| Model | Table | Purpose |
|---|---|---|
| `GuestyPropertyPrice` | `guesty_property_prices` | Synced pricing data |
| `GuestyPropertyBooking` | `guesty_property_bookings` | Synced reservation data |
| `GuestyPropertyReview` | `guesty_property_reviews` | Synced guest reviews |
| `GuestyAvailablityPrice` | `guesty_availablity_prices` | Per-date availability + price calendar |

### Calendar Models

| Model | Table | Purpose |
|---|---|---|
| `IcalEvent` | `ical_events` | Parsed iCal events (blocked dates) |
| `IcalImportList` | `ical_import_list` | Registered iCal feed URLs |

### CMS / Content Models

| Model | Table | Purpose |
|---|---|---|
| `Cms` | `cms` | Static page content (home, about, contact, etc.) |
| `LandingCms` | `landing_cms` | Marketing landing pages |
| `SeoCms` | `seo_pages` | SEO-focused vacation pages |
| `Blog` | `blogs` | Blog posts (multilingual: EN + DE) |
| `BlogCategory` | `blog_categories` | Blog categories (multilingual) |
| `Service` | `services` | Service listings |

### Other Models

| Model | Table | Purpose |
|---|---|---|
| `Location` | `locations` | Geographic locations / towns |
| `Attraction` | `attractions` | Local attraction listings |
| `AttractionCategory` | `attraction_categories` | Attraction categories |
| `Gallery` | `galleries` | General site gallery |
| `Slider` | `sliders` | Homepage sliders |
| `Faq` | `faqs` | FAQ entries |
| `Testimonial` | `testimonials` | Guest reviews / testimonials |
| `OurTeam` | `our_teams` | Team member profiles |
| `OurClient` | `our_clients` | Client logos |
| `NewsLetter` | `newsletters` | Newsletter subscribers |
| `ContactusRequest` | `contactus_requests` | Contact form submissions |
| `PropertyManagementRequest` | `property_management_requests` | Property mgmt service leads |
| `OnboardingRequest` | `onboarding_requests` | Owner onboarding forms |
| `MaximizeAsset` | `maximize_assets` | "Maximize your asset" section |
| `WelcomePackage` | `welcome_packages` | Welcome package content |
| `EmailTemplete` | `email_templetes` | Email templates with placeholders |
| `BasicSetting` | `basic_settings` | Key-value site settings |
| `User` | `users` | Admin users |

---

## 5. Relationships Between Models

> **Critical observation:** Almost no Eloquent relationships are defined. Only `Blog ↔ BlogCategory` has explicit `belongsTo`/`hasMany`. All other relationships are implicit via foreign keys queried manually in controllers/helpers.

### Implicit Relationships (by FK column)

```
Property
 ├── PropertyGallery (property_id)
 ├── PropertyAmenityGroup (property_id)
 │    └── PropertyAmenity (property_amenity_id → group.id)
 ├── PropertyRoom (property_id)
 │    └── PropertyRoomItem (room_id)
 │         └── PropertyRoomItemImage
 ├── PropertyFee (property_id)
 ├── PropertyRate (property_id)
 ├── PropertyRateGroup (property_id)
 ├── PropertySpace (property_id)
 ├── IcalImportList (property_id)
 ├── IcalEvent (event_pid → property.id)
 ├── BookingRequest (property_id)
 └── Coupon (property_id)

GuestyProperty
 ├── GuestyPropertyPrice (property_id → guesty_property.id)
 ├── GuestyPropertyBooking (listingId → guesty_property._id)
 ├── GuestyPropertyReview (listingId → guesty_property._id)
 ├── GuestyAvailablityPrice (listingId → guesty_property._id)
 └── BookingRequest (property_id → guesty_property.id)

Location
 ├── Property (location_id)
 ├── GuestyProperty (location_id, sub_location_id)
 └── Attraction (location_id)

AttractionCategory
 └── Attraction (category_id)

BlogCategory
 └── Blog (blog_category_id) ✅ Defined in Eloquent

BookingRequest
 └── Payment (booking_id)
```

---

## 6. Payment Flow

The system has **three distinct payment paths:**

### Path A — Guesty Pay (New Primary Flow)
1. Guest selects property, dates, guests on front-end
2. `PageController@checkAjaxGetQuoteData` → calls `GuestyApi::getQuoteNewNew()` to get quote from Guesty
3. Guest fills in personal details → `PageController@saveBookingData`
   - Creates `BookingRequest` locally
   - Calls `GuestyApi::createGuest()` to register guest in Guesty
   - Calls `GuestyApi::newBookingCreate()` to create reservation in Guesty (status: "inquiry")
   - Redirects to `get-quote-after/{reservation_id}`
4. Guest sees payment form → `PageController@updatepaymentBookingData`
   - Calls `GuestyApi::getBookingPaymentid()` to get Stripe provider ID
   - Tokenizes card via `pay.guesty.com/api/tokenize/v2`
   - Calls `GuestyApi::confirmBooking()` to confirm reservation
   - Calls `GuestyApi::paymentAttached()` to attach payment method to guest
   - Updates `BookingRequest` status to confirmed
   - Redirects to success page

### Path B — Direct Stripe Charge (Legacy for local properties)
1. Admin creates booking → confirms → sends payment link to guest
2. Guest visits `booking/payment/{id}` → `StripeController@index`
3. Guest submits card → `StripeController@indexPost`
   - Creates Stripe Customer, charges via `Stripe\Charge::create`
   - Creates `Payment` record
   - Calls `ModelHelper::finalEmailAndUpdateBookingPayment()` to update booking status, send emails, regenerate iCal
4. Redirects to `payment/success/{payment_id}`

### Path C — PayPal (Legacy for local properties)
1. Guest visits `booking/payment/paypal/{id}` → `PaypalController@index`
   - If setting says "stripe", redirects to Stripe flow
2. PayPal processes payment client-side
3. Return to `PaypalController@indexPost` with `st=COMPLETED`
   - Creates `Payment` record
   - Same `finalEmailAndUpdateBookingPayment()` call

### Split Payment / Partial Payments
- `BookingRequest.total_payment` defines how many installments (1, 2, or 3)
- `BookingRequest.amount_data` stores JSON array of installment amounts with status
- `how_many_payment_done` tracks completed installments
- Reminder emails sent for unpaid installments

---

## 7. Booking Flow

### Guest-Facing Flow (Guesty Properties)

```
1. Browse property → /property-slug (singleGuesty.blade.php)
2. Select dates + guests → AJAX quote request
   └── checkAjaxGetQuoteData → GuestyApi::getQuoteNewNew()
3. Review quote → Submit "Get Quote" form
   └── dynamicDataCategory with templete="get-quote"
4. Fill personal details → saveBookingData
   ├── Create BookingRequest
   ├── Create Guesty Guest
   ├── Create Guesty Reservation (status: inquiry)
   └── Redirect to rental agreement
5. Sign rental agreement → rentalAggrementDataSave
   ├── Capture signature (base64 → PNG)
   ├── Upload ID image
   └── Update BookingRequest
6. Payment page → updatepaymentBookingData
   ├── Tokenize card via Guesty Pay
   ├── Confirm reservation in Guesty
   └── Attach payment method
7. Success page → payment/success/{id}
```

### Admin-Managed Flow (Local Properties)

```
1. Admin creates BookingRequest manually
   └── Sets booking_type_admin = "invoice" or other
2. If "invoice" → Admin confirms booking
   └── Sends payment request email to guest
3. Guest receives email with payment link
4. Guest signs rental agreement
5. Guest pays via Stripe or PayPal
6. System confirms booking, sends confirmation emails
```

### Booking Statuses
- `booked` — Initial state (admin-created)
- `rental-aggrement` — Admin accepted, awaiting rental agreement
- `rental-aggrement-success` — Rental agreement signed
- `booking-confirmed` — Payment received, fully confirmed
- `booking-cancel` — Cancelled
- `inquiry` (Guesty) — Initial Guesty reservation
- `confirm` (Guesty) — Confirmed in Guesty

---

## 8. Admin Features

Accessed via `/client-login/` with auth middleware. Uses **AdminLTE** admin template.

| Feature | Controller | Description |
|---|---|---|
| **Dashboard** | `DashboardController` | Redirects to Guesty properties list |
| **Guesty Properties** | `GuestyPropertyController` | View/edit synced properties, assign locations |
| **Local Properties** | `PropertyController` | Full CRUD + gallery, amenities, rooms, fees, rates, calendar |
| **Property Calendar** | `PropertyCalendarController` | iCal import/export, view blocked dates |
| **Property Rates** | `PropertyRateController` | Date-based pricing (per-day, weekday-specific) |
| **Booking Enquiries** | `BookingRequestController` | View/create/edit/cancel bookings, confirm & send payment emails |
| **CMS Pages** | `CmsController` | Manage static pages |
| **Blogs** | `BlogController` + `BlogCategoryController` | Blog post CRUD + categories |
| **Landing Pages** | `LandingCmsController` | Marketing landing page CRUD |
| **SEO Pages** | `SeoCmsController` | Vacation/SEO page CRUD |
| **Locations** | `LocationController` | Location/town management |
| **Attractions** | `AttractionController` + `AttractionCategoryController` | Attraction CRUD |
| **Sliders** | `SliderController` | Homepage slider management |
| **Galleries** | `GalleryController` | Site gallery management |
| **FAQs** | `FaqController` | FAQ management |
| **Testimonials** | `TestimonialController` | Review/testimonial management |
| **Team** | `OurTeamController` | Team member profiles |
| **Clients** | `OurClientController` | Client logo management |
| **Services** | `ServiceController` | Service listing management |
| **Newsletters** | `NewsLetterController` | Subscriber list |
| **Contact Enquiries** | `ContactusRequestController` | Contact form submissions |
| **Email Templates** | `EmailTempleteController` | Email body/subject templates |
| **Coupons** | `CouponController` | Discount coupon management |
| **Welcome Packages** | `WelcomePackageController` | Welcome package content |
| **Onboarding** | `OnboardingRequestController` | Owner onboarding submissions |
| **Property Mgmt Requests** | `PropertyManagementRequestController` | Property management leads |
| **Maximize Assets** | `MaximizeAssetController` | "Maximize your asset" section |
| **Users** | `UserController` | Admin user management |
| **Settings** | `DashboardController@setting` | Key-value site settings (all stored in `basic_settings`) |
| **Media Center** | `DashboardController@mediaCenter` | File upload manager |
| **Password Change** | `DashboardController@changePassword` | Admin password change |
| **Bulk Delete** | `DashboardController@multipleDelete` | Multi-select delete across models |
| **Export** | `DashboardController@exportData` | CSV export via Maatwebsite Excel |
| **Copy/Duplicate** | Various `copyData` methods | Duplicate records (properties, blogs, etc.) |

---

## 9. User Features (Public / Guest)

| Feature | Route | Description |
|---|---|---|
| **Browse Properties** | `/{seo_url}` (GuestyProperty match) | Property detail page |
| **Properties by Location** | `/properties/location/{seo_url}` | Filter by location |
| **Search Availability** | AJAX on property page | Date/guest picker with live quote |
| **Get Quote** | `/get-quote` (CMS template) | Full quote page with breakdown |
| **Book Property** | `/save-booking-data` (POST) | Create booking |
| **Rental Agreement** | `/booking/rental-aggrement/{id}` | Sign rental agreement online |
| **Make Payment** | `/get-quote-after/{id}` | Enter card and pay via Guesty Pay |
| **Payment (Stripe)** | `/booking/payment/{id}` | Direct Stripe payment |
| **Payment (PayPal)** | `/booking/payment/paypal/{id}` | PayPal payment |
| **Booking Preview** | `/booking/preview/{id}` | View booking details |
| **Payment Receipt** | `/payment/success/{id}` | Success/confirmation page |
| **Browse Attractions** | `/attractions/detail/{seo_url}` | Attraction detail |
| **Attractions by Location** | `/attractions/location/{seo_url}` | Attractions in a location |
| **Attractions by Category** | `/attractions/category/{seo_url}` | Attractions by category |
| **Read Blog** | `/blog/{seo_url}` | Single blog post |
| **Blog by Category** | `/blogs/category/{seo_url}` | Blog listing by category |
| **Vacation Pages** | `/vacation/{seo_url}` | SEO vacation pages |
| **Contact Form** | `/contact-post` (POST) | Submit contact enquiry |
| **Property Mgmt Form** | `/property-management-post` (POST) | Submit property mgmt interest |
| **Newsletter** | `/newsletter-post` (POST) | Subscribe email |
| **Submit Review** | `/review-submit` (POST) | Submit guest testimonial |
| **Onboarding Form** | `/onboarding-post` (POST) | Owner onboarding submission |
| **Meet the Team** | `/meet-the-team/{seo_url}` | Team member profile |
| **Sitemap** | `/sitemap.xml` | XML sitemap |
| **CMS Pages** | `/{seo_url}` | Dynamic static pages |
| **Landing Pages** | `/{seo_url}` (LandingCms match) | Marketing landing pages |

---

## 10. Background Jobs / Cron / Webhooks

### Cron Jobs (via URL-triggered endpoint)

All cron tasks are triggered by a single entry point: `GET /set-cron-job` → `ICalController@setCronJob`

This calls four sub-tasks sequentially:

| Task | Method | Description |
|---|---|---|
| **Calendar Refresh** | `refresshCalendar1()` → `LiveCart::allIcalImportListRefresh()` | Re-import all iCal feeds, regenerate local iCal export files |
| **Payment Reminders** | `sendReminderPackage1()` | Send reminder emails for partially-paid bookings based on configurable day offsets (`second_how_many_days`, `third_how_many_days`, `second_third_how_many_days`) |
| **Welcome Packages** | `sendWelcomePackage1()` | Send welcome package emails N days before check-in (`welcome_package_send_day`) |
| **PriceLabs Sync** | `setPriceLab1()` | Fetch nightly prices from PriceLabs API and update `PropertyRate` table |

### Additional Scheduled URLs

| URL | Method | Description |
|---|---|---|
| `GET /send-review-email` | `ICalController@sendReviewEmail` | Send review request emails N days after checkout |
| `GET /send-welcome-packages` | `ICalController@sendWelcomePackage` | Manual trigger for welcome packages |
| `GET /send-reminder-email` | `ICalController@sendReminderPackage` | Manual trigger for payment reminders |
| `GET /refresh-calendar-data` | `ICalController@refresshCalendar` | Manual calendar refresh |
| `GET /set-pricelab` | `ICalController@setPriceLab` | Manual PriceLabs sync |

### Data Sync URLs (Guesty)

| URL | Method | Description |
|---|---|---|
| `GET /set-getPropertyData` | `PageController@getPropertyData` → `GuestyApi::getPropertyData()` | Full property sync from Guesty |
| `GET /set-getBookingData` | `PageController@getBookingData` → `GuestyApi::getBookingData()` | Full reservation sync from Guesty (truncates + reimports) |
| `GET /get-reviews-data` | `PageController@getReviewData` → `GuestyApi::getReviewData()` | Full review sync from Guesty |
| `GET /set-token` | `PageController@getToken` → `GuestyApi::getToken()` | Refresh Open API token |
| `GET /getBookingToken` | `PageController@getBookingToken` → `GuestyApi::getBookingToken()` | Refresh Booking API token |

> **Warning:** None of these cron/sync endpoints are protected by authentication. They are publicly accessible GET routes.

### No Laravel Scheduler
- `Console\Kernel::schedule()` is empty — no `php artisan schedule:run` tasks
- All scheduled tasks appear to be triggered by external cron hitting URLs

### No Queued Jobs
- `QUEUE_CONNECTION=sync` in `.env` — all work runs synchronously
- No Job classes exist in the project

---

## 11. API Endpoints

### REST API (`routes/api.php`)
Only the default Sanctum user endpoint exists:
```
GET /api/user (auth:sanctum)
```

### AJAX Endpoints (in `routes/web.php`)

| Method | URL | Purpose |
|---|---|---|
| POST | `/checkajax-get-quote` | Get quote for Guesty property (frontend AJAX) |
| POST | `/admin-checkajax-get-quote` | Get quote for local property (admin AJAX) |
| POST | `/admin-checkajax-get-quote-edit` | Get quote for local property edit (admin AJAX) |
| POST | `/save-booking-data` | Save booking (frontend form) |
| POST | `/update-payment-booking-data/{id}` | Process Guesty Pay payment |
| POST | `/rental-aggrement-data-save` | Save rental agreement |
| POST | `/payment_init` | Create Stripe PaymentIntent |
| GET | `/getIntendentData` | Create Stripe SetupIntent |
| POST | `/client-login/get-checkin-checkout-data-gaurav` | Get blocked dates for calendar (admin AJAX) |
| POST | `/client-login/getSubLocationList` | Get sub-locations by parent (admin AJAX) |
| GET | `/reload-captcha` | Reload captcha image |

### iCal Endpoints

| Method | URL | Purpose |
|---|---|---|
| GET | `/ical/{id}` | Generate and serve iCal file for property |

---

## 12. Blade Frontend Structure

### Layout
- Master layout: `resources/views/front/layouts/master.blade.php`
- Partials: `head.blade.php`, `header.blade.php`, `footer.blade.php`, `css.blade.php`, `js.blade.php`, `banner.blade.php`
- Admin layout: `resources/views/admin/layouts.blade.php` (AdminLTE)

### Frontend Pages (`front/`)

| Directory | Templates | Purpose |
|---|---|---|
| `static/` | `home`, `about`, `about-owner`, `contact`, `faq`, `gallery`, `blogs`, `property-list`, `property-management`, `reviews`, `services`, `get-quote`, `onboarding`, `prearrival`, `privacy`, `partner`, `map`, `common`, `test` | CMS-driven static pages (mapped by `Cms.templete` field) |
| `property/` | `singleGuesty`, `single`, `location`, `ajax-gaurav-*` | Property detail, location listing, AJAX quote fragments |
| `booking/` | `preview`, `rentalAggrementBooking`, `second-step-get-quote` | Booking flow pages |
| `booking/payment/` | `stripe`, `paypal`, `first-preview` | Payment pages |
| `group/` | `single`, `category` | Blog single post, blog category listing |
| `attractions/` | `single`, `category`, `location` | Attraction pages |
| `landing-pages/` | `common` | Landing page template |
| `seo-pages/` | — | Vacation/SEO page templates |
| `meet-the-teams/` | `common` | Team member profile page |
| `errors/` | — | Error pages |

### Admin Pages (`admin/`)
Each admin module has its own directory with `index`, `create`, `edit` blade files, following AdminLTE patterns. 34 admin module directories.

### Email Templates (`mail/`)
16 blade email templates for booking confirmations, cancellations, reminders, welcome packages, reviews, and rental agreements.

---

## 13. Risk Areas While Refactoring

### HIGH RISK

| Area | Description |
|---|---|
| **PageController is a god controller** | 884 lines, handles homepage, property detail, blog, attractions, booking creation, payment processing, rental agreement, quote generation, Guesty data sync, sitemap, onboarding, contact, newsletter — basically everything public. Splitting this requires careful route mapping. |
| **Two parallel property systems** | `Property` (local) and `GuestyProperty` (Guesty) coexist with different schemas, different booking flows, different payment flows. BookingRequest.property_id can point to either table depending on context. This is extremely fragile. |
| **BookingRequest is overloaded** | 80+ fillable fields mixing local booking data, Guesty booking data, card details (!!), payment status, rental agreement status, email flags. The same model serves fundamentally different flows. |
| **GuestyApi helper is 883 lines** | Contains all external API calls with raw cURL/Guzzle, duplicated methods (e.g., `getQuoteNewNew` appears twice with different signatures), hardcoded URLs, no retry logic, no rate limiting. Splitting requires mapping all callers. |
| **Unprotected cron/sync endpoints** | `/set-cron-job`, `/set-getPropertyData`, `/set-getBookingData`, `/get-reviews-data`, `/set-token`, `/getBookingToken` are all public GET routes. Anyone can trigger data syncs or token refreshes. |
| **Sensitive data stored in plain text** | `OnboardingRequest` stores SSN, card numbers, bank routing numbers, CVV in database. `BookingRequest` stores card_number, card_cvv, card_expiry_month, card_expiry_year. |
| **No model relationships defined** | Only `Blog ↔ BlogCategory` has Eloquent relationships. All other queries are manual `::where()`. Changing table structure or IDs could break queries silently. |
| **No request validation classes** | All validation is inline in controllers (`Validator::make`), often with empty rules (`[]`). Many endpoints accept `$request->all()` directly into `::create()`. Mass assignment risk despite `$fillable`. |

### MEDIUM RISK

| Area | Description |
|---|---|
| **Duplicated code** | `ICalController` has paired methods (e.g., `sendWelcomePackage` + `sendWelcomePackage1`, `setPriceLab` + `setPriceLab1`) — one returns redirect, one doesn't. Same logic duplicated. |
| **Helper classes as pseudo-services** | `Helper`, `ModelHelper`, `LiveCart`, `MailHelper`, `GuestyApi` are large classes accessed via Facades. They mix concerns (UI rendering, business logic, API calls, DB queries). |
| **Payment flow fragility** | Three different payment paths with different models, different success URLs, different email triggers. `CommonController@showReceipt1` uses booking ID, `showReceipt` uses payment ID. |
| **JSON data stored in columns** | `BookingRequest.amount_data`, `BookingRequest.before_total_fees`, `BookingRequest.after_total_fees`, `GuestyProperty.all_data`, `GuestyProperty.pictures`, etc. Business logic parses these inline. |
| **Email template system** | `MailHelper::emailSender` uses regex replacement (`preg_replace`) with 30+ individual field checks. Adding new placeholders requires modifying the helper. |
| **No database migrations** | Only 1 migration file exists (`guesty_availablity_prices`). All other tables appear to have been created manually or via SQL dumps. Schema changes cannot be tracked or replayed. |
| **Booking status as magic strings** | Statuses like `"booking-confirmed"`, `"booking-confirmed123"`, `"rental-aggrement"`, `"inquiry"`, `"confirm"` are scattered as string literals. `"booking-confirmed123"` appears to be a disabled/debugging status. |
| **LiveCart helper complexity** | `iCalDataCheckInCheckOut` and `iCalDataCheckInCheckOutCheckinCheckout` are 100+ line methods combining data from iCal events, Guesty bookings, local booking requests, and availability prices to calculate blocked dates. |

### LOW RISK

| Area | Description |
|---|---|
| **OptimizeMiddleware disabled** | All HTML minification code is commented out, middleware is a pass-through. Can be safely removed or replaced. |
| **No tests** | Test directory exists but only has boilerplate (`TestCase.php`, `CreatesApplication.php`). No feature or unit tests. |
| **No API versioning** | `api.php` routes are unused. All "API" functionality runs through web routes. |
| **German localization fields** | Blog/BlogCategory have `_ger` suffixed fields. No actual localization framework used. |
| **AdminLTE admin panel** | Standard AdminLTE integration via `jeroennoten/laravel-adminlte` package. Admin views follow consistent patterns. |
| **`public $fillable` instead of `protected $fillable`** | Most models use `public` visibility. Not a security issue but violates convention. |
| **Queue is sync** | All email sending and API calls happen synchronously in the request cycle. Moving to async queues will improve UX but requires testing each flow. |
| **No service container bindings** | Facades bind to helper classes directly. No interfaces, no dependency injection. Introducing DI will require updating all Facade references. |

---

## Appendix: Facade → Helper Class Mapping

| Facade | Helper Class | Purpose |
|---|---|---|
| `GuestyApi` | `App\Helper\GuestyApi` | All Guesty PMS API interactions |
| `Helper` | `App\Helper\Helper` | Pricing calculations, availability checks, utility functions |
| `ModelHelper` | `App\Helper\ModelHelper` | Settings retrieval, model queries, payment finalization |
| `MailHelper` | `App\Helper\MailHelper` | Email sending (template-based and controller-based) |
| `LiveCart` | `App\Helper\LiveCart` | iCal management, blocked date calculation, calendar operations |

## Appendix: Settings (basic_settings keys referenced in code)

| Key | Usage |
|---|---|
| `stripe_secret_key` | Stripe API secret key |
| `which_payment_gateway` | Determines Stripe vs PayPal redirect |
| `payment_currency` | Currency symbol for display |
| `OpenClientid` / `OpenClientSecretkey` | Guesty Open API OAuth credentials |
| `BookingClientid` / `BookingClientSecretkey` | Guesty Booking API OAuth credentials |
| `pricelab_access_token` | PriceLabs API key |
| `g_captcha_enabled` | reCAPTCHA toggle |
| `google_captcha_site_key` / `google_captcha_secret_key` | reCAPTCHA keys |
| `blocked_email` | Comma-separated blocked email list |
| `contact_us_receiving_mail` | Admin email for contact/onboarding/newsletter |
| `payment_receiving_mail` | Admin email for payment confirmations |
| `cancel_receiving_mail` | Admin email for cancellations |
| `rental_aggrement_receiving_mail` | Admin email for rental agreements |
| `welcome_package_receiving_mail` | Admin email for welcome packages |
| `welcome_package_send_day` | Days before check-in to send welcome package |
| `reminder_package_receiving_mail` | Admin email for payment reminders |
| `review_receiving_mail` / `review_send_day` | Review email settings |
| `second_how_many_days` / `third_how_many_days` / `second_third_how_many_days` | Payment reminder day offsets |
| `mail_from` / `mail_from_name` | Email sender identity |
| `favicon` / `header_logo` / `footer_logo` / `ogimage` | Site branding assets |
