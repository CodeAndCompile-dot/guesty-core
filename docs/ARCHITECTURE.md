# Bentonville Lodging Co. — Clean Architecture Blueprint

> Target architecture for rebuilding the legacy Laravel project.
> Blade frontend remains untouched — all existing view variables and redirects are preserved.

---

## Table of Contents

1. [Guiding Principles](#1-guiding-principles)
2. [Folder Structure](#2-folder-structure)
3. [Layer Responsibilities](#3-layer-responsibilities)
4. [Dependency Flow](#4-dependency-flow)
5. [Domain Breakdown](#5-domain-breakdown)
6. [Naming Conventions](#6-naming-conventions)
7. [Migration Map — Old → New](#7-migration-map--old--new)
8. [Service Provider Bindings](#8-service-provider-bindings)
9. [Example Refactor: Booking Flow](#9-example-refactor-booking-flow)
10. [Example Refactor: Admin CRUD](#10-example-refactor-admin-crud)
11. [Testing Strategy](#11-testing-strategy)
12. [Migration Roadmap](#12-migration-roadmap)

---

## 1. Guiding Principles

| Principle | Rule |
|---|---|
| **Thin controllers** | Controllers do three things: validate (via FormRequest), call a Service/Action, return a response. Max ~15 lines per method. |
| **Single Responsibility** | Each class has exactly one reason to change. |
| **Dependency Inversion** | Controllers depend on Service interfaces; Services depend on Repository interfaces; never concrete classes. |
| **No Eloquent in Controllers** | All database access goes through Repositories. |
| **No HTTP in Services** | External API calls go through Integration classes. Services call Integrations, never `Http::` or `curl` directly. |
| **DTOs over arrays** | Structured data passed between layers uses typed DTOs, not raw arrays. |
| **Events for side-effects** | Emails, Guesty sync, logging — all triggered via Events/Listeners, not inline in business logic. |
| **Blade contract preserved** | View variables, redirect routes, flash messages, and session keys remain identical. Blade files require zero changes. |

---

## 2. Folder Structure

```
app/
├── Actions/                          # Single-purpose action classes (invokable)
│   ├── Booking/
│   │   ├── CreateBookingAction.php
│   │   ├── CancelBookingAction.php
│   │   ├── ConfirmBookingAction.php
│   │   └── ApplyCouponAction.php
│   ├── Payment/
│   │   ├── ProcessStripePaymentAction.php
│   │   ├── ProcessPaypalPaymentAction.php
│   │   └── AttachPaymentToBookingAction.php
│   ├── Property/
│   │   ├── SyncGuestyPropertyAction.php
│   │   └── CalculatePricingAction.php
│   └── Calendar/
│       ├── RefreshIcalFeedsAction.php
│       ├── SyncPriceLabsAction.php
│       └── CheckAvailabilityAction.php
│
├── Console/
│   ├── Kernel.php                    # Schedule definitions (replaces unprotected cron URLs)
│   └── Commands/
│       ├── RefreshIcalCommand.php
│       ├── SyncPriceLabsCommand.php
│       ├── SendWelcomePackagesCommand.php
│       ├── SendRemindersCommand.php
│       └── SendReviewRequestsCommand.php
│
├── DTO/                              # Data Transfer Objects (immutable, typed)
│   ├── Booking/
│   │   ├── BookingData.php
│   │   ├── BookingQuoteData.php
│   │   └── GuestData.php
│   ├── Payment/
│   │   ├── PaymentResultData.php
│   │   └── StripeChargeData.php
│   ├── Property/
│   │   ├── PropertyData.php
│   │   ├── PricingData.php
│   │   ├── AvailabilityData.php
│   │   └── RateCalendarData.php
│   └── Guesty/
│       ├── GuestyTokenData.php
│       ├── GuestyReservationData.php
│       └── GuestyQuoteData.php
│
├── Events/                           # Domain events
│   ├── Booking/
│   │   ├── BookingCreated.php
│   │   ├── BookingConfirmed.php
│   │   ├── BookingCancelled.php
│   │   └── BookingPaid.php
│   ├── Payment/
│   │   └── PaymentReceived.php
│   ├── Guest/
│   │   ├── ReviewSubmitted.php
│   │   └── RentalAgreementSigned.php
│   ├── Contact/
│   │   ├── ContactFormSubmitted.php
│   │   ├── NewsletterSubscribed.php
│   │   ├── OnboardingRequested.php
│   │   └── PropertyManagementRequested.php
│   └── Calendar/
│       └── IcalRefreshed.php
│
├── Listeners/                        # Event handlers (side-effects)
│   ├── Booking/
│   │   ├── SendBookingConfirmationEmail.php
│   │   ├── SendBookingCancellationEmail.php
│   │   ├── SyncBookingToGuesty.php
│   │   └── AttachGuestyPayment.php
│   ├── Payment/
│   │   └── UpdateBookingPaymentStatus.php
│   ├── Guest/
│   │   ├── SendReviewNotificationEmail.php
│   │   └── StoreRentalAgreement.php
│   ├── Contact/
│   │   ├── SendContactNotificationEmail.php
│   │   ├── SendNewsletterConfirmationEmail.php
│   │   ├── SendOnboardingNotificationEmail.php
│   │   └── SendPropertyManagementNotificationEmail.php
│   └── Scheduling/
│       ├── SendWelcomePackageEmail.php
│       ├── SendReminderEmail.php
│       └── SendReviewRequestEmail.php
│
├── Exceptions/
│   ├── Handler.php
│   ├── Booking/
│   │   ├── BookingNotAvailableException.php
│   │   ├── MinimumNightsNotMetException.php
│   │   └── InvalidCouponException.php
│   ├── Payment/
│   │   ├── PaymentFailedException.php
│   │   └── PaymentGatewayException.php
│   └── Integration/
│       ├── GuestyApiException.php
│       └── PriceLabsApiException.php
│
├── Http/
│   ├── Kernel.php
│   ├── Controllers/
│   │   ├── Controller.php
│   │   │
│   │   ├── Public/                   # Public-facing (currently PageController)
│   │   │   ├── HomeController.php
│   │   │   ├── PropertyController.php
│   │   │   ├── BookingController.php
│   │   │   ├── BlogController.php
│   │   │   ├── AttractionController.php
│   │   │   ├── ContactController.php
│   │   │   ├── PageController.php        # CMS / dynamic / SEO pages
│   │   │   ├── ReviewController.php
│   │   │   ├── NewsletterController.php
│   │   │   ├── OnboardingController.php
│   │   │   ├── SitemapController.php
│   │   │   └── CaptchaController.php
│   │   │
│   │   ├── Payment/                  # Payment gateways
│   │   │   ├── StripeController.php
│   │   │   ├── PaypalController.php
│   │   │   └── ReceiptController.php
│   │   │
│   │   ├── Api/                      # AJAX / JSON endpoints
│   │   │   ├── PropertyApiController.php
│   │   │   ├── BookingApiController.php
│   │   │   ├── AvailabilityApiController.php
│   │   │   └── GuestyTokenController.php
│   │   │
│   │   └── Admin/                    # Admin panel
│   │       ├── DashboardController.php
│   │       ├── PropertyController.php
│   │       ├── GuestyPropertyController.php
│   │       ├── BookingRequestController.php
│   │       ├── CalendarController.php
│   │       ├── RateController.php
│   │       ├── RoomController.php
│   │       ├── RoomItemController.php
│   │       ├── AmenityGroupController.php
│   │       ├── AmenityController.php
│   │       ├── BlogController.php
│   │       ├── BlogCategoryController.php
│   │       ├── AttractionController.php
│   │       ├── AttractionCategoryController.php
│   │       ├── CmsController.php
│   │       ├── SeoCmsController.php
│   │       ├── LandingCmsController.php
│   │       ├── GalleryController.php
│   │       ├── SliderController.php
│   │       ├── FaqController.php
│   │       ├── TestimonialController.php
│   │       ├── TeamController.php
│   │       ├── ClientController.php
│   │       ├── ServiceController.php
│   │       ├── CouponController.php
│   │       ├── EmailTemplateController.php
│   │       ├── ContactRequestController.php
│   │       ├── NewsletterController.php
│   │       ├── OnboardingRequestController.php
│   │       ├── PropertyManagementRequestController.php
│   │       ├── WelcomePackageController.php
│   │       ├── MaximizeAssetController.php
│   │       ├── UserController.php
│   │       ├── SettingController.php
│   │       ├── MediaController.php
│   │       └── CkeditorController.php
│   │
│   ├── Requests/                     # FormRequest validation classes
│   │   ├── Public/
│   │   │   ├── ContactFormRequest.php
│   │   │   ├── NewsletterRequest.php
│   │   │   ├── OnboardingFormRequest.php
│   │   │   ├── PropertyManagementFormRequest.php
│   │   │   ├── ReviewFormRequest.php
│   │   │   └── RentalAgreementFormRequest.php
│   │   ├── Booking/
│   │   │   ├── StoreBookingRequest.php
│   │   │   ├── UpdateBookingRequest.php
│   │   │   └── AvailabilityCheckRequest.php
│   │   ├── Payment/
│   │   │   ├── StripePaymentRequest.php
│   │   │   └── PaypalPaymentRequest.php
│   │   └── Admin/
│   │       ├── PropertyFormRequest.php
│   │       ├── GuestyPropertyFormRequest.php
│   │       ├── BookingRequestFormRequest.php
│   │       ├── BlogFormRequest.php
│   │       ├── BlogCategoryFormRequest.php
│   │       ├── AttractionFormRequest.php
│   │       ├── AttractionCategoryFormRequest.php
│   │       ├── CmsFormRequest.php
│   │       ├── SeoCmsFormRequest.php
│   │       ├── LandingCmsFormRequest.php
│   │       ├── CouponFormRequest.php
│   │       ├── ServiceFormRequest.php
│   │       ├── TeamFormRequest.php
│   │       ├── UserFormRequest.php
│   │       ├── SettingFormRequest.php
│   │       ├── PasswordChangeFormRequest.php
│   │       ├── RateGroupFormRequest.php
│   │       ├── RoomFormRequest.php
│   │       ├── AmenityFormRequest.php
│   │       ├── AmenityGroupFormRequest.php
│   │       ├── IcalImportFormRequest.php
│   │       ├── NewsletterFormRequest.php
│   │       └── SliderFormRequest.php
│   │
│   └── Middleware/
│       ├── Authenticate.php
│       ├── ForceHttps.php            # Replaces HTTPRedirect
│       ├── OptimizeResponse.php      # Replaces OptimizeMiddleware
│       └── ... (Laravel defaults)
│
├── Integrations/                     # External API wrappers (HTTP boundary)
│   ├── Guesty/
│   │   ├── GuestyClient.php              # HTTP client, token management, retry logic
│   │   ├── GuestyPropertyApi.php         # Property CRUD operations
│   │   ├── GuestyBookingApi.php          # Reservation lifecycle
│   │   ├── GuestyGuestApi.php            # Guest management
│   │   ├── GuestyPaymentApi.php          # Payment attachment
│   │   ├── GuestyQuoteApi.php            # Quote generation
│   │   ├── GuestyReviewApi.php           # Review retrieval
│   │   └── Contracts/
│   │       ├── GuestyClientInterface.php
│   │       ├── GuestyPropertyApiInterface.php
│   │       ├── GuestyBookingApiInterface.php
│   │       ├── GuestyGuestApiInterface.php
│   │       ├── GuestyPaymentApiInterface.php
│   │       ├── GuestyQuoteApiInterface.php
│   │       └── GuestyReviewApiInterface.php
│   │
│   ├── PriceLabs/
│   │   ├── PriceLabsClient.php           # HTTP client for PriceLabs API
│   │   └── Contracts/
│   │       └── PriceLabsClientInterface.php
│   │
│   ├── Stripe/
│   │   ├── StripeGateway.php             # Wraps Stripe PHP SDK
│   │   └── Contracts/
│   │       └── StripeGatewayInterface.php
│   │
│   ├── PayPal/
│   │   ├── PayPalGateway.php             # PayPal JS SDK server verification
│   │   └── Contracts/
│   │       └── PayPalGatewayInterface.php
│   │
│   └── ICal/
│       ├── ICalParser.php                # Parse .ics files into domain objects
│       ├── ICalExporter.php              # Generate .ics files from bookings
│       └── Contracts/
│           ├── ICalParserInterface.php
│           └── ICalExporterInterface.php
│
├── Models/                           # Eloquent models (unchanged, but cleaned)
│   ├── Attraction.php
│   ├── AttractionCategory.php
│   ├── BasicSetting.php
│   ├── Blog.php                      # Moved from Models/Blogs/
│   ├── BlogCategory.php             # Moved from Models/Blogs/
│   ├── BookingRequest.php
│   ├── Cms.php
│   ├── ContactusRequest.php
│   ├── Coupon.php
│   ├── EmailTemplate.php            # Renamed from EmailTemplete
│   ├── Faq.php
│   ├── Gallery.php
│   ├── GuestyAvailabilityPrice.php  # Moved from Models/Guesty/
│   ├── GuestyProperty.php           # Moved from Models/Guesty/
│   ├── GuestyPropertyBooking.php
│   ├── GuestyPropertyPrice.php
│   ├── GuestyPropertyReview.php
│   ├── IcalEvent.php
│   ├── IcalImportList.php
│   ├── LandingCms.php
│   ├── Location.php
│   ├── MaximizeAsset.php
│   ├── Newsletter.php               # Renamed from NewsLetter
│   ├── OnboardingRequest.php
│   ├── OurClient.php
│   ├── OurTeam.php
│   ├── Payment.php
│   ├── Property.php
│   ├── PropertyAmenity.php
│   ├── PropertyAmenityGroup.php
│   ├── PropertyFee.php
│   ├── PropertyGallery.php
│   ├── PropertyManagementRequest.php
│   ├── PropertyRate.php
│   ├── PropertyRateGroup.php
│   ├── PropertyRoom.php
│   ├── PropertyRoomItem.php
│   ├── PropertyRoomItemImage.php
│   ├── PropertySpace.php
│   ├── Review.php
│   ├── SeoCms.php
│   ├── Service.php
│   ├── Slider.php
│   ├── Testimonial.php
│   ├── User.php
│   └── WelcomePackage.php
│
├── Repositories/                     # Database access layer
│   ├── Contracts/                    # Interfaces
│   │   ├── PropertyRepositoryInterface.php
│   │   ├── GuestyPropertyRepositoryInterface.php
│   │   ├── BookingRepositoryInterface.php
│   │   ├── CouponRepositoryInterface.php
│   │   ├── IcalEventRepositoryInterface.php
│   │   ├── IcalImportListRepositoryInterface.php
│   │   ├── PropertyRateRepositoryInterface.php
│   │   ├── PropertyRateGroupRepositoryInterface.php
│   │   ├── PropertyRoomRepositoryInterface.php
│   │   ├── PropertyAmenityRepositoryInterface.php
│   │   ├── PropertyAmenityGroupRepositoryInterface.php
│   │   ├── CmsRepositoryInterface.php
│   │   ├── BlogRepositoryInterface.php
│   │   ├── AttractionRepositoryInterface.php
│   │   ├── LocationRepositoryInterface.php
│   │   ├── SettingRepositoryInterface.php
│   │   ├── EmailTemplateRepositoryInterface.php
│   │   ├── UserRepositoryInterface.php
│   │   └── BaseRepositoryInterface.php
│   │
│   └── Eloquent/                     # Implementations
│       ├── BaseRepository.php            # Shared CRUD: index, find, create, update, delete, activate, deactivate, duplicate
│       ├── PropertyRepository.php
│       ├── GuestyPropertyRepository.php
│       ├── BookingRepository.php
│       ├── CouponRepository.php
│       ├── IcalEventRepository.php
│       ├── IcalImportListRepository.php
│       ├── PropertyRateRepository.php
│       ├── PropertyRateGroupRepository.php
│       ├── PropertyRoomRepository.php
│       ├── PropertyAmenityRepository.php
│       ├── PropertyAmenityGroupRepository.php
│       ├── CmsRepository.php
│       ├── BlogRepository.php
│       ├── AttractionRepository.php
│       ├── LocationRepository.php
│       ├── SettingRepository.php
│       ├── EmailTemplateRepository.php
│       ├── UserRepository.php
│       └── GenericCrudRepository.php     # For simple admin CRUD entities (Gallery, Slider, FAQ, etc.)
│
├── Services/                         # Business logic orchestration
│   ├── Booking/
│   │   ├── BookingService.php            # Create, confirm, cancel bookings
│   │   ├── PricingService.php            # Calculate gross amount, taxes, fees, coupon discounts
│   │   ├── AvailabilityService.php       # Check availability (Guesty + iCal + local)
│   │   └── QuoteService.php             # Generate pricing quotes
│   ├── Payment/
│   │   └── PaymentService.php            # Orchestrate payment flow, post-payment processing
│   ├── Property/
│   │   ├── PropertyService.php           # Property CRUD with galleries, fees, spaces
│   │   ├── GuestyPropertyService.php     # Guesty property overrides
│   │   ├── RateService.php              # Rate group management, per-day rate generation
│   │   ├── RoomService.php             # Room + room item management
│   │   └── AmenityService.php          # Amenity group + amenity management
│   ├── Calendar/
│   │   ├── IcalService.php              # iCal import/export/refresh logic
│   │   └── PriceLabsSyncService.php     # PriceLabs rate sync logic
│   ├── Content/
│   │   ├── CmsService.php               # CMS page management (multi-image handling)
│   │   ├── SeoCmsService.php            # SEO pages with JSON sections
│   │   ├── LandingCmsService.php        # Landing pages with JSON sections
│   │   ├── BlogService.php              # Blog + category management
│   │   └── AttractionService.php        # Attraction + category management
│   ├── Communication/
│   │   ├── EmailService.php             # Template rendering + sending (replaces MailHelper)
│   │   ├── WelcomePackageService.php    # Welcome package scheduling logic
│   │   ├── ReminderService.php          # Reminder scheduling logic
│   │   └── ReviewRequestService.php     # Post-checkout review request logic
│   ├── Media/
│   │   ├── UploadService.php            # File upload handling (replaces Upload helper)
│   │   └── MediaCenterService.php       # Media center operations
│   ├── Admin/
│   │   ├── SettingService.php           # Global settings management
│   │   ├── UserService.php             # Admin user management
│   │   └── ExportService.php           # Data export logic
│   └── Shared/
│       └── CrudService.php              # Generic CRUD orchestration for simple admin modules
│
├── Providers/
│   ├── AppServiceProvider.php
│   ├── AuthServiceProvider.php
│   ├── EventServiceProvider.php          # Event → Listener mapping
│   ├── RepositoryServiceProvider.php     # Interface → Eloquent bindings
│   ├── IntegrationServiceProvider.php    # Interface → Integration bindings
│   └── ViewComposerServiceProvider.php   # Replaces global View::share in boot()
│
├── Support/                          # Framework helpers
│   ├── helpers.php                       # Global helper functions (if any)
│   └── Traits/
│       ├── HasImageUpload.php            # Reusable image upload logic for services
│       ├── HasActivation.php             # active/deactive toggle logic
│       └── HasDuplication.php            # copyData / replicate logic
│
└── View/
    └── Composers/
        ├── SettingComposer.php           # Shares setting_data to all views
        └── NavigationComposer.php        # Shares menu data
```

```
config/
├── guesty.php                # Guesty API credentials & endpoints
├── pricelabs.php             # PriceLabs API key & endpoint
├── payment.php               # Stripe & PayPal keys (replaces scattered env() calls)
└── ... (existing configs)
```

```
routes/
├── web.php                   # Minimal — includes route files below
├── web/
│   ├── public.php            # All public-facing routes
│   ├── booking.php           # Booking & payment routes
│   └── admin.php             # All admin routes
├── api.php                   # JSON/AJAX endpoints only
├── console.php               # Artisan command scheduling
└── channels.php
```

---

## 3. Layer Responsibilities

### Controllers
```
WHO:    HTTP boundary — receives Request, returns Response
DOES:   1. Accept FormRequest (auto-validates)
        2. Transform request to DTO (if needed)
        3. Call ONE Service or Action method
        4. Return view() / redirect() / json()
DOES NOT: Query DB, call external APIs, send emails, compute prices
LINES:  ≤ 15 per method
```

### FormRequests
```
WHO:    Input validation & authorization
DOES:   1. Define rules() for field validation
        2. Define authorize() for access control
        3. Define messages() for custom error text
        4. Optional: prepareForValidation() to sanitize input
DOES NOT: Contain business rules (e.g., date overlap checks belong in Service)
```

### Services
```
WHO:    Business logic orchestration
DOES:   1. Implement domain rules (pricing calculation, availability checks, coupon validation)
        2. Coordinate between Repositories and Integrations
        3. Dispatch Events for side-effects
        4. Return DTOs to Controllers
DOES NOT: Access Request/Session directly, return HTTP responses, call Eloquent directly
DEPENDS ON: Repository interfaces, Integration interfaces, other Services
```

### Actions
```
WHO:    Single-purpose, invokable business operations
DOES:   1. Encapsulate one complex operation that spans multiple services
        2. Typically has a single __invoke() method
WHEN:   Use instead of Service when the operation is a one-off cross-cutting workflow
        (e.g., ProcessStripePaymentAction calls PaymentService + BookingService + dispatches events)
DOES NOT: Replace Services — Actions call Services, not the other way around
```

### Repositories
```
WHO:    Database access abstraction
DOES:   1. All Eloquent queries (where, find, create, update, delete)
        2. Complex query scopes (date range overlaps, filtered listings)
        3. Return Eloquent models or Collections
DOES NOT: Contain business logic, dispatch events, call external APIs
INTERFACE: Every repository has a Contract interface bound in ServiceProvider

BaseRepository provides:
  - all(): Get all records
  - find($id): Find by ID
  - create(array $data): Create record
  - update($id, array $data): Update record
  - delete($id): Delete record
  - activate($id): Set status active
  - deactivate($id): Set status inactive
  - duplicate($id): Replicate record
```

### Integrations
```
WHO:    External API communication boundary
DOES:   1. Make HTTP requests to third-party APIs (Guesty, PriceLabs, Stripe, PayPal)
        2. Handle authentication, token refresh, retries, rate limiting
        3. Transform API responses into DTOs
        4. Throw typed exceptions on failure (GuestyApiException, PaymentGatewayException)
DOES NOT: Contain business logic, access database, know about Laravel Request/Response
INTERFACE: Every integration has a Contract interface — enables mocking in tests

Token caching:
  - GuestyClient caches OAuth2 tokens in Laravel Cache (not re-fetched per request)
  - Token refresh handled internally with automatic retry
```

### DTOs (Data Transfer Objects)
```
WHO:    Typed, immutable data containers
DOES:   1. Carry data between layers with type safety
        2. Replace raw arrays (e.g., $data = $request->all())
        3. Enforce required fields at construction time
DOES NOT: Contain logic, make API calls, depend on framework classes
PATTERN: readonly class with named constructor or static factory method

Example:
  readonly class BookingData {
      public function __construct(
          public string $propertyId,
          public string $checkIn,
          public string $checkOut,
          public int $adults,
          public int $children,
          public int $infants,
          public int $pets,
          public ?string $couponCode,
      ) {}

      public static function fromRequest(StoreBookingRequest $req): self { ... }
  }
```

### Events & Listeners
```
WHO:    Decoupled side-effect handlers
DOES:   1. Events are dispatched by Services/Actions at domain boundaries
        2. Listeners handle side-effects: emails, Guesty sync, logging, webhooks
PATTERN: One Event can have multiple Listeners, each listener does ONE thing

Example flow (booking paid):
  BookingPaid event →
    ├── SendBookingConfirmationEmail (Listener)
    ├── SyncBookingToGuesty (Listener)
    └── AttachGuestyPayment (Listener)

QUEUED: All email and API-sync listeners should implement ShouldQueue
```

### Exceptions
```
WHO:    Typed, domain-specific error handling
DOES:   1. Replace generic Exception throws with domain exceptions
        2. Carry context (booking ID, reason, API response)
        3. Handler.php maps exceptions to user-facing flash messages/redirects
```

---

## 4. Dependency Flow

```
┌───────────────────────────────────────────────────────────────┐
│                        HTTP Layer                             │
│  Request → Middleware → FormRequest → Controller → Response   │
└──────────────────────────┬────────────────────────────────────┘
                           │ calls
                           ▼
┌───────────────────────────────────────────────────────────────┐
│                     Service / Action Layer                     │
│  BookingService, PricingService, PaymentService, etc.         │
│  Dispatches Events ──────────────────────────────► Events     │
└──────────┬───────────────────────────┬────────────────────────┘
           │ calls                     │ calls
           ▼                           ▼
┌─────────────────────┐   ┌──────────────────────────────────┐
│   Repository Layer   │   │      Integration Layer           │
│  BookingRepository   │   │  GuestyClient, StripeGateway,   │
│  PropertyRepository  │   │  PriceLabsClient, ICalParser    │
└──────────┬───────────┘   └──────────────┬───────────────────┘
           │                               │
           ▼                               ▼
      ┌──────────┐                ┌──────────────────┐
      │ Eloquent │                │ External APIs    │
      │ Database │                │ (Guesty, Stripe, │
      └──────────┘                │  PriceLabs, etc) │
                                  └──────────────────┘

Events ──► Listeners ──► EmailService / Integrations (queued)
```

**Rule: Dependencies point downward only. No layer may reference a layer above it.**

---

## 5. Domain Breakdown

### Domain 1: Booking & Reservations
| Component | Class | Responsibility |
|---|---|---|
| Controller | `Public/BookingController` | saveBookingData, previewBooking, rentalAgreement |
| Controller | `Admin/BookingRequestController` | Admin CRUD for bookings |
| FormRequest | `StoreBookingRequest` | Validate guest info, dates, property_id |
| FormRequest | `RentalAgreementFormRequest` | Validate SSN, emergency contact, signature |
| Service | `BookingService` | Create booking, confirm, cancel, generate reference |
| Service | `PricingService` | Calculate gross amount, taxes, fees, coupon |
| Service | `AvailabilityService` | Check Guesty + iCal + local availability |
| Service | `QuoteService` | Generate pricing quote (local + Guesty) |
| Action | `CreateBookingAction` | Orchestrate full booking creation flow |
| Action | `CancelBookingAction` | Cancel + Guesty sync + email |
| Action | `ApplyCouponAction` | Validate coupon, compute discount |
| Repository | `BookingRepository` | BookingRequest queries |
| Repository | `CouponRepository` | Coupon lookup, property-specific queries |
| DTO | `BookingData` | Typed booking payload |
| DTO | `BookingQuoteData` | Pricing breakdown |
| Event | `BookingCreated` | Triggers confirmation email |
| Event | `BookingCancelled` | Triggers cancellation email + Guesty cancel |
| Event | `BookingPaid` | Triggers Guesty sync + payment attachment |

### Domain 2: Payments
| Component | Class | Responsibility |
|---|---|---|
| Controller | `Payment/StripeController` | Render form, process Stripe charge |
| Controller | `Payment/PaypalController` | Render form, verify PayPal payment |
| Controller | `Payment/ReceiptController` | Show receipt pages |
| FormRequest | `StripePaymentRequest` | Validate Stripe token, amount |
| FormRequest | `PaypalPaymentRequest` | Validate PayPal return params |
| Service | `PaymentService` | Orchestrate payment → booking update → events |
| Action | `ProcessStripePaymentAction` | Stripe-specific flow |
| Action | `ProcessPaypalPaymentAction` | PayPal-specific flow |
| Integration | `StripeGateway` | Stripe SDK wrapper (Charge, PaymentIntent, SetupIntent) |
| Integration | `PayPalGateway` | PayPal verification |
| DTO | `PaymentResultData` | Transaction ID, status, amount |
| Event | `PaymentReceived` | Triggers post-payment processing |

### Domain 3: Properties
| Component | Class | Responsibility |
|---|---|---|
| Controller | `Public/PropertyController` | Property detail, property listing |
| Controller | `Admin/PropertyController` | Admin CRUD + gallery/fees/spaces |
| Controller | `Admin/GuestyPropertyController` | Local overrides for Guesty properties |
| Service | `PropertyService` | Property CRUD with sub-entities |
| Service | `GuestyPropertyService` | Guesty property local override management |
| Service | `RateService` | Rate group + per-day rate generation |
| Service | `RoomService` | Room + item management |
| Service | `AmenityService` | Amenity group + amenity management |
| Repository | `PropertyRepository` | Property queries with relations |
| Repository | `GuestyPropertyRepository` | Guesty property queries |
| Repository | `PropertyRateGroupRepository` | Rate group overlap checks |
| Repository | `PropertyRateRepository` | Per-day rate CRUD |

### Domain 4: Guesty Integration
| Component | Class | Responsibility |
|---|---|---|
| Integration | `GuestyClient` | HTTP client, OAuth2 token caching, retry |
| Integration | `GuestyPropertyApi` | Fetch property data, availability, calendar fees |
| Integration | `GuestyBookingApi` | Create, confirm, cancel reservations |
| Integration | `GuestyGuestApi` | Create, fetch guest records |
| Integration | `GuestyPaymentApi` | Attach payment, mark paid |
| Integration | `GuestyQuoteApi` | Generate pricing quotes |
| Integration | `GuestyReviewApi` | Fetch reviews |
| DTO | `GuestyTokenData` | Token + expiry |
| DTO | `GuestyReservationData` | Reservation details |
| DTO | `GuestyQuoteData` | Quote breakdown |

### Domain 5: Calendar & Scheduling
| Component | Class | Responsibility |
|---|---|---|
| Controller | `Admin/CalendarController` | iCal import list, refresh, events |
| Service | `IcalService` | Import, export, refresh, conflict check |
| Service | `PriceLabsSyncService` | Fetch & apply PriceLabs rates |
| Integration | `ICalParser` | Parse .ics format |
| Integration | `ICalExporter` | Generate .ics files |
| Integration | `PriceLabsClient` | PriceLabs API HTTP calls |
| Command | `RefreshIcalCommand` | Scheduled iCal refresh |
| Command | `SyncPriceLabsCommand` | Scheduled PriceLabs sync |
| Repository | `IcalEventRepository` | iCal event queries |
| Repository | `IcalImportListRepository` | Feed URL management |

### Domain 6: Content Management (Admin)
| Component | Class | Responsibility |
|---|---|---|
| Service | `CmsService` | CMS pages with multi-image handling |
| Service | `SeoCmsService` | SEO pages with JSON sections |
| Service | `LandingCmsService` | Landing pages with JSON sections |
| Service | `BlogService` | Blog posts + categories |
| Service | `AttractionService` | Attractions + categories |
| Service | `CrudService` (shared) | Generic CRUD for Gallery, Slider, FAQ, Testimonial, Client, etc. |

### Domain 7: Communications
| Component | Class | Responsibility |
|---|---|---|
| Service | `EmailService` | Template rendering, placeholder replacement |
| Service | `WelcomePackageService` | Pre-arrival email scheduling |
| Service | `ReminderService` | Check-in reminder scheduling |
| Service | `ReviewRequestService` | Post-checkout review solicitation |
| Command | `SendWelcomePackagesCommand` | Artisan scheduled command |
| Command | `SendRemindersCommand` | Artisan scheduled command |
| Command | `SendReviewRequestsCommand` | Artisan scheduled command |

---

## 6. Naming Conventions

| Layer | Pattern | Example |
|---|---|---|
| Controller | `{Domain}Controller` | `BookingController`, `StripeController` |
| FormRequest | `{Action}{Entity}Request` | `StoreBookingRequest`, `StripePaymentRequest` |
| Service | `{Domain}Service` | `BookingService`, `PricingService` |
| Action | `{Verb}{Entity}Action` | `CreateBookingAction`, `ProcessStripePaymentAction` |
| Repository Interface | `{Entity}RepositoryInterface` | `BookingRepositoryInterface` |
| Repository Impl | `{Entity}Repository` | `BookingRepository` |
| Integration Interface | `{Provider}{Domain}Interface` | `GuestyBookingApiInterface` |
| Integration Impl | `{Provider}{Domain}` | `GuestyBookingApi` |
| DTO | `{Entity}Data` | `BookingData`, `PaymentResultData` |
| Event | `{Entity}{PastTenseVerb}` | `BookingCreated`, `PaymentReceived` |
| Listener | `{Verb}{Side-Effect}` | `SendBookingConfirmationEmail`, `SyncBookingToGuesty` |
| Exception | `{Domain}Exception` | `BookingNotAvailableException` |
| Command | `{Verb}{Entity}Command` | `RefreshIcalCommand` |
| Trait | `Has{Capability}` | `HasImageUpload`, `HasActivation` |

---

## 7. Migration Map — Old → New

### Old Helper Classes → New Locations

| Old (Legacy) | New Location | Notes |
|---|---|---|
| `App\Helper\GuestyApi` (883 lines) | Split into 7 Integration classes under `Integrations/Guesty/` | Each API concern gets its own class |
| `App\Helper\Helper::getGrossAmountData()` | `Services/Booking/PricingService::calculateGrossAmount()` | |
| `App\Helper\Helper::getGrossDataCheckerDays()` | `Services/Booking/AvailabilityService::checkGuestyAvailability()` | |
| `App\Helper\Helper::getPropertyRates()` | `Services/Property/RateService::getCalendarRates()` | |
| `App\Helper\Helper::getPropertyList()` | `Repositories/Eloquent/PropertyRepository::getSelectList()` | |
| `App\Helper\Helper::getSeoUrlGet()` | `Repositories/Eloquent/CmsRepository::findBySeoUrl()` | |
| `App\Helper\ModelHelper::finalEmailAndUpdateBookingPayment()` | `Actions/Payment/AttachPaymentToBookingAction` + `BookingPaid` event | Business logic split from email side-effect |
| `App\Helper\ModelHelper::saveSIngleDatePropertyRate()` | `Services/Property/RateService::generateDailyRates()` | |
| `App\Helper\ModelHelper::getDataFromSetting()` | `Repositories/Eloquent/SettingRepository::getValue()` | |
| `App\Helper\MailHelper::emailSender()` | `Services/Communication/EmailService::sendFromTemplate()` | |
| `App\Helper\MailHelper::emailSenderByController()` | `Services/Communication/EmailService::sendRawHtml()` | |
| `App\Helper\LiveCart` (763 lines) | Split into `Integrations/ICal/ICalParser`, `ICalExporter` + `Services/Calendar/IcalService` | Parse/export = Integration; logic = Service |
| `App\Helper\Upload` | `Services/Media/UploadService` | |
| `App\Helper\MyMenuFilter` | Kept as-is (AdminLTE specific) | |

### Old Facades → Removed
| Old Facade | Replacement |
|---|---|
| `GuestyApi::method()` | Inject `GuestyBookingApiInterface` (or relevant sub-API) |
| `Helper::method()` | Inject relevant Service class |
| `ModelHelper::method()` | Inject relevant Service/Action class |
| `MailHelper::method()` | Inject `EmailService` |
| `LiveCart::method()` | Inject `IcalService` |

**All Facades are removed.** Dependencies are constructor-injected.

### Old God Controller → Split Controllers

| Old `PageController` Method | New Controller | New Method |
|---|---|---|
| `index()` | `Public/HomeController` | `index()` |
| `propertyDetail()` | `Public/PropertyController` | `show()` |
| `propertyLocation()` | `Public/PropertyController` | `byLocation()` |
| `checkAjaxGetQuoteData()` | `Api/AvailabilityApiController` | `getQuote()` |
| `adminCheckAjaxGetQuoteData()` | `Api/AvailabilityApiController` | `getAdminQuote()` |
| `adminCheckAjaxGetQuoteDataEdit()` | `Api/AvailabilityApiController` | `getAdminEditQuote()` |
| `saveBookingData()` | `Public/BookingController` | `store()` |
| `saveBookingData1()` | `Public/BookingController` | `storeGuesty()` |
| `updatepaymentBookingData()` | `Api/BookingApiController` | `updatePayment()` |
| `getQuoteAfter()` | `Api/BookingApiController` | `postPaymentQuote()` |
| `previewBooking()` | `Public/BookingController` | `preview()` |
| `rentalAggrementBooking()` | `Public/BookingController` | `rentalAgreement()` |
| `rentalAggrementDataSave()` | `Public/BookingController` | `saveRentalAgreement()` |
| `contactPost()` | `Public/ContactController` | `store()` |
| `propertyManagementPost()` | `Public/ContactController` | `propertyManagement()` |
| `onboardingPost()` | `Public/OnboardingController` | `store()` |
| `newsletterPost()` | `Public/NewsletterController` | `store()` |
| `reviewSubmit()` | `Public/ReviewController` | `store()` |
| `blogSingle()` | `Public/BlogController` | `show()` |
| `categoryData()` | `Public/BlogController` | `byCategory()` |
| `dynamicDataCategory()` | `Public/PageController` | `cmsPage()` |
| `sitemap()` | `Public/SitemapController` | `index()` |
| `getPropertyData()` | `Api/PropertyApiController` | `show()` |
| `getBookingData()` | `Api/BookingApiController` | `show()` |
| `getToken()` | `Api/GuestyTokenController` | `openApiToken()` |
| `getBookingToken()` | `Api/GuestyTokenController` | `bookingToken()` |
| `getReviewData()` | `Api/PropertyApiController` | `reviews()` |
| `attractionSingle()` | `Public/AttractionController` | `show()` |
| `attractionLocation()` | `Public/AttractionController` | `byLocation()` |
| `attractionCategory()` | `Public/AttractionController` | `byCategory()` |
| `ourTeamSingle()` | `Public/PageController` | `teamMember()` |
| `getVacationData()` | `Public/PageController` | `vacation()` |
| `reloadCaptcha()` | `Public/CaptchaController` | `reload()` |

### Old Cron URLs → Artisan Commands

| Old Route | New Command | Schedule |
|---|---|---|
| `GET /setCronJob` (unprotected) | `php artisan app:refresh-ical` | `->hourly()` |
| `GET /setPriceLab` (unprotected) | `php artisan app:sync-pricelabs` | `->daily()` |
| `GET /sendWelcomePackage` (unprotected) | `php artisan app:send-welcome-packages` | `->dailyAt('08:00')` |
| `GET /sendReminderPackage` (unprotected) | `php artisan app:send-reminders` | `->dailyAt('09:00')` |
| `GET /sendReviewEmail` (unprotected) | `php artisan app:send-review-requests` | `->dailyAt('10:00')` |

---

## 8. Service Provider Bindings

### RepositoryServiceProvider.php

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        \App\Repositories\Contracts\PropertyRepositoryInterface::class
            => \App\Repositories\Eloquent\PropertyRepository::class,
        \App\Repositories\Contracts\GuestyPropertyRepositoryInterface::class
            => \App\Repositories\Eloquent\GuestyPropertyRepository::class,
        \App\Repositories\Contracts\BookingRepositoryInterface::class
            => \App\Repositories\Eloquent\BookingRepository::class,
        \App\Repositories\Contracts\CouponRepositoryInterface::class
            => \App\Repositories\Eloquent\CouponRepository::class,
        \App\Repositories\Contracts\IcalEventRepositoryInterface::class
            => \App\Repositories\Eloquent\IcalEventRepository::class,
        \App\Repositories\Contracts\IcalImportListRepositoryInterface::class
            => \App\Repositories\Eloquent\IcalImportListRepository::class,
        \App\Repositories\Contracts\PropertyRateRepositoryInterface::class
            => \App\Repositories\Eloquent\PropertyRateRepository::class,
        \App\Repositories\Contracts\PropertyRateGroupRepositoryInterface::class
            => \App\Repositories\Eloquent\PropertyRateGroupRepository::class,
        \App\Repositories\Contracts\CmsRepositoryInterface::class
            => \App\Repositories\Eloquent\CmsRepository::class,
        \App\Repositories\Contracts\BlogRepositoryInterface::class
            => \App\Repositories\Eloquent\BlogRepository::class,
        \App\Repositories\Contracts\AttractionRepositoryInterface::class
            => \App\Repositories\Eloquent\AttractionRepository::class,
        \App\Repositories\Contracts\LocationRepositoryInterface::class
            => \App\Repositories\Eloquent\LocationRepository::class,
        \App\Repositories\Contracts\SettingRepositoryInterface::class
            => \App\Repositories\Eloquent\SettingRepository::class,
        \App\Repositories\Contracts\EmailTemplateRepositoryInterface::class
            => \App\Repositories\Eloquent\EmailTemplateRepository::class,
        \App\Repositories\Contracts\UserRepositoryInterface::class
            => \App\Repositories\Eloquent\UserRepository::class,
    ];
}
```

### IntegrationServiceProvider.php

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class IntegrationServiceProvider extends ServiceProvider
{
    public array $bindings = [
        \App\Integrations\Guesty\Contracts\GuestyClientInterface::class
            => \App\Integrations\Guesty\GuestyClient::class,
        \App\Integrations\Guesty\Contracts\GuestyPropertyApiInterface::class
            => \App\Integrations\Guesty\GuestyPropertyApi::class,
        \App\Integrations\Guesty\Contracts\GuestyBookingApiInterface::class
            => \App\Integrations\Guesty\GuestyBookingApi::class,
        \App\Integrations\Guesty\Contracts\GuestyGuestApiInterface::class
            => \App\Integrations\Guesty\GuestyGuestApi::class,
        \App\Integrations\Guesty\Contracts\GuestyPaymentApiInterface::class
            => \App\Integrations\Guesty\GuestyPaymentApi::class,
        \App\Integrations\Guesty\Contracts\GuestyQuoteApiInterface::class
            => \App\Integrations\Guesty\GuestyQuoteApi::class,
        \App\Integrations\Guesty\Contracts\GuestyReviewApiInterface::class
            => \App\Integrations\Guesty\GuestyReviewApi::class,
        \App\Integrations\Stripe\Contracts\StripeGatewayInterface::class
            => \App\Integrations\Stripe\StripeGateway::class,
        \App\Integrations\PayPal\Contracts\PayPalGatewayInterface::class
            => \App\Integrations\PayPal\PayPalGateway::class,
        \App\Integrations\PriceLabs\Contracts\PriceLabsClientInterface::class
            => \App\Integrations\PriceLabs\PriceLabsClient::class,
        \App\Integrations\ICal\Contracts\ICalParserInterface::class
            => \App\Integrations\ICal\ICalParser::class,
        \App\Integrations\ICal\Contracts\ICalExporterInterface::class
            => \App\Integrations\ICal\ICalExporter::class,
    ];
}
```

### EventServiceProvider.php

```php
<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // Booking Events
        \App\Events\Booking\BookingCreated::class => [
            \App\Listeners\Booking\SendBookingConfirmationEmail::class,
        ],
        \App\Events\Booking\BookingPaid::class => [
            \App\Listeners\Booking\SyncBookingToGuesty::class,
            \App\Listeners\Booking\AttachGuestyPayment::class,
        ],
        \App\Events\Booking\BookingCancelled::class => [
            \App\Listeners\Booking\SendBookingCancellationEmail::class,
        ],

        // Payment Events
        \App\Events\Payment\PaymentReceived::class => [
            \App\Listeners\Payment\UpdateBookingPaymentStatus::class,
        ],

        // Guest Events
        \App\Events\Guest\ReviewSubmitted::class => [
            \App\Listeners\Guest\SendReviewNotificationEmail::class,
        ],
        \App\Events\Guest\RentalAgreementSigned::class => [
            \App\Listeners\Guest\StoreRentalAgreement::class,
        ],

        // Contact Events
        \App\Events\Contact\ContactFormSubmitted::class => [
            \App\Listeners\Contact\SendContactNotificationEmail::class,
        ],
        \App\Events\Contact\NewsletterSubscribed::class => [
            \App\Listeners\Contact\SendNewsletterConfirmationEmail::class,
        ],
        \App\Events\Contact\OnboardingRequested::class => [
            \App\Listeners\Contact\SendOnboardingNotificationEmail::class,
        ],
        \App\Events\Contact\PropertyManagementRequested::class => [
            \App\Listeners\Contact\SendPropertyManagementNotificationEmail::class,
        ],
    ];
}
```

---

## 9. Example Refactor: Booking Flow

### Before (Legacy) — `PageController::saveBookingData()` (~100 lines)
```
Controller does EVERYTHING:
  1. Validates inline with Validator::make()
  2. Calls GuestyApi::getQuoteNewNew()
  3. Calls LiveCart::iCalDataCheckInCheckOut()
  4. Computes pricing with Helper::getGrossAmountData()
  5. Applies coupon logic inline
  6. Creates BookingRequest via Eloquent directly
  7. Stores data in Session
  8. Returns redirect
```

### After (Clean) — Split across layers

**Controller** (10 lines):
```php
class BookingController extends Controller
{
    public function store(
        StoreBookingRequest $request,
        CreateBookingAction $action,
    ): RedirectResponse {
        $bookingData = BookingData::fromRequest($request);
        $booking = $action($bookingData);

        session(['booking_id' => $booking->id]);

        return redirect()->route('booking.preview', $booking->id);
    }
}
```

**FormRequest**:
```php
class StoreBookingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'property_id'  => 'required|integer',
            'check_in'     => 'required|date|after:today',
            'check_out'    => 'required|date|after:check_in',
            'adults'       => 'required|integer|min:1',
            'children'     => 'nullable|integer|min:0',
            'infants'      => 'nullable|integer|min:0',
            'pets'         => 'nullable|integer|min:0',
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'email'        => 'required|email',
            'phone'        => 'required|string',
            'coupon_code'  => 'nullable|string',
        ];
    }
}
```

**Action**:
```php
class CreateBookingAction
{
    public function __construct(
        private AvailabilityService $availability,
        private PricingService $pricing,
        private BookingService $booking,
    ) {}

    public function __invoke(BookingData $data): BookingRequest
    {
        // 1. Check availability (delegates to Guesty or iCal depending on property type)
        $this->availability->ensureAvailable($data->propertyId, $data->checkIn, $data->checkOut);

        // 2. Calculate pricing
        $quote = $this->pricing->calculateQuote($data);

        // 3. Create booking record
        $booking = $this->booking->create($data, $quote);

        // 4. Dispatch event (listeners handle emails)
        BookingCreated::dispatch($booking);

        return $booking;
    }
}
```

**AvailabilityService**:
```php
class AvailabilityService
{
    public function __construct(
        private GuestyPropertyApiInterface $guestyPropertyApi,
        private IcalService $icalService,
        private PropertyRepositoryInterface $propertyRepo,
    ) {}

    public function ensureAvailable(int $propertyId, string $checkIn, string $checkOut): void
    {
        $property = $this->propertyRepo->find($propertyId);

        if ($property->is_guesty) {
            $available = $this->guestyPropertyApi->checkAvailability(
                $property->guesty_id, $checkIn, $checkOut
            );
        } else {
            $available = $this->icalService->isAvailable($propertyId, $checkIn, $checkOut);
        }

        if (! $available) {
            throw new BookingNotAvailableException($propertyId, $checkIn, $checkOut);
        }
    }
}
```

---

## 10. Example Refactor: Admin CRUD

Most admin controllers follow the same pattern. Use a shared `CrudService` + `BaseRepository`.

### Before (Legacy) — Every Admin Controller (~120 lines each, identical structure)
```
Constructor sets $model, $admin_base_url, $admin_view
index(), create(), store(), edit(), update(), destroy()
Some have: active(), deactive(), copyData()
All contain inline Validator::make(), Upload::fileUpload(), Model::create()
```

### After (Clean) — Shared Base

**Admin/GalleryController** (thin):
```php
class GalleryController extends Controller
{
    public function __construct(
        private CrudService $crud,
    ) {
        $this->crud->configure(Gallery::class, 'galleries', 'admin.galleries');
    }

    public function index()
    {
        return view('admin.galleries.index', [
            'data' => $this->crud->index(),
        ]);
    }

    public function store(GalleryFormRequest $request)
    {
        $this->crud->store($request->validated());
        return redirect()->route('galleries.index')->with('success', 'Successfully Added');
    }

    public function edit(int $id)
    {
        return view('admin.galleries.edit', [
            'data' => $this->crud->find($id),
        ]);
    }

    public function update(GalleryFormRequest $request, int $id)
    {
        $this->crud->update($id, $request->validated());
        return redirect()->route('galleries.index')->with('success', 'Successfully Updated');
    }

    public function destroy(int $id)
    {
        $this->crud->delete($id);
        return redirect()->route('galleries.index')->with('success', 'Successfully Deleted');
    }

    public function active(int $id)
    {
        $this->crud->activate($id);
        return redirect()->route('galleries.index')->with('success', 'Successfully Active');
    }

    public function deactive(int $id)
    {
        $this->crud->deactivate($id);
        return redirect()->route('galleries.index')->with('success', 'Successfully Deactive');
    }

    public function copyData(int $id)
    {
        $this->crud->duplicate($id);
        return redirect()->route('galleries.index')->with('success', 'Successfully Coppied');
    }
}
```

**CrudService**:
```php
class CrudService
{
    private string $modelClass;
    private UploadService $upload;

    public function __construct(UploadService $upload)
    {
        $this->upload = $upload;
    }

    public function configure(string $modelClass): void
    {
        $this->modelClass = $modelClass;
    }

    public function index(): Collection
    {
        return $this->modelClass::orderBy('id', 'desc')->get();
    }

    public function find(int $id): Model
    {
        return $this->modelClass::findOrFail($id);
    }

    public function store(array $data): Model
    {
        $data = $this->upload->processUploads($data);
        return $this->modelClass::create($data);
    }

    public function update(int $id, array $data): Model
    {
        $model = $this->modelClass::findOrFail($id);
        $data = $this->upload->processUploads($data);
        $model->update($data);
        return $model;
    }

    public function delete(int $id): void
    {
        $this->modelClass::findOrFail($id)->delete();
    }

    public function activate(int $id): void
    {
        $this->modelClass::findOrFail($id)->update(['status' => 'true']);
    }

    public function deactivate(int $id): void
    {
        $this->modelClass::findOrFail($id)->update(['status' => 'false']);
    }

    public function duplicate(int $id): Model
    {
        $original = $this->modelClass::findOrFail($id);
        $copy = $original->replicate();
        $copy->created_at = now();
        $copy->save();
        return $copy;
    }
}
```

---

## 11. Testing Strategy

| Layer | Test Type | Tooling | What to Test |
|---|---|---|---|
| **FormRequests** | Unit | PHPUnit | Validation rules pass/fail, authorization |
| **DTOs** | Unit | PHPUnit | Construction, immutability, factory methods |
| **Services** | Unit | PHPUnit + Mockery | Mock Repositories & Integrations; test business rules |
| **Actions** | Unit | PHPUnit + Mockery | Mock Services; test orchestration |
| **Repositories** | Integration | PHPUnit + RefreshDatabase | Test queries against SQLite/MySQL |
| **Integrations** | Unit | PHPUnit + Http::fake() | Fake HTTP responses; test parsing/error handling |
| **Controllers** | Feature | PHPUnit + actingAs() | Test full request → response cycle |
| **Listeners** | Unit | PHPUnit + Event::fake() | Test side-effect logic in isolation |

### Priority Tests (highest ROI)
1. `PricingService` — Price calculations must be exact
2. `AvailabilityService` — Availability checks must be reliable
3. `BookingService` — Booking creation integrity
4. `StripeGateway` / `PayPalGateway` — Payment processing must be bulletproof
5. `GuestyClient` — Token management, error handling
6. `IcalService` — Calendar conflict detection

---

## 12. Migration Roadmap

### Phase 1: Foundation (Week 1–2)
- [ ] Create folder structure (empty classes with interfaces)
- [ ] Set up ServiceProviders (Repository, Integration, Event)
- [ ] Create `config/guesty.php`, `config/pricelabs.php`, `config/payment.php`
- [ ] Create `BaseRepository` with shared CRUD methods
- [ ] Create `CrudService` for generic admin operations
- [ ] Create `UploadService` (extract from `Upload` helper)
- [ ] Set up `ViewComposerServiceProvider` (replace View::share in AppServiceProvider)
- [ ] Create DTOs for core domains (BookingData, PaymentResultData, PricingData)

### Phase 2: Integrations (Week 3–4)
- [ ] Extract `GuestyApi` helper → 7 Integration classes
- [ ] Extract `LiveCart` helper → `ICalParser`, `ICalExporter`, `IcalService`
- [ ] Extract `Stripe\Charge::create` calls → `StripeGateway`
- [ ] Extract PayPal logic → `PayPalGateway`
- [ ] Extract PriceLabs calls → `PriceLabsClient`
- [ ] Write integration tests with `Http::fake()`

### Phase 3: Core Services (Week 5–6)
- [ ] Build `PricingService` (extract from `Helper::getGrossAmountData()`)
- [ ] Build `AvailabilityService` (extract from `Helper::getGrossDataCheckerDays()` + `LiveCart`)
- [ ] Build `BookingService` (extract from `PageController::saveBookingData()`)
- [ ] Build `PaymentService` (extract from `StripeController` + `PaypalController` + `ModelHelper`)
- [ ] Build `EmailService` (extract from `MailHelper`)
- [ ] Build `QuoteService` (extract from GuestyApi quote methods)
- [ ] Write unit tests for all services

### Phase 4: Events & Listeners (Week 7)
- [ ] Create all Events and Listeners
- [ ] Wire EventServiceProvider
- [ ] Replace inline email sends with event dispatches
- [ ] Replace inline Guesty sync with listeners
- [ ] Convert cron URL endpoints to Artisan Commands
- [ ] Set up `Console/Kernel.php` scheduling

### Phase 5: Controllers & FormRequests (Week 8–9)
- [ ] Create all FormRequest classes (extract Validator::make rules)
- [ ] Split `PageController` (884 lines) → 12 focused controllers
- [ ] Refactor `StripeController`, `PaypalController`, `CommonController`
- [ ] Refactor `ICalController` → Commands
- [ ] Refactor all 32 Admin controllers to use Services/CrudService
- [ ] Split `routes/web.php` into `routes/web/public.php`, `booking.php`, `admin.php`

### Phase 6: Cleanup & Harden (Week 10)
- [ ] Remove all Facade classes (`App\Facades\*`)
- [ ] Remove all Helper classes (`App\Helper\*`)
- [ ] Delete `BookingRequest-old.php`
- [ ] Fix `EmailTemplete` → `EmailTemplate` (model rename + migration)
- [ ] Fix `NewsLetter` → `Newsletter` (model rename + migration)
- [ ] Add typed exceptions throughout
- [ ] Add missing model relationships (`belongsTo`, `hasMany`)
- [ ] Run full test suite
- [ ] Verify all Blade views render correctly (no variable changes)

---

## Appendix: Files to Delete After Migration

```
app/Facades/GuestyApi.php        → replaced by DI
app/Facades/Helper.php           → replaced by DI
app/Facades/LiveCart.php          → replaced by DI
app/Facades/MailHelper.php       → replaced by DI
app/Facades/ModelHelper.php      → replaced by DI
app/Helper/GuestyApi.php         → split into Integrations/Guesty/*
app/Helper/Helper.php            → split into Services/*
app/Helper/LiveCart.php           → split into Integrations/ICal/* + Services/Calendar/*
app/Helper/MailHelper.php        → replaced by Services/Communication/EmailService
app/Helper/ModelHelper.php       → split into Services/* + Actions/*
app/Helper/Upload.php            → replaced by Services/Media/UploadService
app/Models/BookingRequest-old.php → dead code, delete
```
