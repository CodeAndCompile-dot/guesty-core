<?php

namespace Tests\Unit\Communication;

use App\Models\BasicSetting;
use App\Models\BookingRequest;
use App\Models\Location;
use App\Models\Property;
use App\Services\Communication\EmailService;
use App\Services\Communication\WelcomePackageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class WelcomePackageServiceTest extends TestCase
{
    use RefreshDatabase;

    protected WelcomePackageService $service;

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
        BasicSetting::create(['name' => 'welcome_package_send_day', 'value' => '3']);
        BasicSetting::create(['name' => 'welcome_package_receiving_mail', 'value' => 'admin@example.com']);

        $location = Location::create([
            'name' => 'Loc', 'seo_url' => 'loc', 'parent_id' => null, 'status' => 1,
        ]);

        $this->property = Property::create([
            'name' => 'Beach House', 'seo_url' => 'beach-house',
            'location_id' => $location->id, 'status' => 1,
        ]);

        $this->service = app(WelcomePackageService::class);
    }

    public function test_process_sends_welcome_emails_for_qualifying_bookings(): void
    {
        Mail::fake();

        $checkinDate = Carbon::today()->addDays(3)->toDateString();

        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => $checkinDate,
            'checkout' => Carbon::today()->addDays(6)->toDateString(),
            'name' => 'Alice', 'email' => 'alice@example.com',
            'mobile' => '1234567890',
            'booking_status' => 'booking-confirmed',
            'welcome_email' => 'false',
            'booking_type_admin' => 'invoice',
        ]);

        $count = $this->service->process();

        $this->assertEquals(1, $count);
        $this->assertDatabaseHas('booking_requests', [
            'name' => 'Alice',
            'welcome_email' => 'true',
        ]);
    }

    public function test_process_skips_already_sent_welcome_email(): void
    {
        Mail::fake();

        $checkinDate = Carbon::today()->addDays(3)->toDateString();

        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => $checkinDate,
            'checkout' => Carbon::today()->addDays(6)->toDateString(),
            'name' => 'Bob', 'email' => 'bob@example.com',
            'booking_status' => 'booking-confirmed',
            'welcome_email' => 'true',
            'booking_type_admin' => 'invoice',
        ]);

        $count = $this->service->process();

        $this->assertEquals(0, $count);
    }

    public function test_process_skips_non_invoice_bookings(): void
    {
        Mail::fake();

        $checkinDate = Carbon::today()->addDays(3)->toDateString();

        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => $checkinDate,
            'checkout' => Carbon::today()->addDays(6)->toDateString(),
            'name' => 'Carol', 'email' => 'carol@example.com',
            'booking_status' => 'booking-confirmed',
            'welcome_email' => 'false',
            'booking_type_admin' => 'manual',
        ]);

        $count = $this->service->process();

        $this->assertEquals(0, $count);
    }

    public function test_process_skips_wrong_checkin_date(): void
    {
        Mail::fake();

        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => Carbon::today()->addDays(10)->toDateString(),
            'checkout' => Carbon::today()->addDays(13)->toDateString(),
            'name' => 'Dave', 'email' => 'dave@example.com',
            'booking_status' => 'booking-confirmed',
            'welcome_email' => 'false',
            'booking_type_admin' => 'invoice',
        ]);

        $count = $this->service->process();

        $this->assertEquals(0, $count);
    }

    public function test_process_returns_zero_when_no_qualifying_bookings(): void
    {
        Mail::fake();

        $count = $this->service->process();

        $this->assertEquals(0, $count);
    }
}
