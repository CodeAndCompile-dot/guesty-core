<?php

namespace Tests\Unit\Communication;

use App\Models\BasicSetting;
use App\Models\BookingRequest;
use App\Models\Location;
use App\Models\Property;
use App\Services\Communication\ReminderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ReminderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ReminderService $service;

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
        BasicSetting::create(['name' => 'second_how_many_days', 'value' => '30']);
        BasicSetting::create(['name' => 'second_third_how_many_days', 'value' => '60']);
        BasicSetting::create(['name' => 'third_how_many_days', 'value' => '30']);
        BasicSetting::create(['name' => 'reminder_package_receiving_mail', 'value' => 'admin@example.com']);

        $location = Location::create([
            'name' => 'Loc', 'seo_url' => 'loc', 'parent_id' => null, 'status' => 1,
        ]);

        $this->property = Property::create([
            'name' => 'Mountain Lodge', 'seo_url' => 'mountain-lodge',
            'location_id' => $location->id, 'status' => 1,
        ]);

        $this->service = app(ReminderService::class);
    }

    // ------------------------------------------------------------------
    // Scenario 1: 2-payment, 1 done
    // ------------------------------------------------------------------

    public function test_scenario1_two_payment_one_done_sends_reminder(): void
    {
        Mail::fake();

        $checkinDate = Carbon::today()->addDays(30)->toDateString();

        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => $checkinDate,
            'checkout' => Carbon::today()->addDays(35)->toDateString(),
            'name' => 'Alice', 'email' => 'alice@example.com',
            'booking_status' => 'booking-confirmed',
            'booking_type_admin' => 'invoice',
            'total_payment' => 2,
            'how_many_payment_done' => 1,
            'reminder_email' => 'false',
            'third_reminder_email' => 'false',
        ]);

        $count = $this->service->process();

        $this->assertEquals(1, $count);
        $this->assertDatabaseHas('booking_requests', [
            'name' => 'Alice',
            'reminder_email' => 'true',
        ]);
    }

    // ------------------------------------------------------------------
    // Scenario 2: 3-payment, 1 done
    // ------------------------------------------------------------------

    public function test_scenario2_three_payment_one_done_sends_reminder(): void
    {
        Mail::fake();

        $checkinDate = Carbon::today()->addDays(60)->toDateString();

        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => $checkinDate,
            'checkout' => Carbon::today()->addDays(65)->toDateString(),
            'name' => 'Bob', 'email' => 'bob@example.com',
            'booking_status' => 'booking-confirmed',
            'booking_type_admin' => 'invoice',
            'total_payment' => 3,
            'how_many_payment_done' => 1,
            'reminder_email' => 'false',
            'third_reminder_email' => 'false',
        ]);

        $count = $this->service->process();

        $this->assertEquals(1, $count);
        $this->assertDatabaseHas('booking_requests', [
            'name' => 'Bob',
            'reminder_email' => 'true',
        ]);
    }

    // ------------------------------------------------------------------
    // Scenario 3: 3-payment, 2 done
    // ------------------------------------------------------------------

    public function test_scenario3_three_payment_two_done_sends_third_reminder(): void
    {
        Mail::fake();

        $checkinDate = Carbon::today()->addDays(30)->toDateString();

        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => $checkinDate,
            'checkout' => Carbon::today()->addDays(35)->toDateString(),
            'name' => 'Carol', 'email' => 'carol@example.com',
            'booking_status' => 'booking-confirmed',
            'booking_type_admin' => 'invoice',
            'total_payment' => 3,
            'how_many_payment_done' => 2,
            'reminder_email' => 'true',
            'third_reminder_email' => 'false',
        ]);

        $count = $this->service->process();

        $this->assertEquals(1, $count);
        $this->assertDatabaseHas('booking_requests', [
            'name' => 'Carol',
            'third_reminder_email' => 'true',
        ]);
    }

    // ------------------------------------------------------------------
    // Edge cases
    // ------------------------------------------------------------------

    public function test_skips_non_invoice_bookings(): void
    {
        Mail::fake();

        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => Carbon::today()->addDays(30)->toDateString(),
            'checkout' => Carbon::today()->addDays(35)->toDateString(),
            'name' => 'Dave', 'email' => 'dave@example.com',
            'booking_status' => 'booking-confirmed',
            'booking_type_admin' => 'manual',
            'total_payment' => 2,
            'how_many_payment_done' => 1,
            'reminder_email' => 'false',
        ]);

        $count = $this->service->process();

        $this->assertEquals(0, $count);
    }

    public function test_skips_already_reminded_bookings(): void
    {
        Mail::fake();

        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => Carbon::today()->addDays(30)->toDateString(),
            'checkout' => Carbon::today()->addDays(35)->toDateString(),
            'name' => 'Eve', 'email' => 'eve@example.com',
            'booking_status' => 'booking-confirmed',
            'booking_type_admin' => 'invoice',
            'total_payment' => 2,
            'how_many_payment_done' => 1,
            'reminder_email' => 'true',
        ]);

        $count = $this->service->process();

        $this->assertEquals(0, $count);
    }

    public function test_returns_zero_with_no_qualifying_bookings(): void
    {
        Mail::fake();

        $count = $this->service->process();

        $this->assertEquals(0, $count);
    }
}
