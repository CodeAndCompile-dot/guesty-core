# Guesty-Core Implementation Progress

> Auto-maintained log of all files created and implemented in the guesty-core rebuild.

---

## Phase 0 — Foundation (Complete)

| Type | File | Notes |
|------|------|-------|
| Contract | `app/Repositories/Contracts/BaseRepositoryInterface.php` | all(), paginate(), find(), findOrFail(), findBy(), create(), update(), delete(), activate(), deactivate(), duplicate() |
| Repository | `app/Repositories/Eloquent/BaseRepository.php` | Implements BaseRepositoryInterface, activate=1/deactivate=0 |
| Repository | `app/Repositories/Eloquent/GenericCrudRepository.php` | Static `for()` factory |
| Service | `app/Services/Shared/CrudService.php` | Generic CRUD + HasActivation/HasDuplication/HasImageUpload traits |
| Service | `app/Services/Media/UploadService.php` | Upload/delete to `public/uploads/{folder}` |
| Trait | `app/Support/Traits/HasActivation.php` | activate/deactivate via repository |
| Trait | `app/Support/Traits/HasDuplication.php` | replicate via repository |
| Trait | `app/Support/Traits/HasImageUpload.php` | processImageUploads with existing file cleanup |
| Provider | `app/Providers/RepositoryServiceProvider.php` | Binds repository interfaces |
| Provider | `app/Providers/ViewComposerServiceProvider.php` | SettingComposer for `*` views |
| Composer | `app/View/Composers/SettingComposer.php` | Pluck value/name from basic_settings |
| Config | `config/guesty.php` | Guesty PMS API config |
| Config | `config/pricelabs.php` | PriceLabs API config |
| Config | `config/payment.php` | Stripe payment config |
| Config | `config/adminlte.php` | AdminLTE sidebar/layout config |

---

## Phase 1 — Auth (Complete)

| Type | File | Notes |
|------|------|-------|
| Controller | `app/Http/Controllers/Auth/LoginController.php` | AuthenticatesUsers trait |
| Routes | `routes/web/auth.php` | guest middleware, POST /client-login, POST /logout |

---

## Phase 2 — Admin Modules (Complete)

### Models (18)
| File | Table |
|------|-------|
| `app/Models/User.php` | users |
| `app/Models/BasicSetting.php` | basic_settings |
| `app/Models/Slider.php` | sliders |
| `app/Models/Cms.php` | cms |
| `app/Models/WelcomePackage.php` | welcome_packages |
| `app/Models/EmailTemplete.php` | email_templetes |
| `app/Models/Faq.php` | faqs |
| `app/Models/Gallery.php` | galleries |
| `app/Models/NewsLetter.php` | newsletters |
| `app/Models/OurClient.php` | our_clients |
| `app/Models/Testimonial.php` | testimonials |
| `app/Models/OurTeam.php` | our_teams |
| `app/Models/Service.php` | services |
| `app/Models/ContactusRequest.php` | contactus_requests |
| `app/Models/LandingCms.php` | landing_cms |
| `app/Models/SeoCms.php` | seo_pages |
| `app/Models/OnboardingRequest.php` | onboarding_requests |
| `app/Models/MaximizeAsset.php` | maximize_assets |

### Repositories
| File | Notes |
|------|-------|
| `app/Repositories/Contracts/SettingRepositoryInterface.php` | |
| `app/Repositories/Eloquent/SettingRepository.php` | upsert by name |
| `app/Repositories/Contracts/CmsRepositoryInterface.php` | |
| `app/Repositories/Eloquent/CmsRepository.php` | |
| `app/Repositories/Contracts/EmailTemplateRepositoryInterface.php` | |
| `app/Repositories/Eloquent/EmailTemplateRepository.php` | |
| `app/Repositories/Contracts/UserRepositoryInterface.php` | |
| `app/Repositories/Eloquent/UserRepository.php` | password hashing |

### Services
| File | Notes |
|------|-------|
| `app/Services/Admin/SettingService.php` | Batch upsert settings |
| `app/Services/Admin/UserService.php` | Create/update user with password hash |
| `app/Services/Admin/CmsService.php` | CMS CRUD |
| `app/Services/Admin/SeoContentService.php` | SEO pages CRUD |
| `app/Services/Admin/MediaCenterService.php` | File upload/list/delete |

### Form Requests (8)
`FaqFormRequest`, `OurTeamFormRequest`, `ServiceFormRequest`, `UserFormRequest`, `NewsLetterFormRequest`, `CmsFormRequest`, `CouponFormRequest`, `PasswordChangeFormRequest`

### Controllers (20)
`DashboardController`, `CkeditorController`, `SliderController`, `CmsController`, `WelcomePackageController`, `EmailTempleteController`, `FaqController`, `GalleryController`, `NewsLetterController`, `OurClientController`, `TestimonialController`, `OurTeamController`, `ServiceController`, `ContactusRequestController`, `LandingCmsController`, `SeoCmsController`, `OnboardingRequestController`, `MaximizeAssetController`, `PropertyManagementRequestController`, `UserController`

### Helpers & Facades
| File | Notes |
|------|-------|
| `app/Helpers/Helper.php` | getBooleanData, getTempletes, getSeoUrlGet, getImage, etc. |
| `app/Facades/Helper.php` | Facade accessor |
| `app/Helpers/ModelHelper.php` | getDataFromSetting, getLocationSelectList, etc. |
| `app/Facades/ModelHelper.php` | Facade accessor |

---

## Phase 3 — Location & Property Module (Complete)

### Models (13)
| File | Table | Notes |
|------|-------|-------|
| `app/Models/Location.php` | locations | |
| `app/Models/Property.php` | properties | ~70 fillable fields |
| `app/Models/PropertyGallery.php` | properties_galleries | |
| `app/Models/PropertyFee.php` | properties_fees | |
| `app/Models/PropertySpace.php` | properties_spaces | |
| `app/Models/PropertyAmenityGroup.php` | properties_amenity_groups | |
| `app/Models/PropertyAmenity.php` | properties_amenities | |
| `app/Models/PropertyRateGroup.php` | properties_rates_group | preserves `thrusday_price` typo |
| `app/Models/PropertyRate.php` | properties_rates | |
| `app/Models/PropertyRoom.php` | properties_rooms | |
| `app/Models/PropertyRoomItem.php` | properties_room_items | |
| `app/Models/PropertyRoomItemImage.php` | properties_room_item_images | |
| `app/Models/Coupon.php` | coupons | |

### Repositories
| File | Notes |
|------|-------|
| `app/Repositories/Contracts/PropertyRepositoryInterface.php` | duplicateWithRelations |
| `app/Repositories/Eloquent/PropertyRepository.php` | DB::transaction |
| `app/Repositories/Contracts/LocationRepositoryInterface.php` | |
| `app/Repositories/Eloquent/LocationRepository.php` | |
| `app/Repositories/Contracts/CouponRepositoryInterface.php` | |
| `app/Repositories/Eloquent/CouponRepository.php` | |
| `app/Repositories/Contracts/PropertyRateGroupRepositoryInterface.php` | findOverlapping |
| `app/Repositories/Eloquent/PropertyRateGroupRepository.php` | |

### Services
| File | Notes |
|------|-------|
| `app/Services/Admin/PropertyService.php` | Transaction, cascade destroy, syncGalleryImages/Fees/Spaces |
| `app/Services/Admin/RateService.php` | generateDailyRates with day-of-week pricing |
| `app/Services/Admin/RoomService.php` | Room + RoomItem CRUD |
| `app/Services/Admin/AmenityService.php` | Group + Amenity CRUD, 'true'/'false' status |

### DTOs
`app/Support/DTOs/PropertyData.php`, `PricingData.php`, `RateCalendarData.php`

### Form Requests (7)
`PropertyFormRequest`, `LocationFormRequest`, `CouponFormRequest`, `AmenityGroupFormRequest`, `AmenityFormRequest`, `RoomFormRequest`, `RateGroupFormRequest`

### Controllers (8)
`PropertyController`, `LocationController`, `CouponController`, `PropertyRateController`, `PropertyAmenityGroupController`, `PropertyAmenityController`, `PropertyRoomController`, `PropertyRoomItemController`

---

## Phase 4 — Attractions & Blog Module (Complete)

### Migrations (5)
| File | Notes |
|------|-------|
| `database/migrations/2026_03_05_163040_create_attractions_table.php` | Copied from legacy, fixed index name |
| `database/migrations/2026_03_05_163040_create_attraction_categories_table.php` | |
| `database/migrations/2026_03_05_163040_create_blogs_table.php` | |
| `database/migrations/2026_03_05_163040_create_blog_categories_table.php` | |
| `database/migrations/2026_03_07_100000_phase4_fixes.php` | Makes templete, ordering, blog_category_id nullable |

### Models (4)
| File | Table | Notes |
|------|-------|-------|
| `app/Models/Attraction.php` | attractions | BelongsTo Location, AttractionCategory |
| `app/Models/AttractionCategory.php` | attraction_categories | Self-referencing parent, HasMany Attraction |
| `app/Models/Blog.php` | blogs | BelongsTo BlogCategory, German _ger fields, status: active/deactive |
| `app/Models/BlogCategory.php` | blog_categories | HasMany Blog, German _ger fields |

### Form Requests (4)
| File | Validation |
|------|------------|
| `app/Http/Requests/Admin/AttractionFormRequest.php` | seo_url (required, unique), name (required) |
| `app/Http/Requests/Admin/AttractionCategoryFormRequest.php` | seo_url (required, unique), name (required) |
| `app/Http/Requests/Admin/BlogFormRequest.php` | seo_url (required, unique), title (required), blog_category_id (required) |
| `app/Http/Requests/Admin/BlogCategoryFormRequest.php` | seo_url (required, unique), title (required), ordering (required) |

### Controllers (4)
| File | Features |
|------|----------|
| `app/Http/Controllers/Admin/AttractionController.php` | Standard CRUD, image upload (attractions folder) |
| `app/Http/Controllers/Admin/AttractionCategoryController.php` | CRUD + remove-image checkbox handling, 3 image fields |
| `app/Http/Controllers/Admin/BlogController.php` | CRUD + active/deactive (string status) + copyData |
| `app/Http/Controllers/Admin/BlogCategoryController.php` | CRUD + copyData |

### ModelHelper Additions
| Method | Returns |
|--------|---------|
| `getBlogCategoriesSelect()` | BlogCategory id => title |
| `getAttractionCategorySelect()` | AttractionCategory id => name |

### Views (16 blade files)
- `resources/views/admin/attractions/` — index, create, edit, form
- `resources/views/admin/attraction-categories/` — index, create, edit, form
- `resources/views/admin/blogs/` — index, create, edit, form
- `resources/views/admin/blog-category/` — index, create, edit, form

### Routes Added
- Resource routes: `attractions`, `attraction-categories`, `blogs`, `blog-category`
- Custom: `blogs/copydata/{id}`, `blogs/active/{id}`, `blogs/deactive/{id}`, `blog-category/copydata/{id}`

### Bug Fixes
- Fixed duplicate SQLite index name `'name'` in attractions and testimonials migrations

### Tests (4 files, 35 tests)
| File | Tests |
|------|-------|
| `tests/Feature/Admin/AttractionControllerTest.php` | 9 tests |
| `tests/Feature/Admin/AttractionCategoryControllerTest.php` | 7 tests |
| `tests/Feature/Admin/BlogControllerTest.php` | 11 tests |
| `tests/Feature/Admin/BlogCategoryControllerTest.php` | 8 tests |

---

## Phase 5 — Guesty PMS Integration (Complete)

### Migrations (5 copied + 1 fix)
| File | Notes |
|------|-------|
| `database/migrations/2026_03_05_163040_create_guesty_properties_table.php` | 70+ columns, 2 composite indexes |
| `database/migrations/2026_03_05_163040_create_guesty_property_prices_table.php` | Per-listing price data |
| `database/migrations/2026_03_05_163040_create_guesty_property_bookings_table.php` | Reservation sync data |
| `database/migrations/2026_03_05_163040_create_guesty_property_reviews_table.php` | Review data |
| `database/migrations/2026_03_05_163040_create_guesty_availablity_prices_table.php` | Per-day pricing/availability |
| `database/migrations/2026_03_07_200000_phase5_guesty_fixes.php` | all_data nullable, availability indexes, missing columns |

### Models (5)
| File | Table | Notes |
|------|-------|-------|
| `app/Models/GuestyProperty.php` | guesty_properties | 67 fillable, relationships to Location, PriceInfo, Bookings, Reviews |
| `app/Models/GuestyPropertyPrice.php` | guesty_property_prices | 12 fillable |
| `app/Models/GuestyPropertyBooking.php` | guesty_property_bookings | 13 fillable, date casts |
| `app/Models/GuestyPropertyReview.php` | guesty_property_reviews | 18 fillable |
| `app/Models/GuestyAvailabilityPrice.php` | guesty_availablity_prices | 5 fillable, date/float/int casts |

### Integration Layer (8 files)
| File | Notes |
|------|-------|
| `app/Integrations/Guesty/Contracts/GuestyClientInterface.php` | Contract: token, GET/POST/PUT for Open API & Booking API |
| `app/Integrations/Guesty/GuestyClient.php` | Token caching via Cache::remember, Http facade, error logging |
| `app/Integrations/Guesty/GuestyPropertyApi.php` | syncProperties (upsert), syncAvailability (delete+recreate), getCalendarFees, searchAvailability |
| `app/Integrations/Guesty/GuestyBookingApi.php` | syncBookings (truncate+reimport), createReservation, confirmReservation |
| `app/Integrations/Guesty/GuestyGuestApi.php` | createGuest, getGuest |
| `app/Integrations/Guesty/GuestyPaymentApi.php` | attachPaymentMethod, recordPayment, getListingPaymentProvider, tokenizeCard |
| `app/Integrations/Guesty/GuestyQuoteApi.php` | createDetailedQuote (adults+children), createSimpleQuote, createBookingEngineQuote |
| `app/Integrations/Guesty/GuestyReviewApi.php` | syncReviews (truncate+reimport, fetches guest name per review) |

### Admin Controller & FormRequest
| File | Notes |
|------|-------|
| `app/Http/Controllers/Admin/GuestyPropertyController.php` | Edit-only (no create/store/destroy), 5 image fields, getSubLocationList AJAX |
| `app/Http/Requests/Admin/GuestyPropertyFormRequest.php` | seo_url required + unique with exclusion |

### Views (3 copied from legacy)
| File | Notes |
|------|-------|
| `resources/views/admin/guesty_properties/index.blade.php` | Property listing with sync buttons |
| `resources/views/admin/guesty_properties/edit.blade.php` | Edit form with AJAX sub-location loader |
| `resources/views/admin/guesty_properties/form.blade.php` | SEO/metadata/location fields only |

### Routes
- Resource route: `guesty_properties` → GuestyPropertyController
- AJAX: `POST getSubLocationList` → GuestyPropertyController@getSubLocationList
- Stub routes: `set-getPropertyData`, `set-getBookingData`, `get-reviews-data`, `set-getToken`, `getBookingToken`

### Provider Updates
- `IntegrationServiceProvider`: GuestyClientInterface → GuestyClient binding activated

### Config Updates
- `config/guesty.php`: Added booking_client_id, booking_client_secret

### Tests (2 files, 28 tests)
| File | Tests |
|------|-------|
| `tests/Feature/Admin/GuestyPropertyControllerTest.php` | 11 tests |
| `tests/Unit/Integrations/Guesty/GuestyClientTest.php` | 6 tests |
| `tests/Unit/Integrations/Guesty/GuestyApiTest.php` | 11 tests |

---

## Test Summary

| Phase | Tests | Assertions |
|-------|-------|------------|
| Phase 0-3 | 129 | 268 |
| Phase 4 | +35 | +90 |
| Phase 5 | +28 | +70 |
| **Total** | **192** | **428** |

All tests passing ✅

---

## Phases Remaining

- [x] Phase 5 — Guesty PMS Integration (API service, admin controller, sync endpoints)
- [ ] Phase 6 — iCal, PriceLabs & Calendar (iCal parser, calendar controller, PriceLabs service)
- [ ] Phase 7 — Booking & Availability (booking flow, availability engine, guest management)
- [ ] Phase 8 — Payment Module (Stripe integration, invoice generation)
- [ ] Phase 9 — Email Automation & Scheduled Jobs (Mailable classes, queue jobs, scheduling)
- [ ] Phase 10 — Public Website & Final Cleanup (front controllers, public routes, regression tests)
