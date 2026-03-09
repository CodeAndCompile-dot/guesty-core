<?php

namespace Tests\Unit\Communication;

use App\Models\BasicSetting;
use App\Models\BookingRequest;
use App\Models\Location;
use App\Models\Property;
use App\Services\Communication\ReviewRequestService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ReviewRequestServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ReviewRequestService $service;

    protected Property $property;

    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('basic_settings')) {
            Schema::create('basic_settings', function ($table) {
                $table->id();
                $table->string('name');
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        BasicSetting::create(['name' => 'mail_from', 'value' => 'test@example.com']);
        BasicSetting::create(['name' => 'mail_from_name', 'value' => 'Test System']);
        BasicSetting::create(['name' => 'review_send_day', 'value' => '3']);
        BasicSetting::create(['name' => 'review_receiving_mail', 'value' => 'admin@example.com']);

        $location = Location::create([
            'name' => 'Loc', 'seo_url' => 'loc', 'parent_id' => null, 'status' => 1,
        ]);

        $this->property = Property::create([
            'name' => 'Lake Cabin', 'seo_url' => 'lake-cabin',
            'location_id' => $location->id, 'status' => 1,
        ]);

        $this->service = app(ReviewRequestService::class);
    }

    public function test_process_sends_review_requests_for_qualifying_bookings(): void
    {
        Mail::fake();

        $checkoutDate = Carbon::today()->subDays(3)->toDateString();

        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => Carbon::today()->subDays(6)->toDateString(),
            'checkout' => $checkoutDate,
            'name' => 'Alice', 'email' => 'alice@example.com',
            'booking_status' => 'booking-confirmed',
            'review_email' => 'false',
            'booking_type_admin' => 'invoice',
        ]);

        $count = $this->service->process();

        $this->assertEquals(1, $count);
        $this->assertDatabaseHas('booking_requests', [
            'name' => 'Alice',
            'review_email' => 'true',
        ]);
    }

    public function test_process_skips_already_reviewed(): void
    {
        Mail::fake();

        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => Carbon::today()->subDays(6)->toDateString(),
            'checkout' => Carbon::today()->subDays(3)->toDateString(),
            'name' => 'Bob', 'email' => 'bob@example.com',
            'booking_status' => 'booking-confirmed',
            'review_email' => 'true',
            'booking_type_admin' => 'invoice',
        ]);

        $count = $this->service->process();

        $this->assertEquals(0, $count);
    }

    public function test_process_skips_wrong_checkout_date(): void
    {
        Mail::fake();

        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => Carbon::today()->subDays(20)->toDateString(),
            'checkout' => Carbon::today()->subDays(15)->toDateString(),
            'name' => 'Carol', 'email' => 'carol@example.com',
            'booking_status' => 'booking-confirmed',
            'review_email' => 'false',
            'booking_type_admin' => 'invoice',
        ]);

        $count = $this->service->process();

        $this->assertEquals(0, $count);
    }

    public function test_process_skips_non_invoice_bookings(): void
    {
        Mail::fake();

        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => Carbon::today()->subDays(6)->toDateString(),
            'checkout' => Carbon::today()->subDays(3)->toDateString(),
            'name' => 'Dave', 'email' => 'dave@example.com',
            'booking_status' => 'booking-confirmed',
            'review_email' => 'false',
            'booking_type_admin' => 'manual',
        ]);

        $count = $this->service->process();

        $this->assertEquals(0, $count);
    }

    public function test_returns_zero_when_no_qualifying_bookings(): void
    {
        Mail::fake();

        $count = $this->service->process();

        $this->assertEquals(0, $count);
    }
}
