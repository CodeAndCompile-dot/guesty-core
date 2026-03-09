<?php

namespace Tests\Feature\Public;

use App\Models\BasicSetting;
use App\Models\ContactusRequest;
use App\Models\EmailTemplete;
use App\Models\NewsLetter;
use App\Models\OnboardingRequest;
use App\Models\PropertyManagementRequest;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PublicFormsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    /*
    |----------------------------------------------------------------------
    | Contact Form
    |----------------------------------------------------------------------
    */

    public function test_contact_form_creates_record_and_redirects(): void
    {
        $response = $this->post(route('contactPost'), [
            'name'    => 'Jane Doe',
            'email'   => 'jane@example.com',
            'message' => 'Hello, I have a question.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('contactus_requests', ['email' => 'jane@example.com']);
    }

    public function test_contact_form_validates_required_fields(): void
    {
        $response = $this->post(route('contactPost'), []);

        $response->assertSessionHasErrors(['name', 'email', 'message']);
    }

    public function test_contact_form_silently_succeeds_for_blocked_email(): void
    {
        BasicSetting::create(['name' => 'blocked_email', 'value' => 'spammer@evil.com, blocked@test.com']);

        $response = $this->post(route('contactPost'), [
            'name'    => 'Spammer',
            'email'   => 'spammer@evil.com',
            'message' => 'Buy my stuff.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        // Record should NOT be created for blocked email
        $this->assertDatabaseMissing('contactus_requests', ['email' => 'spammer@evil.com']);
    }

    /*
    |----------------------------------------------------------------------
    | Property Management Form
    |----------------------------------------------------------------------
    */

    public function test_property_management_creates_record(): void
    {
        $response = $this->post(route('property-management-post'), [
            'email'      => 'owner@example.com',
            'first_name' => 'John',
            'last_name'  => 'Smith',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('property_management_requests', ['email' => 'owner@example.com']);
    }

    public function test_property_management_validates_required_fields(): void
    {
        $response = $this->post(route('property-management-post'), []);

        $response->assertSessionHasErrors(['email', 'first_name']);
    }

    /*
    |----------------------------------------------------------------------
    | Onboarding Form
    |----------------------------------------------------------------------
    */

    public function test_onboarding_creates_record(): void
    {
        $response = $this->post(route('onboardingPost'), [
            'email' => 'newclient@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('onboarding_requests', ['email' => 'newclient@example.com']);
    }

    public function test_onboarding_validates_email(): void
    {
        $response = $this->post(route('onboardingPost'), [
            'email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /*
    |----------------------------------------------------------------------
    | Newsletter
    |----------------------------------------------------------------------
    */

    public function test_newsletter_subscribe_returns_json_success(): void
    {
        $response = $this->postJson(route('newsletterPost'), [
            'email' => 'subscriber@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 200]);
        $this->assertDatabaseHas('newsletters', ['email' => 'subscriber@example.com']);
    }

    public function test_newsletter_duplicate_returns_400_json_status(): void
    {
        NewsLetter::create(['email' => 'dup@example.com']);

        $response = $this->postJson(route('newsletterPost'), [
            'email' => 'dup@example.com',
        ]);

        // Custom failedValidation returns HTTP 200 with {status: 400} in body
        $response->assertStatus(200);
        $response->assertJson(['status' => 400]);
    }

    public function test_newsletter_validates_email_required(): void
    {
        $response = $this->postJson(route('newsletterPost'), [
            'email' => '',
        ]);

        // Custom failedValidation returns HTTP 200 with {status: 400} in body
        $response->assertStatus(200);
        $response->assertJson(['status' => 400]);
    }

    /*
    |----------------------------------------------------------------------
    | Review Submit
    |----------------------------------------------------------------------
    */

    public function test_review_submit_creates_testimonial(): void
    {
        $response = $this->post(route('reviewSubmit'), [
            'name'    => 'Guest',
            'email'   => 'guest@example.com',
            'message' => 'Great stay!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('testimonials', [
            'email'   => 'guest@example.com',
            'message' => 'Great stay!',
        ]);
    }

    public function test_review_validates_required_fields(): void
    {
        $response = $this->post(route('reviewSubmit'), []);

        $response->assertSessionHasErrors(['name', 'email', 'message']);
    }
}
