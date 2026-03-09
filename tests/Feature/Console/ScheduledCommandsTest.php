<?php

namespace Tests\Feature\Console;

use App\Models\BasicSetting;
use App\Models\BookingRequest;
use App\Models\Location;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ScheduledCommandsTest extends TestCase
{
    use RefreshDatabase;

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
        BasicSetting::create(['name' => 'second_how_many_days', 'value' => '30']);
        BasicSetting::create(['name' => 'second_third_how_many_days', 'value' => '60']);
        BasicSetting::create(['name' => 'third_how_many_days', 'value' => '30']);
        BasicSetting::create(['name' => 'reminder_package_receiving_mail', 'value' => 'admin@example.com']);
        BasicSetting::create(['name' => 'review_send_day', 'value' => '3']);
        BasicSetting::create(['name' => 'review_receiving_mail', 'value' => 'admin@example.com']);

        $location = Location::create([
            'name' => 'Loc', 'seo_url' => 'loc', 'parent_id' => null, 'status' => 1,
        ]);

        $this->property = Property::create([
            'name' => 'Test Villa', 'seo_url' => 'test-villa',
            'location_id' => $location->id, 'status' => 1,
        ]);
    }

    // ------------------------------------------------------------------
    // communication:send-welcome-packages
    // ------------------------------------------------------------------

    public function test_welcome_packages_command_runs_and_outputs_count(): void
    {
        Mail::fake();

        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => Carbon::today()->addDays(3)->toDateString(),
            'checkout' => Carbon::today()->addDays(6)->toDateString(),
            'name' => 'Alice', 'email' => 'alice@example.com',
            'booking_status' => 'booking-confirmed',
            'welcome_email' => 'false',
            'booking_type_admin' => 'invoice',
        ]);

        $this->artisan('communication:send-welcome-packages')
            ->expectsOutputToContain('Welcome-package emails sent: 1')
            ->assertExitCode(0);
    }

    public function test_welcome_packages_command_zero_when_none_qualify(): void
    {
        Mail::fake();

        $this->artisan('communication:send-welcome-packages')
            ->expectsOutputToContain('Welcome-package emails sent: 0')
            ->assertExitCode(0);
    }

    // ------------------------------------------------------------------
    // communication:send-reminders
    // ------------------------------------------------------------------

    public function test_reminders_command_runs_and_outputs_count(): void
    {
        Mail::fake();

        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => Carbon::today()->addDays(30)->toDateString(),
            'checkout' => Carbon::today()->addDays(35)->toDateString(),
            'name' => 'Bob', 'email' => 'bob@example.com',
            'booking_status' => 'booking-confirmed',
            'booking_type_admin' => 'invoice',
            'total_payment' => 2,
            'how_many_payment_done' => 1,
            'reminder_email' => 'false',
        ]);

        $this->artisan('communication:send-reminders')
            ->expectsOutputToContain('Reminder emails sent: 1')
            ->assertExitCode(0);
    }

    // ------------------------------------------------------------------
    // communication:send-review-requests
    // ------------------------------------------------------------------

    public function test_review_requests_command_runs_and_outputs_count(): void
    {
        Mail::fake();

        BookingRequest::create([
            'property_id' => $this->property->id,
            'checkin' => Carbon::today()->subDays(6)->toDateString(),
            'checkout' => Carbon::today()->subDays(3)->toDateString(),
            'name' => 'Carol', 'email' => 'carol@example.com',
            'booking_status' => 'booking-confirmed',
            'review_email' => 'false',
            'booking_type_admin' => 'invoice',
        ]);

        $this->artisan('communication:send-review-requests')
            ->expectsOutputToContain('Review-request emails sent: 1')
            ->assertExitCode(0);
    }
}
