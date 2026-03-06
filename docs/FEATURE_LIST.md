# Bentonville Lodging Co. — Complete Feature List

> Auto-generated feature inventory extracted from every controller, helper, and model in the codebase.

---

## Table of Contents

1. [Public Website Module](#1-public-website-module)
2. [Property Listing & Search Module](#2-property-listing--search-module)
3. [Booking & Reservation Module](#3-booking--reservation-module)
4. [Payment Module — Stripe](#4-payment-module--stripe)
5. [Payment Module — PayPal](#5-payment-module--paypal)
6. [Payment — Common / Receipts](#6-payment--common--receipts)
7. [Guesty PMS Integration Module](#7-guesty-pms-integration-module)
8. [iCal / Calendar Sync Module](#8-ical--calendar-sync-module)
9. [PriceLabs Dynamic Pricing Module](#9-pricelabs-dynamic-pricing-module)
10. [Automated Emails & Notifications Module](#10-automated-emails--notifications-module)
11. [Admin — Dashboard & Settings Module](#11-admin--dashboard--settings-module)
12. [Admin — Property Management Module](#12-admin--property-management-module)
13. [Admin — Guesty Property Management Module](#13-admin--guesty-property-management-module)
14. [Admin — Booking Request Management Module](#14-admin--booking-request-management-module)
15. [Admin — Property Calendar (iCal) Module](#15-admin--property-calendar-ical-module)
16. [Admin — Property Rates Module](#16-admin--property-rates-module)
17. [Admin — Property Rooms Module](#17-admin--property-rooms-module)
18. [Admin — Property Room Items Module](#18-admin--property-room-items-module)
19. [Admin — Property Amenity Groups Module](#19-admin--property-amenity-groups-module)
20. [Admin — Property Amenities Module](#20-admin--property-amenities-module)
21. [Admin — Attractions Module](#21-admin--attractions-module)
22. [Admin — Attraction Categories Module](#22-admin--attraction-categories-module)
23. [Admin — Blog Module](#23-admin--blog-module)
24. [Admin — Blog Categories Module](#24-admin--blog-categories-module)
25. [Admin — CMS Pages Module](#25-admin--cms-pages-module)
26. [Admin — SEO CMS Pages Module](#26-admin--seo-cms-pages-module)
27. [Admin — Landing CMS Pages Module](#27-admin--landing-cms-pages-module)
28. [Admin — Gallery Module](#28-admin--gallery-module)
29. [Admin — Slider Module](#29-admin--slider-module)
30. [Admin — FAQ Module](#30-admin--faq-module)
31. [Admin — Testimonials Module](#31-admin--testimonials-module)
32. [Admin — Our Team Module](#32-admin--our-team-module)
33. [Admin — Our Clients Module](#33-admin--our-clients-module)
34. [Admin — Services Module](#34-admin--services-module)
35. [Admin — Coupons Module](#35-admin--coupons-module)
36. [Admin — Email Templates Module](#36-admin--email-templates-module)
37. [Admin — Contact Us Requests Module](#37-admin--contact-us-requests-module)
38. [Admin — Newsletter Module](#38-admin--newsletter-module)
39. [Admin — Onboarding Requests Module](#39-admin--onboarding-requests-module)
40. [Admin — Property Management Requests Module](#40-admin--property-management-requests-module)
41. [Admin — Welcome Packages Module](#41-admin--welcome-packages-module)
42. [Admin — Maximize Assets Module](#42-admin--maximize-assets-module)
43. [Admin — User Management Module](#43-admin--user-management-module)
44. [Admin — CKEditor Module](#44-admin--ckeditor-module)
45. [Helper — GuestyApi Features](#45-helper--guestyapi-features)
46. [Helper — General Utility Features](#46-helper--general-utility-features)
47. [Helper — ModelHelper Features](#47-helper--modelhelper-features)
48. [Helper — MailHelper Features](#48-helper--mailhelper-features)
49. [Helper — LiveCart (iCal Engine) Features](#49-helper--livecart-ical-engine-features)
50. [Authentication & Authorization Module](#50-authentication--authorization-module)
51. [API Endpoints Module](#51-api-endpoints-module)

---

## 1. Public Website Module
**Controller:** `PageController`

- **Homepage rendering** — Dynamic homepage with CMS content, properties, sliders, testimonials, FAQs, blogs, galleries, attractions, locations, team members, clients
- **Dynamic CMS page rendering** — Route-based CMS page lookup by `seo_url` slug (`dynamicDataCategory()`)
- **SEO CMS page rendering** — Dedicated SEO-optimized pages via `SeoCms` model lookup
- **Blog listing page** — Paginated blog index with category filtering
- **Single blog page** — Individual blog detail view by `seo_url` slug (`blogSingle()`)
- **Blog category filtering** — Filter blogs by selected category (`categoryData()`)
- **Contact Us form submission** — Validates name/email/phone/message with Google reCAPTCHA, stores `ContactusRequest`, sends admin notification email (`contactPost()`)
- **Property Management inquiry form** — Captures property management leads with reCAPTCHA, stores `PropertyManagementRequest`, sends email (`propertyManagementPost()`)
- **Onboarding request form** — Multi-field onboarding form with file uploads (file1, file2), reCAPTCHA, stores `OnboardingRequest`, sends email (`onboardingPost()`)
- **Newsletter subscription** — Email subscription with unique email validation, stores `NewsLetter`, sends confirmation email (`newsletterPost()`)
- **Guest review submission** — Captures guest reviews (star rating, comment) linked to a booking, stores `Review`, sends admin notification email (`reviewSubmit()`)
- **Single attraction page** — Attraction detail view by `seo_url` slug (`attractionSingle()`)
- **Attraction location page** — Lists attractions filtered by location (`attractionLocation()`)
- **Attraction category page** — Lists attractions filtered by category (`attractionCategory()`)
- **Property location page** — Lists properties filtered by location (`propertyLocation()`)
- **Single team member page** — Individual team member profile view (`ourTeamSingle()`)
- **Vacation data page** — Dynamic vacation rental content page (`getVacationData()`)
- **XML Sitemap generation** — Auto-generated `sitemap.xml` with all public URLs (`sitemap()`)
- **CAPTCHA reload** — AJAX endpoint to reload Google reCAPTCHA image (`reloadCaptcha()`)
- **Rental agreement display** — Shows rental agreement form for a booking (`rentalAggrementBooking()`)
- **Rental agreement data save** — Saves signed rental agreement with SSN, emergency contact, vehicle info, pet info, and e-signature (`rentalAggrementDataSave()`)
- **Booking preview page** — Displays booking summary before payment with all pricing breakdown (`previewBooking()`)

---

## 2. Property Listing & Search Module
**Controller:** `PageController`

- **Property detail page** — Full property detail view with gallery, amenities, rooms, rates, reviews, calendar, related properties (`propertyDetail()`)
- **Property search / listing** — Browse all properties, filterable by location
- **AJAX property data fetch** — Returns property data for client-side rendering (`getPropertyData()` — calls Guesty Open API)
- **AJAX availability check (public)** — Check-in/check-out date availability and pricing quote via AJAX (`checkAjaxGetQuoteData()`)
- **AJAX availability check (admin - create)** — Admin-side availability/quote check for new bookings (`adminCheckAjaxGetQuoteData()`)
- **AJAX availability check (admin - edit)** — Admin-side availability/quote check for editing bookings (`adminCheckAjaxGetQuoteDataEdit()`)
- **Guesty token retrieval** — Fetch OAuth2 token for Guesty Open API calls (`getToken()`)
- **Guesty booking token retrieval** — Fetch token for Guesty Booking Engine API (`getBookingToken()`)
- **Guesty booking data fetch** — Retrieve booking details from Guesty by reservation ID (`getBookingData()`)
- **Guesty review data fetch** — Retrieve property reviews from Guesty API (`getReviewData()`)
- **Local property rate calendar** — Returns calendar data with day-by-day pricing for local (non-Guesty) properties (`Helper::getPropertyRates()`)
- **iCal availability cross-check** — Checks iCal events for date conflicts on local properties (`LiveCart::iCalDataCheckInCheckOut()`)

---

## 3. Booking & Reservation Module
**Controller:** `PageController`

- **Save booking data (initial)** — Creates a new `BookingRequest` record with guest info, dates, pricing, coupon, and generates a unique booking reference (`saveBookingData()`)
- **Save booking data (Guesty flow)** — Alternative booking save for Guesty properties with additional Guesty-specific fields (`saveBookingData1()`)
- **Update payment on booking** — Updates an existing booking record after payment gateway returns, attaches payment reference (`updatepaymentBookingData()`)
- **Post-payment quote retrieval** — Fetches final Guesty quote after payment is processed and updates booking (`getQuoteAfter()`)
- **Coupon code application** — Validates and applies discount coupons (percentage or fixed) to booking totals
- **Guest count tracking** — Captures adults, children, infants, and pets count per booking
- **Cleaning fee calculation** — Adds cleaning fee from property configuration to booking total
- **Tax calculation** — Computes tax amount based on property tax rate
- **Additional fees** — Supports line-item additional fees attached to properties
- **Minimum night enforcement** — Validates against property's minimum stay requirement
- **Booking status workflow** — Tracks booking through statuses: pending → confirmed → cancelled
- **Booking reference generation** — Auto-generates unique booking reference numbers
- **Dual property system** — Supports both local (database) properties and Guesty-managed properties with different pricing/availability engines

---

## 4. Payment Module — Stripe
**Controller:** `Payment/StripeController`

- **Stripe payment page** — Renders Stripe checkout page with booking summary (`index()`)
- **Stripe direct charge** — Processes card payment via `Stripe\Charge::create` with amount and description (`indexPost()`)
- **Stripe PaymentIntent creation** — Creates a PaymentIntent for advanced Stripe flows (`getIntendentData()`)
- **Stripe SetupIntent creation** — Creates a SetupIntent for saving card details without immediate charge (`payment_init()`)
- **Post-payment booking update** — On successful charge, updates `BookingRequest` with Stripe transaction ID and payment status
- **Post-payment email trigger** — Calls `ModelHelper::finalEmailAndUpdateBookingPayment()` to send confirmation emails after successful Stripe payment
- **Post-payment Guesty sync** — After Stripe payment, creates reservation in Guesty via `GuestyApi::newBookingCreate()` and `GuestyApi::confirmBooking()`
- **Guesty payment attachment** — Attaches payment record to Guesty reservation via `GuestyApi::paymentAttached()`

---

## 5. Payment Module — PayPal
**Controller:** `Payment/PaypalController`

- **PayPal payment page** — Renders PayPal checkout page with booking summary and PayPal JS SDK button (`index()`)
- **PayPal payment verification** — Receives PayPal redirect with transaction details, validates payment status (`indexPost()`)
- **Post-payment booking update** — On verified PayPal payment, updates `BookingRequest` with PayPal transaction ID
- **Post-payment email trigger** — Calls `ModelHelper::finalEmailAndUpdateBookingPayment()` for PayPal payments
- **Post-payment Guesty sync** — After PayPal payment, syncs reservation to Guesty PMS

---

## 6. Payment — Common / Receipts
**Controller:** `Payment/CommonController`

- **Booking receipt page (type 1)** — Displays formatted receipt/confirmation page after successful payment (`showReceipt()`)
- **Booking receipt page (type 2)** — Alternative receipt layout, handles both Guesty and local property receipts (`showReceipt1()`)
- **Dynamic receipt content** — Pulls booking, property, and payment details to render comprehensive receipt with line items

---

## 7. Guesty PMS Integration Module
**Helper:** `GuestyApi`

- **OAuth2 token management** — Authenticates with Guesty Open API via client credentials grant (`getToken()`)
- **Booking Engine token** — Separate authentication for Guesty Booking Engine API (`getBookingToken()`)
- **Property data sync** — Fetches full property details from Guesty Open API (`getPropertyData()`)
- **Booking data retrieval** — Gets reservation details by booking ID from Guesty (`getBookingData()`)
- **Review data retrieval** — Fetches guest reviews for a property from Guesty (`getReviewData()`)
- **Availability check** — Queries Guesty for property availability between dates (`getAVailablityDataData()`)
- **Calendar fee data** — Retrieves per-night pricing from Guesty calendar (`getCalFeeData()`)
- **Additional fee retrieval** — Gets property-level additional fees from Guesty (`getAdditionalFeeData()`, `getAdditionalFeeDataAll()`)
- **Guest creation** — Creates a new guest record in Guesty PMS (`createGuest()`)
- **Reservation creation** — Creates a new booking/reservation in Guesty (`newBookingCreate()`)
- **Reservation confirmation** — Confirms a pending reservation in Guesty (`confirmBooking()`)
- **Payment attachment** — Attaches payment record to a Guesty reservation (`paymentAttached()`)
- **Payment ID retrieval** — Gets the payment method ID from a Guesty reservation (`getBookingPaymentid()`)
- **Quote generation (multiple versions)** — Generates pricing quotes via Guesty Booking Engine (`getQuoteNewNew()`, `getQuouteNew()`, `getQuouteNewNew()`)
- **Booking data push** — Pushes local booking data to Guesty (`setBookingData()`, `setBookingDataNew()`)
- **Full Guesty booking save** — End-to-end booking creation using Guesty data (`saveBookingUsingGuestyData()`)
- **Payment API call** — Marks a reservation as paid in Guesty (`paidAPi()`)
- **Search availability** — Searches property availability via Guesty Booking Engine (`getSearchAvailability()`)
- **Guest data retrieval** — Gets guest details from Guesty (`getGuestData()`)
- **Custom API helper** — Generic Guesty API call wrapper (`customAPI()`)
- **Paginated booking retrieval** — Gets bookings with limit/skip pagination (`getLimitBookingData()`)
- **Guesty Pay tokenization** — JavaScript-based card tokenization via Guesty Pay widget for payment processing

---

## 8. iCal / Calendar Sync Module
**Controller:** `ICalController`  
**Helper:** `LiveCart`

- **Master cron job** — Single endpoint that triggers all scheduled tasks: iCal refresh, PriceLabs sync, welcome packages, reminders, review emails (`setCronJob()`)
- **iCal feed parsing** — Parses `.ics` files from external URLs and extracts events (`getEventsICalObject()`)
- **Calendar refresh** — Refreshes all imported iCal feeds for all properties, updating `IcalEvent` records (`refresshCalendar()`)
- **Single iCal link refresh** — Refreshes events from a single iCal subscription (`LiveCart::refreshIcalData()`)
- **Self iCal file refresh** — Regenerates the property's own iCal export file (`LiveCart::getFileIcalFileData()`)
- **iCal event storage** — Stores parsed iCal events (DTSTART, DTEND, SUMMARY, UID) in `ical_events` table
- **iCal import list management** — Tracks external iCal feed URLs per property in `ical_import_list` table
- **iCal availability check** — Cross-checks booking dates against iCal events for conflict detection (`LiveCart::iCalDataCheckInCheckOut()`)
- **iCal check-in/check-out validation** — Detailed validation of date ranges against existing calendar blocks (`LiveCart::iCalDataCheckInCheckOutCheckinCheckout()`)
- **Bulk iCal refresh** — Refreshes all iCal import lists across the system (`LiveCart::allIcalImportListRefresh()`)
- **iCal file export** — Generates `.ics` export file for each property with all bookings and blocked dates

---

## 9. PriceLabs Dynamic Pricing Module
**Controller:** `ICalController`  
**Helper:** `LiveCart`

- **PriceLabs price sync** — Fetches dynamic pricing from PriceLabs API (`api.pricelabs.co/v1/listing_prices`) and updates local property rates (`setPriceLab()`)
- **Date-range rate updates** — Updates `PropertyRate` and `PropertyRateGroup` tables with PriceLabs-sourced per-night prices
- **Minimum stay sync** — Syncs minimum night stay requirements from PriceLabs data

---

## 10. Automated Emails & Notifications Module
**Controller:** `ICalController`  
**Helper:** `MailHelper`, `ModelHelper`

- **Welcome package email** — Sends pre-arrival welcome package email to guests X days before check-in (`sendWelcomePackage()`)
- **Welcome package (non-redirect)** — Welcome package sender that returns JSON instead of redirect (`sendWelcomePackage1()`)
- **Reminder email** — Sends check-in reminder email to guests X days before arrival (`sendReminderPackage()`)
- **Reminder (non-redirect)** — Reminder sender returning JSON (`sendReminderPackage1()`)
- **Post-checkout review request email** — Sends review solicitation email to guests after checkout (`sendReviewEmail()`)
- **Review email (non-redirect)** — Review email sender returning JSON (`sendReviewEmail1()`)
- **Template-based email system** — Renders emails from `EmailTemplete` model with 30+ dynamic placeholders: `{name}`, `{email}`, `{phone}`, `{check_in}`, `{check_out}`, `{property_name}`, `{total_amount}`, `{booking_id}`, etc. (`MailHelper::emailSender()`)
- **Direct HTML email** — Sends raw HTML emails with optional file attachments (`MailHelper::emailSenderByController()`)
- **Booking confirmation email** — Sent to both guest and admin after successful payment (`ModelHelper::finalEmailAndUpdateBookingPayment()`)
- **Booking cancellation email** — Sent when admin cancels/deletes a booking
- **Contact form notification** — Admin email on new contact form submission
- **Newsletter subscription email** — Confirmation email for new subscribers
- **Onboarding request email** — Admin notification for new onboarding submissions
- **Property management inquiry email** — Admin notification for property management requests
- **Review submission email** — Admin notification when guest submits a review

---

## 11. Admin — Dashboard & Settings Module
**Controller:** `Admin/DashboardController`

- **Admin dashboard** — Landing page (redirects to Guesty properties listing) (`index()`)
- **Media center** — File management interface listing all uploaded files (`mediaCenter()`)
- **File upload (media center)** — Upload new files to the media center (`newFileUploads()`)
- **File delete (media center)** — Delete individual files from media center (`mediasDelete()`)
- **Multiple file delete** — Bulk delete multiple media files at once (`multipleDelete()`)
- **Site settings page** — Display global site configuration form (`setting()`)
- **Site settings update** — Save global settings: site name, logo, favicon, contact info, social links, SEO meta, Google Analytics, API keys, email settings, etc. (`settingPost()`)
- **Admin password change page** — Form to change admin password (`changePassword()`)
- **Admin password change save** — Validates current password, updates to new password with bcrypt hashing (`changePasswordPost()`)
- **Data export** — Export functionality for admin data (`exportData()`)

---

## 12. Admin — Property Management Module
**Controller:** `Admin/PropertyController`

- **Property listing** — Display all properties in admin table (`index()`)
- **Property creation form** — Multi-field form for creating new properties (`create()`)
- **Property store** — Save new property with: SEO URL (unique), name, description, location, amenities, galleries (multiple image upload), fees (multiple fee items), spaces (multiple space items), pricing configuration (`store()`)
- **Property edit form** — Edit existing property with all sub-entities (`edit()`)
- **Property update** — Update property and associated galleries, fees, spaces; handles image upload for gallery items (`update()`)
- **Property delete** — Remove property and all associated data (`destroy()`)
- **Property activate** — Set property status to active/visible (`active()`)
- **Property deactivate** — Set property status to inactive/hidden (`deactive()`)
- **Property duplicate** — Clone a property record with all data (`copyData()`)
- **Gallery caption/sort update** — AJAX endpoint to update image captions and sort order (`updateCaptionSOrt()`)
- **Gallery image delete** — Delete individual gallery images from a property (`imageDeleteAsset()`)
- **Property space delete** — Delete individual space items from a property (`deletePropertySpace()`)
- **Multiple image upload** — Handles bulk image upload for property galleries during create/update

---

## 13. Admin — Guesty Property Management Module
**Controller:** `Admin/GuestyPropertyController`

- **Guesty property listing** — Display all Guesty-synced properties (uses DashboardController index redirect)
- **Guesty property edit form** — Edit local overrides for Guesty properties: booklet PDF, banner image, feature image, OG image, rental agreement PDF, custom fields (`edit()`)
- **Guesty property update** — Save local overrides with file uploads (booklet, banner, feature image, OG image, rental agreement) (`update()`)
- **Sub-location AJAX dropdown** — Returns sub-locations filtered by parent location for dynamic dropdown (`getSubLocationList()`)
- **No create/delete** — Guesty properties are managed in Guesty; admin can only edit local overrides

---

## 14. Admin — Booking Request Management Module
**Controller:** `Admin/BookingRequestController`

- **Booking listing** — Display all booking requests in admin table (`index()`)
- **Admin booking creation form** — Form for admin to manually create a booking (`create()`)
- **Admin booking store** — Save admin-created booking with full guest details, property, dates, pricing (`store()`)
- **Booking edit form** — Edit existing booking details (`edit()`)
- **Booking update** — Update booking request details (`update()`)
- **Booking cancellation/delete** — Cancel and delete a booking; sends cancellation email to guest; if Guesty booking, cancels in Guesty PMS (`destroy()`)
- **Booking confirmation** — Mark a booking as confirmed (`confirmed()`)
- **Single property booking view** — View bookings filtered by a specific property (`singlePropertyBookoing()`)
- **AJAX check-in/check-out data** — Returns availability/pricing data for admin booking forms via AJAX (`getCheckinCheckoutDataGaurav()`)

---

## 15. Admin — Property Calendar (iCal) Module
**Controller:** `Admin/PropertyCalendarController`

- **Calendar event listing** — Display all iCal events for a specific property (`index()`)
- **iCal import list** — View all imported iCal feed URLs for a property (`importlist()`)
- **Add iCal import** — Add a new external iCal feed URL with unique validation; auto-refreshes events on save (`store()`)
- **Refresh single iCal feed** — Manually trigger re-import of events from a specific iCal URL (`importlistRefresh()`)
- **Refresh self iCal** — Regenerate the property's own iCal export file (`selfIcalRefresh()`)
- **Delete iCal import** — Remove an iCal feed URL and all its associated events (`destroy()`)

---

## 16. Admin — Property Rates Module
**Controller:** `Admin/PropertyRateController`

- **Rate group listing** — Display all rate groups (seasonal pricing periods) for a property (`index()`)
- **Rate group creation form** — Form with start date, end date, pricing type selection (`create()`)
- **Rate group store** — Save new rate group with date overlap validation (unique start/end per property); supports two pricing modes: **default** (single price + base price) or **day-of-week** (Mon–Sun individual prices); auto-generates per-day `PropertyRate` records via `ModelHelper::saveSIngleDatePropertyRate()` (`store()`)
- **Rate group edit** — Edit existing rate period (`edit()`)
- **Rate group update** — Update rate group with same overlap validation and pricing mode logic; re-generates per-day rates (`update()`)
- **Rate group delete** — Delete rate group and all associated per-day `PropertyRate` records (`destroy()`)
- **Rate group duplicate** — Clone a rate group record (`copyData()`)
- **Timestamp storage** — Stores Unix timestamps alongside date strings for efficient range queries

---

## 17. Admin — Property Rooms Module
**Controller:** `Admin/PropertyRoomController`

- **Room listing** — Display all rooms for a specific property (`index()`)
- **Room creation** — Add new room with title, image, banner image (`store()`)
- **Room edit** — Edit existing room details (`edit()`)
- **Room update** — Update room record with image uploads (`update()`)
- **Room delete** — Remove a room (`destroy()`)
- **Room activate** — Set room `room_status` to active (`active()`)
- **Room deactivate** — Set room `room_status` to inactive (`deactive()`)
- **Room duplicate** — Clone a room record (`copyData()`)

---

## 18. Admin — Property Room Items Module
**Controller:** `Admin/PropertyRoomItemController`

- **Room item listing** — Display all items (beds, furniture) within a specific room (`index()`)
- **Room item creation** — Add new item to a room with image upload (`store()`)
- **Room item edit** — Edit existing room item (`edit()`)
- **Room item update** — Update room item with image upload (`update()`)
- **Room item delete** — Remove a room item (`destroy()`)
- **Room item activate** — Set `sub_room_status` to active (`active()`)
- **Room item deactivate** — Set `sub_room_status` to inactive (`deactive()`)
- **Room item duplicate** — Clone a room item (`copyData()`)

---

## 19. Admin — Property Amenity Groups Module
**Controller:** `Admin/PropertyAmenityGroupController`

- **Amenity group listing** — Display amenity groups (categories) for a property (`index()`)
- **Amenity group creation** — Add new amenity category with name, image, banner (`store()`)
- **Amenity group edit** — Edit existing amenity group (`edit()`)
- **Amenity group update** — Update amenity group with image uploads (`update()`)
- **Amenity group delete** — Delete amenity group and all child amenities (cascade delete via `PropertyAmenity::where()->delete()`) (`destroy()`)
- **Amenity group activate** — Set group status to active (`active()`)
- **Amenity group deactivate** — Set group status to inactive (`deactive()`)
- **Amenity group duplicate** — Clone an amenity group (`copyData()`)

---

## 20. Admin — Property Amenities Module
**Controller:** `Admin/PropertyAmenityController`

- **Amenity listing** — Display amenities within a specific amenity group for a property (`index()`)
- **Amenity creation** — Add new amenity with name, image, banner (`store()`)
- **Amenity edit** — Edit existing amenity (`edit()`)
- **Amenity update** — Update amenity with image uploads (`update()`)
- **Amenity delete** — Remove an amenity (`destroy()`)
- **Amenity activate** — Set status to active (`active()`)
- **Amenity deactivate** — Set status to inactive (`deactive()`)
- **Amenity duplicate** — Clone an amenity (`copyData()`)
- **Nested navigation** — Three-level navigation: Property → Amenity Group → Amenity

---

## 21. Admin — Attractions Module
**Controller:** `Admin/AttractionController`

- **Attraction listing** — Display all attractions in admin table (`index()`)
- **Attraction creation** — Add new attraction with SEO URL, name, category, location, description, image uploads (`store()`)
- **Attraction edit** — Edit existing attraction (`edit()`)
- **Attraction update** — Update attraction record with conditional image re-upload (`update()`)
- **Attraction delete** — Remove an attraction (`destroy()`)
- **Image upload** — Handles image and banner image uploads for attractions
- **Validation** — Requires `seo_url` (unique), `name`, `attraction_category_id`

---

## 22. Admin — Attraction Categories Module
**Controller:** `Admin/AttractionCategoryController`

- **Category listing** — Display all attraction categories (`index()`)
- **Category creation** — Add new category with image upload (`store()`)
- **Category edit** — Edit category (`edit()`)
- **Category update** — Update category; deletes old image file on re-upload (`update()`)
- **Category delete** — Remove category (`destroy()`)
- **Old image cleanup** — Explicitly deletes old image file from disk when replaced

---

## 23. Admin — Blog Module
**Controller:** `Admin/BlogController`

- **Blog listing** — Display all blog posts (`index()`)
- **Blog creation** — Add new blog post with SEO URL, title, content, category, featured image, banner (`store()`)
- **Blog edit** — Edit existing blog post (`edit()`)
- **Blog update** — Update blog post with image uploads (`update()`)
- **Blog delete** — Remove blog post (`destroy()`)
- **Blog activate** — Publish/show a blog post (`active()`)
- **Blog deactivate** — Unpublish/hide a blog post (`deactive()`)
- **Blog duplicate** — Clone a blog post (`copyData()`)

---

## 24. Admin — Blog Categories Module
**Controller:** `Admin/BlogCategoryController`

- **Category listing** — Display all blog categories (`index()`)
- **Category creation** — Add new category with image upload (`store()`)
- **Category edit** — Edit category (`edit()`)
- **Category update** — Update category with image upload (`update()`)
- **Category delete** — Remove category (`destroy()`)
- **Category duplicate** — Clone a category (`copyData()`)

---

## 25. Admin — CMS Pages Module
**Controller:** `Admin/CmsController`

- **CMS page listing** — Display all CMS pages (`index()`)
- **CMS page creation** — Add new CMS page with 23+ image upload fields for different sections (banner, sections, vacation images, etc.) (`store()`)
- **CMS page edit** — Edit CMS page (`edit()`)
- **CMS page update** — Update CMS page with conditional image re-uploads for all section fields (`update()`)
- **CMS page delete** — Remove CMS page (`destroy()`)
- **Multi-section image management** — Handles `bannerImage`, `section_image_one` through `section_image_ten`, `vacation_one_image` through `vacation_four_image`, `ogimage`, and more
- **SEO URL validation** — Unique slug validation per CMS page

---

## 26. Admin — SEO CMS Pages Module
**Controller:** `Admin/SeoCmsController`

- **SEO page listing** — Display all SEO-focused CMS pages (`index()`)
- **SEO page creation** — Add new SEO page with unique `seo_url`, multiple image sections, attraction sections (JSON array), video sections (JSON array) (`store()`)
- **SEO page edit** — Edit SEO page (`edit()`)
- **SEO page update** — Update SEO page with image uploads; handles dynamic attraction section and video section arrays with image preservation on edit (`update()`)
- **SEO page delete** — Remove SEO page (`destroy()`)
- **Dynamic attraction sections** — Repeatable attraction content blocks with heading, title, image, content stored as JSON
- **Dynamic video sections** — Repeatable video content blocks with heading, link, content stored as JSON
- **Multiple image fields** — `image`, `bannerImage`, `vacation_one_image` through `vacation_four_image`

---

## 27. Admin — Landing CMS Pages Module
**Controller:** `Admin/LandingCmsController`

- **Landing page listing** — Display all landing CMS pages (`index()`)
- **Landing page creation** — Add new landing page with unique `seo_url`, attraction sections (JSON), video sections (JSON), location/attraction image uploads (`store()`)
- **Landing page edit** — Edit landing page (`edit()`)
- **Landing page update** — Update landing page with image handling and JSON section preservation (`update()`)
- **Landing page delete** — Remove landing page (`destroy()`)
- **Dynamic attraction sections** — Repeatable blocks stored as JSON with per-item image upload
- **Dynamic video sections** — Repeatable blocks stored as JSON
- **Hidden image preservation** — Uses hidden fields to preserve existing images when no new upload provided on edit

---

## 28. Admin — Gallery Module
**Controller:** `Admin/GalleryController`

- **Gallery listing** — Display all gallery items (`index()`)
- **Gallery creation** — Add new gallery item with image upload (`store()`)
- **Gallery edit** — Edit gallery item (`edit()`)
- **Gallery update** — Update gallery item with image upload (`update()`)
- **Gallery delete** — Remove gallery item (`destroy()`)
- **Gallery activate** — Show gallery item (`active()`)
- **Gallery deactivate** — Hide gallery item (`deactive()`)
- **Gallery duplicate** — Clone a gallery item (`copyData()`)

---

## 29. Admin — Slider Module
**Controller:** `Admin/SliderController`

- **Slider listing** — Display all homepage slider items (`index()`)
- **Slider creation** — Add new slider with image upload (`store()`)
- **Slider edit** — Edit slider item (`edit()`)
- **Slider update** — Update slider with image upload (`update()`)
- **Slider delete** — Remove slider item (`destroy()`)
- **Slider activate** — Enable slider item (`active()`)
- **Slider deactivate** — Disable slider item (`deactive()`)
- **Slider duplicate** — Clone a slider item (`copyData()`)

---

## 30. Admin — FAQ Module
**Controller:** `Admin/FaqController`

- **FAQ listing** — Display all FAQ entries (`index()`)
- **FAQ creation** — Add new FAQ with question/answer (`store()`)
- **FAQ edit** — Edit FAQ entry (`edit()`)
- **FAQ update** — Update FAQ record (`update()`)
- **FAQ delete** — Remove FAQ entry (`destroy()`)
- **FAQ duplicate** — Clone an FAQ entry (`copyData()`)

---

## 31. Admin — Testimonials Module
**Controller:** `Admin/TestimonialController`

- **Testimonial listing** — Display all testimonials (`index()`)
- **Testimonial creation** — Add new testimonial with image upload (`store()`)
- **Testimonial edit** — Edit testimonial (`edit()`)
- **Testimonial update** — Update testimonial with image upload (`update()`)
- **Testimonial delete** — Remove testimonial (`destroy()`)
- **Testimonial duplicate** — Clone a testimonial (`copyData()`)

---

## 32. Admin — Our Team Module
**Controller:** `Admin/OurTeamController`

- **Team member listing** — Display all team members (`index()`)
- **Team member creation** — Add new team member with validation (`seo_url` unique, `first_name`, `last_name`, `email`), profile image upload (`store()`)
- **Team member edit** — Edit team member (`edit()`)
- **Team member update** — Update team member with validation, image upload (`update()`)
- **Team member delete** — Remove team member (`destroy()`)

---

## 33. Admin — Our Clients Module
**Controller:** `Admin/OurClientController`

- **Client listing** — Display all client logos/entries (`index()`)
- **Client creation** — Add new client with image upload (`store()`)
- **Client edit** — Edit client entry (`edit()`)
- **Client update** — Update client with image upload (`update()`)
- **Client delete** — Remove client entry (`destroy()`)

---

## 34. Admin — Services Module
**Controller:** `Admin/ServiceController`

- **Service listing** — Display all services (`index()`)
- **Service creation** — Add new service with unique `seo_url` validation, image upload (`store()`)
- **Service edit** — Edit service (`edit()`)
- **Service update** — Update service with `seo_url` uniqueness check (excluding current record), image upload (`update()`)
- **Service delete** — Remove service (`destroy()`)

---

## 35. Admin — Coupons Module
**Controller:** `Admin/CouponController`

- **Coupon listing** — Display all discount coupons (`index()`)
- **Coupon creation** — Add new coupon with validation: `code` (required), `type` (percentage/fixed), `property_id`; image upload (`store()`)
- **Coupon edit** — Edit coupon (`edit()`)
- **Coupon update** — Update coupon with same validation rules (`update()`)
- **Coupon delete** — Remove coupon (`destroy()`)
- **Property-specific coupons** — Coupons can be tied to a specific property

---

## 36. Admin — Email Templates Module
**Controller:** `Admin/EmailTempleteController`

- **Template listing** — Display all email templates (`index()`)
- **Template creation** — Add new email template (no validation enforced) (`store()`)
- **Template edit** — Edit email template with placeholder support (`edit()`)
- **Template update** — Update template content (`update()`)
- **Template delete** — Remove email template (`destroy()`)
- **Dynamic placeholders** — Templates support 30+ placeholders that are replaced at send time

---

## 37. Admin — Contact Us Requests Module
**Controller:** `Admin/ContactusRequestController`

- **Request listing** — Display all contact form submissions (`index()`)
- **Request view** — View individual contact request details (`show()`)
- **Request delete** — Remove a contact request (`destroy()`)
- **Standard CRUD** — Full create/edit available in admin (likely unused; submissions come from public form)

---

## 38. Admin — Newsletter Module
**Controller:** `Admin/NewsLetterController`

- **Subscriber listing** — Display all newsletter subscribers (`index()`)
- **Subscriber add** — Manually add newsletter subscriber with unique email validation (`store()`)
- **Subscriber edit** — Edit subscriber details (`edit()`)
- **Subscriber update** — Update subscriber email (`update()`)
- **Subscriber delete** — Remove subscriber (`destroy()`)

---

## 39. Admin — Onboarding Requests Module
**Controller:** `Admin/OnboardingRequestController`

- **Request listing** — Display all onboarding requests (`index()`)
- **Request creation** — Add new onboarding request with file uploads (file1, file2) (`store()`)
- **Request edit** — Edit onboarding request (`edit()`)
- **Request update** — Update onboarding request with file re-uploads (`update()`)
- **Request delete** — Remove onboarding request (`destroy()`)

---

## 40. Admin — Property Management Requests Module
**Controller:** `Admin/PropertyManagementRequestController`

- **Request listing** — Display all property management inquiries (`index()`)
- **Request creation** — Add new property management request (`store()`)
- **Request edit** — Edit request details (`edit()`)
- **Request update** — Update request (`update()`)
- **Request delete** — Remove request (`destroy()`)

---

## 41. Admin — Welcome Packages Module
**Controller:** `Admin/WelcomePackageController`

- **Package listing** — Display all welcome packages (`index()`)
- **Package creation** — Add new welcome package with image and banner image upload (`store()`)
- **Package edit** — Edit package content (`edit()`)
- **Package update** — Update package with image re-uploads (`update()`)
- **Package delete** — Remove welcome package (`destroy()`)
- **Automated delivery** — Welcome packages are automatically emailed to guests before check-in (via `ICalController::sendWelcomePackage()`)

---

## 42. Admin — Maximize Assets Module
**Controller:** `Admin/MaximizeAssetController`

- **Asset listing** — Display all maximize asset entries (`index()`)
- **Asset creation** — Add new asset entry (`store()`)
- **Asset edit** — Edit asset entry (`edit()`)
- **Asset update** — Update asset entry (`update()`)
- **Asset delete** — Remove asset entry (`destroy()`)

---

## 43. Admin — User Management Module
**Controller:** `Admin/UserController`

- **User listing** — Display all admin users (`index()`)
- **User creation** — Add new admin user with password bcrypt hashing (`store()`)
- **User edit** — Edit user details (`edit()`)
- **User update** — Update user; password only updated if provided (non-empty), otherwise preserves existing; uses bcrypt hashing (`update()`)
- **User delete** — Remove user (`destroy()`)

---

## 44. Admin — CKEditor Module
**Controller:** `Admin/CkeditorController`

- **CKEditor page** — Renders standalone CKEditor view (`index()`)
- **Image upload for CKEditor** — Handles inline image uploads from CKEditor WYSIWYG editor; saves to `public/uploads/` with timestamp-appended filename; returns JavaScript callback for CKEditor integration (`upload()`)

---

## 45. Helper — GuestyApi Features
**Class:** `App\Helper\GuestyApi` (Facade: `GuestyApi`)

- **OAuth2 client credentials authentication** — `client_id` + `client_secret` → Bearer token for Open API
- **Dual API support** — Open API (`open-api.guesty.com`) + Booking Engine API (`booking.guesty.com`)
- **Property data fetching** — Full property details including pictures, amenities, terms
- **Availability querying** — Date-range availability checks with calendar data
- **Per-night pricing** — Day-by-day rate retrieval from Guesty calendar
- **Fee calculation** — Retrieve and compute cleaning fees, additional fees, taxes
- **Guest management** — Create and retrieve guest records in Guesty
- **Reservation lifecycle** — Create → Confirm → Attach Payment → Mark Paid
- **Quote engine** — Multiple quote generation methods for pricing calculations
- **Booking data sync** — Push local booking data to Guesty PMS
- **Search engine** — Property search with availability filtering
- **Generic API wrapper** — Flexible `customAPI()` for ad-hoc Guesty calls
- **Paginated data retrieval** — Skip/limit pagination for large booking datasets

---

## 46. Helper — General Utility Features
**Class:** `App\Helper\Helper` (Facade: `Helper`)

- **Gross amount calculation (local properties)** — Computes total price for local properties using `PropertyRateGroup` day-of-week pricing or flat rate, plus cleaning fee, tax, and additional fees (`getGrossAmountData()`)
- **Guesty availability check** — Checks Guesty property availability for date range with detailed day-by-day breakdown (`getGrossDataCheckerDays()`)
- **Property rate calendar data** — Returns per-day pricing data for front-end calendar display (`getPropertyRates()`)
- **Property list retrieval** — Gets all properties as key-value pairs for dropdowns (`getPropertyList()`, `getPropertyListNew()`)
- **SEO URL resolver** — Looks up CMS page by SEO URL slug (`getSeoUrlGet()`)
- **Category retrieval** — Gets categories for dropdowns (`getCategoryGet()`)
- **Select list builders** — Generic helper methods for building HTML select options from various models

---

## 47. Helper — ModelHelper Features
**Class:** `App\Helper\ModelHelper` (Facade: `ModelHelper`)

- **Post-payment processing** — Updates booking status, sends confirmation emails to guest and admin, triggers Guesty reservation creation and confirmation (`finalEmailAndUpdateBookingPayment()`)
- **Settings retrieval** — Gets key-value site settings from `BasicSetting` model (`getDataFromSetting()`)
- **Per-day rate generation** — Generates individual `PropertyRate` records for each day in a rate group's date range, supporting both flat and day-of-week pricing (`saveSIngleDatePropertyRate()`)
- **Product image retrieval** — Gets the primary image for a property  (`getImageByProduct()`)
- **Select list helpers** — Various model-to-dropdown helper methods
- **Location/category lookups** — Helper methods for location and category data retrieval

---

## 48. Helper — MailHelper Features
**Class:** `App\Helper\MailHelper` (Facade: `MailHelper`)

- **Template-based email rendering** — Fetches email template by slug, replaces 30+ placeholders with dynamic data: `{name}`, `{email}`, `{phone}`, `{check_in}`, `{check_out}`, `{property_name}`, `{total_amount}`, `{booking_id}`, `{adults}`, `{children}`, `{infants}`, `{pets}`, `{cleaning_fee}`, `{tax}`, `{base_price}`, `{additional_fee}`, `{message}`, `{star_rating}`, `{comment}`, `{review_link}`, `{site_name}`, `{site_url}`, `{coupon_discount}`, etc. (`emailSender()`)
- **Direct HTML email** — Sends arbitrary HTML content as email with optional multiple file attachments, configurable from/to/subject (`emailSenderByController()`)
- **Gmail SMTP integration** — Sends through Gmail SMTP with app-specific credentials

---

## 49. Helper — LiveCart (iCal Engine) Features
**Class:** `App\Helper\LiveCart` (Facade: `LiveCart`)

- **iCal date conflict check** — Checks if requested check-in/check-out dates conflict with existing iCal events for a property (`iCalDataCheckInCheckOut()`)
- **Detailed iCal conflict check** — More granular date-range overlap detection (`iCalDataCheckInCheckOutCheckinCheckout()`)
- **Single feed refresh** — Parses a single iCal URL, extracts VEVENT components, upserts into `IcalEvent` table (`refreshIcalData()`)
- **Bulk feed refresh** — Iterates all `IcalImportList` records and refreshes each feed (`allIcalImportListRefresh()`)
- **iCal file generation** — Creates `.ics` export file for a property from its bookings and blocked dates (`getFileIcalFileData()`)
- **iCal parsing** — Manual string parsing of VCALENDAR/VEVENT format (DTSTART, DTEND, SUMMARY, UID)

---

## 50. Authentication & Authorization Module

- **Admin login** — Laravel default auth scaffolding for admin panel access
- **Auth middleware** — `auth` middleware on all admin routes
- **Guest middleware** — `guest` middleware on login/register routes
- **Password hashing** — bcrypt for all stored passwords
- **Session-based auth** — Standard Laravel session authentication
- **AdminLTE integration** — Admin panel uses `jeroennoten/laravel-adminlte` with custom menu filter (`MyMenuFilter`)

---

## 51. API Endpoints Module
**File:** `routes/api.php`

- **AJAX property data** — `/getPropertyData/{id}` — Fetch property data from Guesty
- **AJAX booking data** — `/getBookingData/{id}` — Fetch booking data from Guesty
- **AJAX Guesty token** — `/getToken` — Get Guesty Open API OAuth2 token
- **AJAX booking token** — `/getBookingToken` — Get Guesty Booking Engine token
- **AJAX availability quote (public)** — `/checkAjaxGetQuoteData` — Check availability and get pricing quote
- **AJAX availability quote (admin create)** — `/adminCheckAjaxGetQuoteData` — Admin availability/quote check for new bookings
- **AJAX availability quote (admin edit)** — `/adminCheckAjaxGetQuoteDataEdit` — Admin availability/quote check for edit bookings
- **AJAX review data** — `/getReviewData/{id}` — Fetch reviews from Guesty
- **AJAX sub-location list** — `/getSubLocationList` — Get sub-locations for dropdown
- **Cron endpoints** — `/setCronJob`, `/setPriceLab`, etc. — Unprotected cron job triggers
- **Booking save** — `/saveBookingData` — Save booking via API
- **Payment update** — `/updatepaymentBookingData` — Update payment on booking via API

---

## Summary Statistics

| Category | Count |
|---|---|
| **Public-facing features** | ~25 |
| **Booking & payment features** | ~20 |
| **Guesty integration features** | ~20 |
| **iCal/Calendar features** | ~11 |
| **Automated email features** | ~15 |
| **Admin CRUD modules** | 32 |
| **Admin module features** | ~180 |
| **Helper/utility features** | ~35 |
| **API endpoints** | ~12 |
| **Total unique features** | **~318** |
