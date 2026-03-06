# Bentonville Lodging Co. — Implementation Roadmap

> Incremental rebuild plan. Each phase is **independently deployable and testable**.
> Legacy code continues serving every request until its replacement is wired in.
> Blade views are never changed — route names, view variables, flash messages stay identical.

---

## Model Dependency Graph

```
                     ┌──────────┐
                     │   User   │  (standalone)
                     └──────────┘

                     ┌──────────┐
                     │ Location │  (root entity)
                     └────┬─────┘
          ┌────────────────┼─────────────────────────┐
          ▼                ▼                          ▼
    ┌──────────┐    ┌──────────────┐          ┌────────────────┐
    │ Property │    │GuestyProperty│          │ WelcomePackage │
    └────┬─────┘    └──────┬───────┘          └────────────────┘
         │                  │
         │     ┌────────────┼──────────────────┐
         │     ▼            ▼                  ▼
         │   GuestyPrice  GuestyBooking   GuestyReview
         │   GuestyAvail
         │
    ┌────┼────────┬──────────┬──────────┬──────────┐
    ▼    ▼        ▼          ▼          ▼          ▼
  Gallery Fees  Spaces  AmenityGrp  RateGroup    Room
                          │            │           │
                          ▼            ▼           ▼
                       Amenity       Rate      RoomItem
                                                  │
                                                  ▼
                                            RoomItemImage
         │
    ┌────┼────────┬──────────┬──────────┐
    ▼    ▼        ▼          ▼          ▼
  IcalEvent IcalImport Coupon Testimonial BookingRequest
                                              │
                                              ▼
                                           Payment

  STANDALONE (no FK dependencies):
  BasicSetting, Cms, Slider(→Cms), ContactusRequest,
  EmailTemplete, Faq, Gallery, LandingCms, MaximizeAsset,
  NewsLetter, OnboardingRequest, OurClient, OurTeam,
  PropertyManagementRequest, SeoCms, Service,
  AttractionCategory, Attraction(→Location,Category),
  Blog(→BlogCategory), BlogCategory
```

---

## Phase 0 — Foundation & Infrastructure

**Goal:** Scaffold clean architecture skeleton. Zero user-visible changes.

**Duration:** 3–4 days

### Folder Structure to Create

```
app/
├── Actions/
├── Console/Commands/
├── DTO/
├── Events/
├── Exceptions/Booking/  Payment/  Integration/
├── Http/
│   ├── Controllers/Public/  Payment/  Api/  Admin/
│   └── Requests/Public/  Booking/  Payment/  Admin/
├── Integrations/
│   ├── Guesty/Contracts/
│   ├── PriceLabs/Contracts/
│   ├── Stripe/Contracts/
│   ├── PayPal/Contracts/
│   └── ICal/Contracts/
├── Listeners/
├── Models/                  (existing — untouched)
├── Providers/
├── Repositories/
│   ├── Contracts/
│   └── Eloquent/
├── Services/
│   ├── Admin/
│   ├── Booking/
│   ├── Calendar/
│   ├── Communication/
│   ├── Content/
│   ├── Media/
│   ├── Payment/
│   ├── Property/
│   └── Shared/
├── Support/Traits/
└── View/Composers/
```

### What to Build

| Artifact | Purpose |
|---|---|
| `Repositories/Contracts/BaseRepositoryInterface.php` | Standard CRUD contract: `all`, `find`, `create`, `update`, `delete` |
| `Repositories/Eloquent/BaseRepository.php` | Eloquent implementation — shared by every repository |
| `Services/Shared/CrudService.php` | Generic CRUD orchestrator with `activate`, `deactivate`, `duplicate` — reused by ~15 admin controllers |
| `Services/Media/UploadService.php` | Centralised file upload (replaces `Upload::fileUpload()` helper) |
| `Support/Traits/HasImageUpload.php` | Reusable image handling for services |
| `Support/Traits/HasActivation.php` | Active/deactive toggle logic |
| `Support/Traits/HasDuplication.php` | Record cloning logic |
| `Providers/RepositoryServiceProvider.php` | Binds all Repository interfaces → Eloquent implementations |
| `Providers/IntegrationServiceProvider.php` | Binds all Integration interfaces → concrete classes |
| `Providers/ViewComposerServiceProvider.php` | Replaces `View::share('setting_data')` in `AppServiceProvider::boot()` |
| `View/Composers/SettingComposer.php` | Shares `setting_data` to all views via composer |
| `config/guesty.php` | Guesty API credentials & endpoints (extract from `.env` / hardcoded values) |
| `config/pricelabs.php` | PriceLabs API key & endpoint |
| `config/payment.php` | Stripe & PayPal keys |
| Route splitting | `routes/web/public.php`, `routes/web/admin.php`, `routes/web/booking.php` — included from `web.php` |

### Tests

```
tests/Unit/Support/UploadServiceTest.php               — file upload to public/uploads
tests/Unit/Repositories/BaseRepositoryTest.php          — CRUD against in-memory SQLite
tests/Unit/Services/Shared/CrudServiceTest.php          — activate, deactivate, duplicate
tests/Feature/ViewComposer/SettingComposerTest.php      — setting_data shared to views
```

### Deliverable

Ship this and verify production site works identically — nothing is wired to routes yet.

---

## Phase 1 — Auth & User Module

**Goal:** Secure admin panel with clean auth. First real model under new architecture.

**Duration:** 2 days

### Models

| Model | Table | Notes |
|---|---|---|
| `User` | `users` | Existing. Add `$casts`, clean up fillable |

### Controllers

| Controller | Methods |
|---|---|
| `Admin/UserController` | `index`, `create`, `store`, `edit`, `update`, `destroy` |

### FormRequests

| Class | Key Rules |
|---|---|
| `Admin/UserFormRequest` | name: required, email: required\|email\|unique, password: nullable\|min:8\|confirmed |
| `Admin/PasswordChangeFormRequest` | current_password: required, password: required\|min:8\|confirmed |

### Services

| Class | Methods |
|---|---|
| `Admin/UserService` | `createUser(array)` — bcrypt password; `updateUser(id, array)` — conditional password update; `deleteUser(id)` |

### Repositories

| Interface | Implementation |
|---|---|
| `UserRepositoryInterface` | `Eloquent/UserRepository` |

### Tests

```
tests/Feature/Auth/LoginTest.php                       — login, logout, redirect to admin
tests/Feature/Admin/UserControllerTest.php              — full CRUD with auth middleware
tests/Unit/Services/Admin/UserServiceTest.php           — password hashing, conditional update
```

### Cutover

Replace old `Admin/UserController` import in routes. Blade views, route names, flash messages unchanged.

---

## Phase 2 — Settings, Media & Standalone Content Modules

**Goal:** Rebuild all standalone admin modules (no FK dependencies). Covers ~60% of admin controllers.

**Duration:** 5–7 days

### Models (18 standalone — no FK dependencies on other phases)

| # | Model | Table | Special Handling |
|---|---|---|---|
| 1 | `BasicSetting` | `basic_settings` | Key-value store |
| 2 | `Cms` | `cms` | 23+ image upload fields |
| 3 | `Slider` | `sliders` | FK: `cms_id` → `cms` |
| 4 | `ContactusRequest` | `contactus_requests` | Read-only + delete in admin |
| 5 | `EmailTemplete` | `email_templetes` | Template body with placeholders |
| 6 | `Faq` | `faqs` | Simple CRUD + copy |
| 7 | `Gallery` | `galleries` | CRUD + active/deactive/copy |
| 8 | `MaximizeAsset` | `maximize_assets` | Simple CRUD |
| 9 | `NewsLetter` | `newsletters` | Unique email validation |
| 10 | `OnboardingRequest` | `onboarding_requests` | File uploads (file1, file2) |
| 11 | `OurClient` | `our_clients` | Simple CRUD |
| 12 | `OurTeam` | `our_teams` | SEO URL + profile image |
| 13 | `PropertyManagementRequest` | `property_management_requests` | Simple CRUD |
| 14 | `SeoCms` | `seo_pages` | JSON attraction + video sections |
| 15 | `LandingCms` | `landing_cms` | JSON sections + multiple images |
| 16 | `Service` | `services` | SEO URL unique |
| 17 | `WelcomePackage` | `welcome_packages` | Image + banner (FK `location_id` nullable until Phase 3) |
| 18 | `Testimonial` | `testimonials` | Image + copy (FK `property_id` nullable until Phase 3) |

### Controllers (all `Admin/`)

| Controller | Replaces Legacy | Pattern |
|---|---|---|
| `SettingController` | `DashboardController` (setting/settingPost) | `SettingService` |
| `MediaController` | `DashboardController` (mediaCenter/newFileUploads/mediasDelete/multipleDelete) | `MediaCenterService` |
| `CmsController` | `CmsController` | `CmsService` (multi-image) |
| `SeoCmsController` | `SeoCmsController` | `SeoCmsService` (JSON sections) |
| `LandingCmsController` | `LandingCmsController` | `LandingCmsService` (JSON) |
| `SliderController` | `SliderController` | `CrudService` (generic) |
| `FaqController` | `FaqController` | `CrudService` |
| `GalleryController` | `GalleryController` | `CrudService` |
| `TestimonialController` | `TestimonialController` | `CrudService` |
| `ClientController` | `OurClientController` | `CrudService` |
| `TeamController` | `OurTeamController` | `CrudService` + custom validation |
| `ServiceController` | `ServiceController` | `CrudService` |
| `MaximizeAssetController` | `MaximizeAssetController` | `CrudService` |
| `EmailTemplateController` | `EmailTempleteController` | `CrudService` |
| `ContactRequestController` | `ContactusRequestController` | `CrudService` (read-only + delete) |
| `NewsletterController` | `NewsLetterController` | `CrudService` |
| `OnboardingRequestController` | `OnboardingRequestController` | `CrudService` |
| `PropertyManagementRequestController` | `PropertyManagementRequestController` | `CrudService` |
| `WelcomePackageController` | `WelcomePackageController` | `CrudService` |
| `CkeditorController` | `CkeditorController` | Direct (2 methods) |

### FormRequests (`Admin/`)

```
CmsFormRequest.php              — seo_url unique, name required
SeoCmsFormRequest.php           — seo_url unique, name required
LandingCmsFormRequest.php       — seo_url unique
ServiceFormRequest.php          — seo_url unique
TeamFormRequest.php             — seo_url unique, first_name, last_name, email required
NewsletterFormRequest.php       — email required|unique
SettingFormRequest.php          — dynamic rules per setting group
PasswordChangeFormRequest.php   — current + new password
SliderFormRequest.php           — title required
```

### Services

| Class | Responsibility |
|---|---|
| `Shared/CrudService` | Generic CRUD for ~12 simple controllers (configure model class, handles image upload via `UploadService`, activate/deactivate/duplicate) |
| `Content/CmsService` | Multi-image upload handling (23+ fields), create, update |
| `Content/SeoCmsService` | JSON `attraction_section` + `video_section` assembly with per-item image uploads |
| `Content/LandingCmsService` | Same JSON pattern as SeoCms |
| `Communication/EmailService` | Template rendering with 30+ placeholder replacement (`{name}`, `{email}`, `{check_in}`, etc.); send via SMTP. Replaces `MailHelper` |
| `Media/MediaCenterService` | List files, upload, single/bulk delete |
| `Media/UploadService` | Central file upload to `public/uploads/{folder}` |
| `Admin/SettingService` | Get/set `BasicSetting` key-value pairs, handle logo/favicon/OG image uploads |

### Repositories

| Interface | Notes |
|---|---|
| `SettingRepositoryInterface` | `getValue(key)`, `setValue(key, value)`, `getAll()` |
| `CmsRepositoryInterface` | `findBySeoUrl(slug)` |
| `EmailTemplateRepositoryInterface` | `findByType(slug)` |
| `GenericCrudRepository` | Covers simple models via configurable model class |

### Tests

```
tests/Unit/Services/Shared/CrudServiceTest.php                — full generic lifecycle
tests/Unit/Services/Content/CmsServiceTest.php                 — multi-image create/update
tests/Unit/Services/Content/SeoCmsServiceTest.php              — JSON section assembly
tests/Unit/Services/Communication/EmailServiceTest.php         — placeholder replacement
tests/Feature/Admin/CmsControllerTest.php                      — representative sample
tests/Feature/Admin/GalleryControllerTest.php                  — activate/deactivate/copy
tests/Feature/Admin/SettingControllerTest.php                  — save/load cycle
```

### Cutover

Swap controller imports in route file one by one. Each module can go live independently.

---

## Phase 3 — Location & Property Module (Core Entity)

**Goal:** Rebuild the central `Property` and `Location` models with all sub-entities. This is the heart of the system.

**Duration:** 7–10 days

### Models (13 models)

| # | Model | Table | FK Dependencies |
|---|---|---|---|
| 1 | `Location` | `locations` | None (root) |
| 2 | `Property` | `properties` | `location_id` → locations |
| 3 | `PropertyGallery` | `property_galleries` | `property_id` → properties |
| 4 | `PropertyFee` | `property_fees` | `property_id` → properties |
| 5 | `PropertySpace` | `property_spaces` | `property_id` → properties |
| 6 | `PropertyAmenityGroup` | `property_amenity_groups` | `property_id` → properties |
| 7 | `PropertyAmenity` | `property_amenities` | `property_amenity_id` → groups |
| 8 | `PropertyRateGroup` | `properties_rates_group` | `property_id` → properties |
| 9 | `PropertyRate` | `property_rates` | `property_id` + `rate_group_id` |
| 10 | `PropertyRoom` | `property_rooms` | `property_id` → properties |
| 11 | `PropertyRoomItem` | `property_room_items` | `room_id` → rooms |
| 12 | `PropertyRoomItemImage` | `property_room_item_images` | `sub_room_id` → room_items |
| 13 | `Coupon` | `coupons` | `property_id` → properties |

**Add missing Eloquent relationships to ALL models:**

```php
// Property.php — example
public function location(): BelongsTo     { return $this->belongsTo(Location::class); }
public function galleries(): HasMany      { return $this->hasMany(PropertyGallery::class); }
public function fees(): HasMany           { return $this->hasMany(PropertyFee::class); }
public function spaces(): HasMany         { return $this->hasMany(PropertySpace::class); }
public function amenityGroups(): HasMany   { return $this->hasMany(PropertyAmenityGroup::class); }
public function rateGroups(): HasMany     { return $this->hasMany(PropertyRateGroup::class); }
public function rooms(): HasMany          { return $this->hasMany(PropertyRoom::class); }
public function testimonials(): HasMany   { return $this->hasMany(Testimonial::class); }
public function coupons(): HasMany        { return $this->hasMany(Coupon::class); }
public function icalImports(): HasMany    { return $this->hasMany(IcalImportList::class); }
public function icalEvents(): HasMany     { return $this->hasMany(IcalEvent::class, 'ppp_id'); }
```

### Controllers

**Admin:**

| Controller | Methods | Notes |
|---|---|---|
| `Admin/PropertyController` | index, create, store, edit, update, destroy, active, deactive, copyData, updateCaptionSort, imageDeleteAsset, deletePropertySpace | Complex: galleries, fees, spaces in single transaction |
| `Admin/RateController` | index, create, store, edit, update, destroy, copyData | Date overlap validation, day-of-week pricing |
| `Admin/RoomController` | index, create, store, edit, update, destroy, active, deactive, copyData | Nested under property |
| `Admin/RoomItemController` | Full CRUD + active/deactive/copy | Nested under property→room |
| `Admin/AmenityGroupController` | Full CRUD + active/deactive/copy | Nested under property |
| `Admin/AmenityController` | Full CRUD + active/deactive/copy | Nested under property→group |
| `Admin/CouponController` | Full CRUD | Property-specific coupons |

**Public:**

| Controller | Methods | Replaces |
|---|---|---|
| `Public/PropertyController` | `show(seo_url)`, `byLocation(seo_url)` | `PageController@propertyDetail`, `PageController@propertyLocation` |

### FormRequests

```
Admin/PropertyFormRequest.php        — seo_url unique, name required, location_id exists
Admin/RateGroupFormRequest.php       — start_date + end_date required (overlap check in Service)
Admin/RoomFormRequest.php            — room_title required
Admin/AmenityFormRequest.php         — name required
Admin/AmenityGroupFormRequest.php    — name required
Admin/CouponFormRequest.php          — code required, type in [percentage,fixed], property_id exists
```

### Services

| Class | Key Methods |
|---|---|
| `Property/PropertyService` | `create(data)` — handles galleries (bulk images), fees (array), spaces (array) in DB transaction; `update(id, data)` — same with image replacement; `delete(id)` — cascade; `activate/deactivate/duplicate` |
| `Property/RateService` | `createRateGroup(propertyId, data)` — validates date overlap, generates per-day `PropertyRate` records; `updateRateGroup(id, data)` — re-generates; `deleteRateGroup(id)` — cascade |
| `Property/RoomService` | CRUD for rooms + room items; cascade deletes |
| `Property/AmenityService` | CRUD for groups + amenities; cascade delete on group destroy |

### Repositories

| Interface | Key Methods |
|---|---|
| `PropertyRepositoryInterface` | `findBySeoUrl()`, `getByLocation()`, `getSelectList()`, `withAllRelations()` |
| `PropertyRateGroupRepositoryInterface` | `findOverlapping(propertyId, startDate, endDate, excludeId?)` |
| `LocationRepositoryInterface` | `findBySeoUrl()`, `getSelectList()`, `getSubLocations(parentId)` |
| `CouponRepositoryInterface` | `findByCode(code)`, `findByProperty(propertyId)` |

### DTOs

```
PropertyData.php        — typed create/update payload
PricingData.php         — rate breakdown (nightly rates, fees, taxes)
RateCalendarData.php    — per-day price for frontend calendar
```

### Tests

```
tests/Unit/Services/Property/PropertyServiceTest.php           — CRUD with sub-entities
tests/Unit/Services/Property/RateServiceTest.php               — overlap detection, day-of-week pricing, per-day generation
tests/Unit/Services/Property/RoomServiceTest.php               — nested CRUD
tests/Unit/Services/Property/AmenityServiceTest.php            — cascade delete
tests/Unit/Repositories/PropertyRepositoryTest.php             — complex queries (RefreshDatabase)
tests/Unit/Repositories/PropertyRateGroupRepositoryTest.php    — overlap query
tests/Feature/Admin/PropertyControllerTest.php                 — full create with galleries/fees/spaces
tests/Feature/Admin/RateControllerTest.php                     — create with overlap rejection
tests/Feature/Public/PropertyControllerTest.php                — detail page, location listing
```

### Cutover

Replace admin property controller imports. Replace `PageController@propertyDetail` and `PageController@propertyLocation`. Route names stay identical.

---

## Phase 4 — Attractions & Blog Module

**Goal:** Rebuild content modules that depend on Location (Attractions) or have their own hierarchy (Blog).

**Duration:** 3–4 days

### Models (4 models)

| Model | Table | FK Dependencies |
|---|---|---|
| `AttractionCategory` | `attraction_categories` | `category_id` → self (parent) |
| `Attraction` | `attractions` | `location_id` → locations, `category_id` → attraction_categories |
| `BlogCategory` | `blog_categories` | None |
| `Blog` | `blogs` | `blog_category_id` → blog_categories |

### Controllers

**Admin:**

| Controller | Methods | Notes |
|---|---|---|
| `Admin/AttractionController` | CRUD | seo_url unique, image upload |
| `Admin/AttractionCategoryController` | CRUD | Old image cleanup on re-upload |
| `Admin/BlogController` | CRUD + active/deactive/copyData | |
| `Admin/BlogCategoryController` | CRUD + copyData | |

**Public:**

| Controller | Methods | Replaces |
|---|---|---|
| `Public/AttractionController` | `show`, `byLocation`, `byCategory` | `PageController@attractionSingle/Location/Category` |
| `Public/BlogController` | `show`, `byCategory` | `PageController@blogSingle`, `categoryData` |

### FormRequests

```
Admin/AttractionFormRequest.php           — seo_url unique, name + category_id required
Admin/AttractionCategoryFormRequest.php   — name required
Admin/BlogFormRequest.php                 — seo_url unique, title required
Admin/BlogCategoryFormRequest.php         — title required
```

### Services

| Class | Responsibility |
|---|---|
| `Content/AttractionService` | CRUD + old image deletion on category update |
| `Content/BlogService` | CRUD + activate/deactivate/duplicate |

### Repositories

| Interface | Key Methods |
|---|---|
| `AttractionRepositoryInterface` | `findBySeoUrl()`, `getByLocation()`, `getByCategory()` |
| `BlogRepositoryInterface` | `findBySeoUrl()`, `getByCategory()`, `paginated()` |

### Tests

```
tests/Unit/Services/Content/AttractionServiceTest.php
tests/Unit/Services/Content/BlogServiceTest.php
tests/Feature/Admin/AttractionControllerTest.php
tests/Feature/Admin/BlogControllerTest.php
tests/Feature/Public/AttractionControllerTest.php        — detail, location, category pages
tests/Feature/Public/BlogControllerTest.php               — single post, category listing
```

### Cutover

Swap admin + public controller imports. Route names unchanged.

---

## Phase 5 — Guesty PMS Integration Module

**Goal:** Extract the 883-line `GuestyApi` monolith into clean, testable integration classes. No business logic changes.

**Duration:** 5–7 days

### Models (5 models)

| Model | Table | FK Dependencies |
|---|---|---|
| `GuestyProperty` | `guesty_properties` | `location_id`, `sub_location_id` → locations |
| `GuestyAvailabilityPrice` | `guesty_availablity_prices` | `listingId` → guesty_properties._id |
| `GuestyPropertyBooking` | `guesty_property_bookings` | `listingId` → guesty_properties._id |
| `GuestyPropertyPrice` | `guesty_property_prices` | `property_id` → guesty_properties |
| `GuestyPropertyReview` | `guesty_property_reviews` | `listingId` → guesty_properties._id |

### Integration Classes (core deliverable)

| Class | Extracted From (`GuestyApi` helper) | Key Methods |
|---|---|---|
| `Guesty/GuestyClient` | `getToken()`, `getBookingToken()` | `getOpenApiToken()`, `getBookingEngineToken()`, `authenticatedRequest()` — with `Cache::remember()` for tokens |
| `Guesty/GuestyPropertyApi` | `getPropertyData()`, `getAVailablityDataData()`, `getCalFeeData()`, `getAdditionalFeeData()`, `getSearchAvailability()` | Clean typed methods returning DTOs |
| `Guesty/GuestyBookingApi` | `newBookingCreate()`, `confirmBooking()`, `getBookingData()`, `getLimitBookingData()`, `setBookingData()` | Reservation lifecycle |
| `Guesty/GuestyGuestApi` | `createGuest()`, `getGuestData()` | Guest CRUD |
| `Guesty/GuestyPaymentApi` | `paymentAttached()`, `getBookingPaymentid()`, `paidAPi()` | Payment operations |
| `Guesty/GuestyQuoteApi` | `getQuoteNewNew()`, `getQuouteNew()`, `getQuouteNewNew()` | Quote generation |
| `Guesty/GuestyReviewApi` | `getReviewData()` | Review retrieval |

**Each class gets a corresponding `Contracts/` interface for DI and mocking.**

### Controllers

| Controller | Methods | Notes |
|---|---|---|
| `Admin/GuestyPropertyController` | edit, update, getSubLocationList (AJAX) | Edit-only (no create/delete — managed in Guesty) |
| `Api/GuestyTokenController` | `openApiToken()`, `bookingToken()` | Replaces `PageController@getToken`, `getBookingToken` |
| `Api/PropertyApiController` | `show()`, `reviews()` | Replaces `PageController@getPropertyData`, `getReviewData` |

### FormRequests

```
Admin/GuestyPropertyFormRequest.php
```

### Services

| Class | Responsibility |
|---|---|
| `Property/GuestyPropertyService` | Local override management: banner, booklet, feature image, OG image, rental agreement |

### Repositories

| Interface | Key Methods |
|---|---|
| `GuestyPropertyRepositoryInterface` | `findByListingId(_id)`, `getSelectList()`, `withLocation()` |

### DTOs

```
Guesty/GuestyTokenData.php            — token string + expires_at
Guesty/GuestyReservationData.php       — reservation details
Guesty/GuestyQuoteData.php             — price breakdown from quote API
```

### Tests (critical — most error-prone extraction)

```
tests/Unit/Integrations/Guesty/GuestyClientTest.php            — token caching, refresh, retry on 401
tests/Unit/Integrations/Guesty/GuestyPropertyApiTest.php        — Http::fake() responses
tests/Unit/Integrations/Guesty/GuestyBookingApiTest.php         — create/confirm/cancel faked
tests/Unit/Integrations/Guesty/GuestyPaymentApiTest.php         — attach/paid faked
tests/Unit/Integrations/Guesty/GuestyQuoteApiTest.php           — multiple quote methods faked
tests/Feature/Admin/GuestyPropertyControllerTest.php
tests/Feature/Api/GuestyTokenControllerTest.php
```

### Cutover

1. Register interfaces in `IntegrationServiceProvider`
2. Replace `GuestyApi::` facade calls in any controllers already migrated
3. Legacy code continues using old helper until Phase 7 wires everything

---

## Phase 6 — iCal, PriceLabs & Calendar Module

**Goal:** Extract calendar sync from 763-line `LiveCart` helper and `ICalController` into clean services + Artisan commands. Replace unprotected cron URLs.

**Duration:** 4–5 days

### Models (2 models)

| Model | Table | FK Dependencies |
|---|---|---|
| `IcalEvent` | `ical_events` | `ppp_id` → properties |
| `IcalImportList` | `ical_import_list` | `property_id` → properties |

### Integration Classes

| Class | Extracted From | Purpose |
|---|---|---|
| `ICal/ICalParser` | `LiveCart::refreshIcalData()` string parsing | Parse `.ics` URL → array of events |
| `ICal/ICalExporter` | `LiveCart::getFileIcalFileData()` | Generate `.ics` content from bookings |
| `PriceLabs/PriceLabsClient` | `ICalController::setPriceLab()` HTTP call | Fetch rates from `api.pricelabs.co` |

### Services

| Class | Key Methods |
|---|---|
| `Calendar/IcalService` | `importFeed(icalLink, propertyId)`, `refreshAllFeeds()`, `refreshSingleFeed(importId)`, `isAvailable(propertyId, checkIn, checkOut)`, `exportPropertyCalendar(propertyId)` |
| `Calendar/PriceLabsSyncService` | `syncRates()` — fetch PriceLabs, update `PropertyRate`/`PropertyRateGroup` |

### Controllers

| Controller | Notes |
|---|---|
| `Admin/CalendarController` | iCal event list, import list, add/refresh/delete feeds, self-refresh |

### Artisan Commands (replace unprotected cron URLs)

| Command | Schedule | Replaces |
|---|---|---|
| `RefreshIcalCommand` | `->hourly()` | `GET /set-cron-job` |
| `SyncPriceLabsCommand` | `->daily()` | `GET /set-pricelab` |

### Repositories

| Interface | Key Methods |
|---|---|
| `IcalEventRepositoryInterface` | `getByProperty(id)`, `deleteByIcalLink(link)`, `getConflicting(propertyId, start, end)` |
| `IcalImportListRepositoryInterface` | `getByProperty(id)`, `getAll()` |

### Tests

```
tests/Unit/Integrations/ICal/ICalParserTest.php                   — parse sample .ics content
tests/Unit/Integrations/ICal/ICalExporterTest.php                  — generate valid iCal output
tests/Unit/Integrations/PriceLabs/PriceLabsClientTest.php          — Http::fake()
tests/Unit/Services/Calendar/IcalServiceTest.php                   — availability conflict detection
tests/Unit/Services/Calendar/PriceLabsSyncServiceTest.php          — rate generation from API data
tests/Feature/Admin/CalendarControllerTest.php
tests/Feature/Console/RefreshIcalCommandTest.php
tests/Feature/Console/SyncPriceLabsCommandTest.php
```

### Cutover

1. Replace `LiveCart::` facade calls
2. Replace admin calendar controller
3. Delete cron URL routes from `web.php`
4. Add `schedule()` entries in `Console/Kernel.php`
5. Set up server `crontab` with `php artisan schedule:run`

---

## Phase 7 — Booking & Availability Module ⚠️ CRITICAL

**Goal:** Rebuild booking creation and the availability/pricing engine — the most complex business logic in the system.

**Duration:** 7–10 days

### Models (1 model, but heaviest logic)

| Model | Table | FK Dependencies |
|---|---|---|
| `BookingRequest` | `booking_requests` | `property_id` → properties |

### DTOs

```
Booking/BookingData.php             — guest info, dates, property, coupon code
Booking/BookingQuoteData.php        — line-item pricing: nightly rates, cleaning, tax, pet, guest, coupon
Booking/GuestData.php               — first_name, last_name, email, phone
Property/AvailabilityData.php       — available: bool, conflicts: array, minNights: int
```

### Services

| Class | Key Methods |
|---|---|
| `Booking/AvailabilityService` | `ensureAvailable(propertyId, checkIn, checkOut)` — checks Guesty OR iCal depending on property type; throws `BookingNotAvailableException` |
| `Booking/PricingService` | `calculateQuote(BookingData): BookingQuoteData` — local rate engine (flat + day-of-week) OR Guesty quote; adds cleaning fee, tax, pet fee, guest fee, heating_pool_fee, additional fees, coupon discount |
| `Booking/QuoteService` | `getGuestyQuote(propertyId, checkIn, checkOut, guests)` — calls `GuestyQuoteApi`, transforms to BookingQuoteData |
| `Booking/BookingService` | `create(BookingData, BookingQuoteData): BookingRequest`, `confirm(id)`, `cancel(id, reason)`, `generateReference(): string` |

### Actions

| Class | Purpose |
|---|---|
| `Booking/CreateBookingAction` | Orchestrate: validate availability → calculate pricing → create booking → dispatch `BookingCreated` |
| `Booking/CancelBookingAction` | Cancel → dispatch `BookingCancelled` (listener handles Guesty cancel + email) |
| `Booking/ApplyCouponAction` | Validate coupon code, check property match, compute discount |

### Controllers

| Controller | Methods | Replaces |
|---|---|---|
| `Public/BookingController` | `store`, `storeGuesty`, `preview`, `rentalAgreement`, `saveRentalAgreement` | `PageController@saveBookingData/1`, `previewBooking`, `rentalAggrementBooking/DataSave` |
| `Api/AvailabilityApiController` | `getQuote`, `getAdminQuote`, `getAdminEditQuote` | `PageController@checkAjaxGetQuoteData`, admin variants |
| `Api/BookingApiController` | `updatePayment`, `postPaymentQuote`, `show` | `PageController@updatepaymentBookingData`, `getQuoteAfter`, `getBookingData` |
| `Admin/BookingRequestController` | full CRUD + `confirmed`, `singlePropertyBooking`, `getCheckinCheckoutData` | Full rebuild of admin controller |

### FormRequests

```
Booking/StoreBookingRequest.php           — property_id, check_in, check_out, adults, name, email, phone
Booking/UpdateBookingRequest.php
Booking/AvailabilityCheckRequest.php      — property_id, check_in, check_out
Public/RentalAgreementFormRequest.php     — signature, emergency contact fields
Admin/BookingRequestFormRequest.php
```

### Repositories

| Interface | Key Methods |
|---|---|
| `BookingRepositoryInterface` | `findByReference(ref)`, `getByProperty(id)`, `getPending()`, `getForWelcomePackage(daysOut)`, `getForReminder(daysOut)`, `getForReviewEmail(daysAfter)` |

### Exceptions

```
Booking/BookingNotAvailableException.php
Booking/MinimumNightsNotMetException.php
Booking/InvalidCouponException.php
```

### Tests (highest ROI in entire project)

```
tests/Unit/Services/Booking/PricingServiceTest.php
  — local flat rate, local day-of-week rate, Guesty quote
  — cleaning fee, tax, pet fee per-night vs flat
  — guest fee, coupon percentage, coupon fixed, heating pool fee

tests/Unit/Services/Booking/AvailabilityServiceTest.php
  — Guesty available, Guesty unavailable
  — iCal conflict, iCal clear
  — minimum night violation

tests/Unit/Services/Booking/BookingServiceTest.php
  — create with reference generation, confirm, cancel

tests/Unit/Actions/Booking/CreateBookingActionTest.php
  — full flow with mocked services

tests/Unit/Actions/Booking/ApplyCouponActionTest.php
  — valid, expired, wrong property, wrong type

tests/Feature/Public/BookingControllerTest.php — store, preview, rental agreement
tests/Feature/Api/AvailabilityApiControllerTest.php — AJAX quote
tests/Feature/Admin/BookingRequestControllerTest.php — admin CRUD + confirm + cancel
```

### Cutover Strategy (HIGH RISK — use feature flag)

1. Deploy new services alongside old code
2. Add feature flag: `config('features.new_booking_engine')`
3. Route to new controllers when flag is ON
4. Test thoroughly in staging
5. Flip flag in production
6. Remove old code after 1 week stable

---

## Phase 8 — Payment Module (Stripe + PayPal) ⚠️ CRITICAL

**Goal:** Rebuild Stripe and PayPal payment flows with clean gateway abstractions and event-driven post-payment processing.

**Duration:** 5–7 days

### Models (1 model)

| Model | Table | FK Dependencies |
|---|---|---|
| `Payment` | `payments` | `booking_id` → booking_requests |

### Integration Classes

| Class | Key Methods |
|---|---|
| `Stripe/StripeGateway` | `createCharge(amount, token, desc)`, `createPaymentIntent(amount)`, `createSetupIntent()` — wraps Stripe PHP SDK |
| `PayPal/PayPalGateway` | `verifyPayment(txnId, amount)` — server-side verification |

### DTOs

```
Payment/PaymentResultData.php       — transactionId, status, amount, gateway, rawResponse
Payment/StripeChargeData.php        — token, amount, description, currency
```

### Services

| Class | Key Methods |
|---|---|
| `Payment/PaymentService` | `processStripePayment(bookingId, StripeChargeData)`, `processPaypalPayment(bookingId, txnData)`, `recordPayment(bookingId, PaymentResultData)` |

### Actions

| Class | Purpose |
|---|---|
| `Payment/ProcessStripePaymentAction` | Charge via StripeGateway → record payment → dispatch `BookingPaid` |
| `Payment/ProcessPaypalPaymentAction` | Verify via PayPalGateway → record payment → dispatch `BookingPaid` |

### Events & Listeners

| Event | Listeners (all `ShouldQueue`) |
|---|---|
| `BookingPaid` | `SyncBookingToGuesty` — create + confirm reservation in Guesty |
| `BookingPaid` | `AttachGuestyPayment` — attach payment to Guesty reservation |
| `BookingPaid` | `SendBookingConfirmationEmail` — email to guest + admin |
| `PaymentReceived` | `UpdateBookingPaymentStatus` — update `payment_status` field |

### Controllers

| Controller | Methods | Replaces |
|---|---|---|
| `Payment/StripeController` | `index` (form), `store` (charge), `getIntentData`, `paymentInit` | Legacy `StripeController` |
| `Payment/PaypalController` | `index` (form), `verify` | Legacy `PaypalController` |
| `Payment/ReceiptController` | `show`, `showAlternate` | `CommonController@showReceipt/1` |

### FormRequests

```
Payment/StripePaymentRequest.php    — stripeToken required, amount numeric
Payment/PaypalPaymentRequest.php    — transaction reference required
```

### Exceptions

```
Payment/PaymentFailedException.php
Payment/PaymentGatewayException.php
```

### Tests

```
tests/Unit/Integrations/Stripe/StripeGatewayTest.php              — mocked Stripe SDK
tests/Unit/Integrations/PayPal/PayPalGatewayTest.php               — verify flow
tests/Unit/Actions/Payment/ProcessStripePaymentActionTest.php      — full flow, events asserted
tests/Unit/Actions/Payment/ProcessPaypalPaymentActionTest.php
tests/Unit/Listeners/Booking/SyncBookingToGuestyTest.php           — Guesty API mocked
tests/Unit/Listeners/Booking/AttachGuestyPaymentTest.php
tests/Feature/Payment/StripeControllerTest.php                     — end-to-end faked
tests/Feature/Payment/PaypalControllerTest.php
tests/Feature/Payment/ReceiptControllerTest.php
```

### Cutover

Deploy behind same feature flag as Phase 7. Test with Stripe test keys. Flip in production.

---

## Phase 9 — Email Automation & Scheduled Jobs

**Goal:** Replace all inline email sends with Events/Listeners. Replace remaining unprotected cron URLs with Artisan commands.

**Duration:** 3–4 days

### Events

| Event | Dispatched When |
|---|---|
| `BookingCreated` | After booking saved (Phase 7) |
| `BookingConfirmed` | After admin confirms |
| `BookingCancelled` | After admin cancels |
| `BookingPaid` | After payment succeeds (Phase 8) |
| `ContactFormSubmitted` | After contact form saved |
| `NewsletterSubscribed` | After newsletter form saved |
| `OnboardingRequested` | After onboarding form saved |
| `PropertyManagementRequested` | After PM inquiry saved |
| `ReviewSubmitted` | After guest submits review |
| `RentalAgreementSigned` | After rental agreement saved |

### Listeners (all implement `ShouldQueue`)

| Listener | Triggered By | Action |
|---|---|---|
| `SendBookingConfirmationEmail` | `BookingPaid` | Guest + admin email via EmailService |
| `SendBookingCancellationEmail` | `BookingCancelled` | Guest email |
| `SyncBookingToGuesty` | `BookingPaid` | Create + confirm Guesty reservation |
| `AttachGuestyPayment` | `BookingPaid` | Attach payment to Guesty reservation |
| `SendContactNotificationEmail` | `ContactFormSubmitted` | Admin notification |
| `SendNewsletterConfirmationEmail` | `NewsletterSubscribed` | Subscriber confirmation |
| `SendOnboardingNotificationEmail` | `OnboardingRequested` | Admin notification + file attachments |
| `SendPropertyManagementNotificationEmail` | `PropertyManagementRequested` | Admin notification |
| `SendReviewNotificationEmail` | `ReviewSubmitted` | Admin notification |

### Artisan Commands (replace remaining cron URLs)

| Command | Schedule | Replaces |
|---|---|---|
| `SendWelcomePackagesCommand` | `->dailyAt('08:00')` | `GET /send-welcome-packages` |
| `SendRemindersCommand` | `->dailyAt('09:00')` | `GET /send-reminder-email` |
| `SendReviewRequestsCommand` | `->dailyAt('10:00')` | `GET /send-review-email` |

### Services

| Class | Responsibility |
|---|---|
| `Communication/WelcomePackageService` | Query bookings with check_in X days away, filter unsent, send |
| `Communication/ReminderService` | Same pattern for check-in reminders |
| `Communication/ReviewRequestService` | Query bookings with check_out X days ago, filter unsent, send |

### Tests

```
tests/Unit/Listeners/ — one test per listener (mock EmailService, GuestyBookingApi)
tests/Feature/Console/SendWelcomePackagesCommandTest.php   — seed bookings, assert emails sent
tests/Feature/Console/SendRemindersCommandTest.php
tests/Feature/Console/SendReviewRequestsCommandTest.php
tests/Feature/Events/BookingPaidEventTest.php              — assert all 3 listeners fire
```

### Cutover

1. Wire `EventServiceProvider`
2. Replace inline `MailHelper::emailSender()` calls with `event()` dispatches
3. Delete cron URL routes from `web.php`
4. Set up `php artisan schedule:run` in server crontab

---

## Phase 10 — Public Website & Final Cleanup

**Goal:** Split remaining `PageController` (884 lines), wire everything, remove all legacy code.

**Duration:** 4–5 days

### Controllers

| Controller | Methods | Replaces |
|---|---|---|
| `Public/HomeController` | `index()` | `PageController@index` |
| `Public/PageController` | `cmsPage`, `teamMember`, `vacation` | `PageController@dynamicDataCategory`, `ourTeamSingle`, `getVacationData` |
| `Public/ContactController` | `store`, `propertyManagement` | `PageController@contactPost`, `propertyManagementPost` |
| `Public/OnboardingController` | `store` | `PageController@onboardingPost` |
| `Public/NewsletterController` | `store` | `PageController@newsletterPost` |
| `Public/ReviewController` | `store` | `PageController@reviewSubmit` |
| `Public/SitemapController` | `index` | `PageController@sitemap` |
| `Public/CaptchaController` | `reload` | `PageController@reloadCaptcha` |

### FormRequests

```
Public/ContactFormRequest.php                — name, email, phone, message + captcha
Public/PropertyManagementFormRequest.php     — name, email, mobile, property_address + captcha
Public/OnboardingFormRequest.php             — multi-field + file1/file2 + captcha
Public/NewsletterRequest.php                 — email required|unique
Public/ReviewFormRequest.php                 — star_rating, comment, booking_id
```

### Legacy Code to Delete

```
app/Http/Controllers/PageController.php              (884 lines → 0)
app/Http/Controllers/ICalController.php
app/Http/Controllers/Payment/CommonController.php
app/Facades/GuestyApi.php
app/Facades/Helper.php
app/Facades/LiveCart.php
app/Facades/MailHelper.php
app/Facades/ModelHelper.php
app/Helper/GuestyApi.php         (883 lines)
app/Helper/Helper.php            (325 lines)
app/Helper/LiveCart.php           (763 lines)
app/Helper/MailHelper.php
app/Helper/ModelHelper.php       (243 lines)
app/Helper/Upload.php
app/Models/BookingRequest-old.php
```

### Final Hardening

- [ ] Add Eloquent `$casts` to all models (dates, JSON, booleans)
- [ ] Add missing `belongsTo`/`hasMany` relationships to every model
- [ ] Rename `email_templetes` → `email_templates` (migration + model)
- [ ] Rename `NewsLetter` → `Newsletter` model
- [ ] Remove `OptimizeMiddleware` hacks — use proper middleware
- [ ] Profile with Debugbar — confirm no N+1 queries
- [ ] Run full test suite

### Tests (smoke / integration)

```
tests/Feature/Smoke/PublicPagesTest.php     — hit every public URL, assert 200
tests/Feature/Smoke/AdminPagesTest.php      — hit every admin URL with auth, assert 200
tests/Feature/Smoke/AjaxEndpointsTest.php   — hit every AJAX endpoint, assert valid JSON
```

---

## Summary Timeline

| Phase | Module | Duration | Models | Risk Level |
|---|---|---|---|---|
| **0** | Foundation & Infrastructure | 3–4 days | 0 | None |
| **1** | Auth & User | 2 days | 1 | Low |
| **2** | Settings, Media & Standalone Content | 5–7 days | 18 | Low |
| **3** | Location & Property (Core) | 7–10 days | 13 | Medium |
| **4** | Attractions & Blog | 3–4 days | 4 | Low |
| **5** | Guesty PMS Integration | 5–7 days | 5 | High |
| **6** | iCal, PriceLabs & Calendar | 4–5 days | 2 | Medium |
| **7** | Booking & Availability ⚠️ | 7–10 days | 1 | **Critical** |
| **8** | Payment (Stripe + PayPal) ⚠️ | 5–7 days | 1 | **Critical** |
| **9** | Email Automation & Scheduled Jobs | 3–4 days | 0 | Medium |
| **10** | Public Website & Cleanup | 4–5 days | 0 | Low |
| | **Total** | **~50–65 days** | **45** | |

---

## Safety Rules

1. **Never delete working code until its replacement is deployed and verified**
2. **Feature flags** for Phase 7+8 (booking/payment) — instant rollback
3. **Each phase has its own test suite** — no phase ships without green tests
4. **Route names never change** — Blade `route()` calls keep working
5. **View variables never change** — `compact()` parameters stay identical
6. **Flash message keys never change** — `success`, `danger` stay as-is
7. **One phase per PR** — reviewable, revertable
8. **Smoke tests after every deploy** — hit every URL, assert 200
