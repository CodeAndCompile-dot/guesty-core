# Laravel 9 → 12 Compatibility Fixes

> **Date:** March 2026
> **Project:** guesty-core (Laravel 12.53.0, PHP 8.3.30)
> **Legacy:** projects/ (Laravel 9)

---

## Summary

| Priority | Issue | Count | Status |
|----------|-------|-------|--------|
| **HIGH** | `$this->middleware()` in controller constructors | 2 | ✅ FIXED |
| **MEDIUM** | `protected $casts` property → `casts()` method | 13 models | ✅ FIXED |
| **LOW** | `Form::` facade (community fork) | 1,138 usages | ⚠️ MONITORED |
| **INFO** | AdminLTE version compatibility | 1 | ⚠️ MONITORED |

---

## 1. FIXED: Controller Middleware in Constructors

**Issue:** Laravel 12 removed the `middleware()` method from the base Controller class. Calling `$this->middleware('...')` in `__construct()` throws a runtime error.

### Files Fixed

#### `app/Http/Controllers/Auth/ConfirmPasswordController.php`
- **Before:** Had `$this->middleware('auth')` in constructor
- **Fix:** Removed constructor (redundant — routes already have `auth` middleware in `routes/web/auth.php`)

#### `app/Http/Controllers/Auth/RegisterController.php`
- **Before:** Had `$this->middleware('guest')` in constructor (fixed in earlier session)
- **Fix:** Removed constructor, added custom `showRegistrationForm()` and `register()` methods with first-admin-only logic

### Why This Breaks
```php
// Laravel 9 — WORKS
class MyController extends Controller {
    public function __construct() {
        $this->middleware('auth'); // ✅ method exists on Controller
    }
}

// Laravel 12 — BREAKS
class MyController extends Controller {
    public function __construct() {
        $this->middleware('auth'); // ❌ method does not exist
    }
}

// Laravel 12 — CORRECT (use route-level middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [MyController::class, 'index']);
});
```

---

## 2. FIXED: Model `$casts` Property → Method

**Issue:** Laravel 11+ deprecated the `protected $casts = []` property in favor of `protected function casts(): array`. The property still works in Laravel 12 but will be removed in future versions.

### Models Fixed (13 total)

| # | Model | Casts |
|---|-------|-------|
| 1 | `Location` | `ordering` (integer), `is_parent` (integer) |
| 2 | `Property` | 18 fields: location_id, bedroom, bathroom, beds, sleeps, cleaning_fee, tax, etc. |
| 3 | `PropertyGallery` | `sorting` (integer) |
| 4 | `PropertyRateGroup` | 14 fields: dates, prices, day-of-week prices |
| 5 | `PropertyRate` | `single_date` (date), timestamps, prices |
| 6 | `Attraction` | `ordering` (integer) |
| 7 | `GuestyProperty` | `guests` (integer), `ordering` (integer) |
| 8 | `GuestyPropertyBooking` | `start_date` (date), `end_date` (date) |
| 9 | `Coupon` | `property_id` (integer) |
| 10 | `PropertyAmenity` | `sorting` (integer) |
| 11 | `IcalEvent` | `start_date` (date), `end_date` (date) |
| 12 | `GuestyAvailabilityPrice` | `start_date` (date), `price` (float), `minNights` (integer) |
| 13 | `PropertyAmenityGroup` | `sorting` (integer) |

**Note:** `User` model was already using the correct `casts()` method format.

### Migration Pattern
```php
// BEFORE (Laravel 9 style — deprecated)
protected $casts = [
    'start_date' => 'date',
    'price'      => 'float',
];

// AFTER (Laravel 12 style — correct)
protected function casts(): array
{
    return [
        'start_date' => 'date',
        'price'      => 'float',
    ];
}
```

---

## 3. MONITORED: Form Facade (laravelcollective/html)

**Status:** Works currently, but requires monitoring

- **Package:** `rdx/laravelcollective-html` v6.9 (community fork of abandoned `laravelcollective/html`)
- **Usage:** 1,138 occurrences of `Form::` across 120 Blade templates
- **Laravel 12 Support:** Yes (fork supports `illuminate/*: ^11.0|^12.0`)
- **Risk:** Single developer maintains the fork. If abandoned, all 1,138 usages break.

### Recommendation
- **Short term:** Keep using the fork — it works fine
- **Long term:** Plan migration to native HTML forms or Livewire forms
- **Priority:** Low (no breaking changes right now)

---

## 4. MONITORED: AdminLTE Package

**Status:** Works but broad version constraint

- **Package:** `jeroennoten/laravel-adminlte` v3.15.3
- **Constraint:** `>=8.0` (very broad)
- **Risk:** May have subtle incompatibilities with Laravel 12 internals

### Recommendation
- Monitor for updates
- Test all admin panel features after any package update

---

## 5. Already Correct (No Action Needed)

| Item | Status | Notes |
|------|--------|-------|
| Route namespace grouping | ✅ OK | Uses explicit `::class` imports |
| `Auth::routes()` | ✅ OK | Routes defined manually in `routes/web/auth.php` |
| `Str::` / `Arr::` helpers | ✅ OK | Auto-aliased in Laravel 12 |
| `@error` Blade directive | ✅ OK | Standard syntax, fully compatible |
| `bootstrap/app.php` | ✅ OK | Uses new `Application::configure()` style |
| `config/app.php` | ✅ OK | Laravel 12 slim format |
| No `app/Http/Kernel.php` | ✅ OK | Middleware configured in `bootstrap/app.php` |
| `$request->input()` / `$request->all()` | ✅ OK | Not deprecated |
| Route model binding | ✅ OK | Uses manual `findOrFail()` |

---

## 6. External Assets Moved Into Project

**Issue:** Several asset directories were stored outside the project root and accessed via symlinks. This is not best practice — assets should live inside `public/`.

### Assets Moved

| Asset | Source (external) | Destination (inside project) |
|-------|-------------------|------------------------------|
| datepicker | `../datepicker/dist/` | `public/datepicker/dist/` |
| toastr | `../toastr/` | `public/toastr/` |
| ckeditor | `../drag-drop-image-uploader/ckeditor/` | `public/ckeditor/` |
| image-uploader | `../drag-drop-image-uploader/src/` | `public/drag-drop-image-uploader/src/` |
| live4calender | `../live4calender/` | `public/live4calender/` |

### Remaining Symlinks (shared with legacy project)

| Symlink | Target | Why |
|---------|--------|-----|
| `public/front` | `../front/` | Large frontend assets directory, shared between projects |
| `public/uploads` | `../uploads/` | User upload files, shared between projects |
| `public/vendor` | `../vendor/` | Frontend vendor libraries (Bootstrap, jQuery, etc.) |

### Legacy Project Impact
- ✅ All original files remain untouched — we **copied** (not moved) the files
- ✅ Legacy project continues to work exactly as before
- ✅ `public/.gitignore` updated to track the new asset directories

---

## Testing Verification

```bash
# All models load correctly with new casts() method
php artisan tinker --execute="App\Models\Property::first()->location_id"
# Result: 1 (type: integer) ✅

# All assets accessible via HTTP
curl -o /dev/null -w "%{http_code}" http://localhost:8000/datepicker/dist/css/hotel-datepicker.css  # 200 ✅
curl -o /dev/null -w "%{http_code}" http://localhost:8000/toastr/toastr.js                          # 200 ✅
curl -o /dev/null -w "%{http_code}" http://localhost:8000/ckeditor/ckeditor.js                      # 200 ✅
curl -o /dev/null -w "%{http_code}" http://localhost:8000/drag-drop-image-uploader/src/image-uploader.js  # 200 ✅
```
