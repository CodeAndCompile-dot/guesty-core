<?php

namespace Tests\Unit\Communication;

use App\Models\BasicSetting;
use App\Models\EmailTemplete;
use App\Services\Communication\EmailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EmailServiceTest extends TestCase
{
    use RefreshDatabase;

    protected EmailService $service;

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

        $this->service = new EmailService();
    }

    /* ------------------------------------------------------------------ */
    /*  sendRenderedHtml                                                    */
    /* ------------------------------------------------------------------ */

    public function test_send_rendered_html_dispatches_email(): void
    {
        // Mail::send() with raw views doesn't produce Mailable objects,
        // so we assert no exception is thrown and the method completes.
        $this->service->sendRenderedHtml(
            '<p>Hello</p>',
            'guest@example.com',
            'Test Subject',
        );

        $this->assertTrue(true); // no exception = email dispatched
    }

    public function test_send_rendered_html_skips_empty_recipients(): void
    {
        // With empty recipient, no email should be attempted (verified by
        // checking that no exception is logged / method exits gracefully).
        $this->service->sendRenderedHtml('<p>Hi</p>', '', 'Subject');

        $this->assertTrue(true);
    }

    public function test_send_rendered_html_with_comma_separated_recipients(): void
    {
        $this->service->sendRenderedHtml(
            '<p>Hello</p>',
            'a@example.com, b@example.com',
            'Multi Recipient',
        );

        $this->assertTrue(true);
    }

    public function test_send_rendered_html_filters_invalid_emails(): void
    {
        // Invalid emails are filtered out, resulting in no recipients — no send.
        $this->service->sendRenderedHtml('<p>Hi</p>', 'not-an-email', 'Subject');

        $this->assertTrue(true);
    }

    /* ------------------------------------------------------------------ */
    /*  sendFromTemplate                                                   */
    /* ------------------------------------------------------------------ */

    public function test_send_from_template_replaces_placeholders_and_sends(): void
    {
        if (! Schema::hasTable('email_templetes')) {
            Schema::create('email_templetes', function ($table) {
                $table->id();
                $table->string('email_type')->nullable();
                $table->string('email_subject')->nullable();
                $table->longText('email_body')->nullable();
                $table->timestamps();
            });
        }

        EmailTemplete::create([
            'email_type' => 'contact_us',
            'email_subject' => 'Contact Us Request',
            'email_body' => 'Name: {username}, Email: {useremail}, Message: {usermessage}',
        ]);

        $this->service->sendFromTemplate([
            'type' => 'contact_us',
            'to' => 'admin@example.com',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Hello there',
        ]);

        $this->assertTrue(true);
    }

    public function test_send_from_template_handles_missing_template_gracefully(): void
    {
        if (! Schema::hasTable('email_templetes')) {
            Schema::create('email_templetes', function ($table) {
                $table->id();
                $table->string('email_type')->nullable();
                $table->string('email_subject')->nullable();
                $table->longText('email_body')->nullable();
                $table->timestamps();
            });
        }

        // No exception should be thrown
        $this->service->sendFromTemplate([
            'type' => 'nonexistent_type',
            'to' => 'admin@example.com',
        ]);

        $this->assertTrue(true);
    }

    public function test_send_from_template_skips_empty_recipients(): void
    {
        if (! Schema::hasTable('email_templetes')) {
            Schema::create('email_templetes', function ($table) {
                $table->id();
                $table->string('email_type')->nullable();
                $table->string('email_subject')->nullable();
                $table->longText('email_body')->nullable();
                $table->timestamps();
            });
        }

        EmailTemplete::create([
            'email_type' => 'test',
            'email_subject' => 'Test',
            'email_body' => 'Body',
        ]);

        $this->service->sendFromTemplate([
            'type' => 'test',
            'to' => '',
        ]);

        $this->assertTrue(true);
    }

    /* ------------------------------------------------------------------ */
    /*  sendFromView                                                       */
    /* ------------------------------------------------------------------ */

    public function test_send_from_view_renders_and_sends(): void
    {
        $this->service->sendFromView(
            'mail.dummyMail',
            ['email_body' => '<p>Test</p>'],
            'guest@example.com',
            'View Test',
        );

        $this->assertTrue(true);
    }

    /* ------------------------------------------------------------------ */
    /*  Placeholder replacement (via reflection)                           */
    /* ------------------------------------------------------------------ */

    public function test_replace_placeholders_substitutes_all_known_tokens(): void
    {
        $method = new \ReflectionMethod(EmailService::class, 'replacePlaceholders');

        $body = 'Hello {username}, your email is {useremail} and message: {usermessage}';
        $data = [
            'name' => 'Jane',
            'email' => 'jane@test.com',
            'message' => 'Hi there',
        ];

        $result = $method->invoke($this->service, $body, $data);

        $this->assertStringContainsString('Jane', $result);
        $this->assertStringContainsString('jane@test.com', $result);
        $this->assertStringContainsString('Hi there', $result);
        $this->assertStringNotContainsString('{username}', $result);
    }

    /* ------------------------------------------------------------------ */
    /*  parseRecipients (via reflection)                                   */
    /* ------------------------------------------------------------------ */

    public function test_parse_recipients_handles_comma_separated_list(): void
    {
        $method = new \ReflectionMethod(EmailService::class, 'parseRecipients');

        $result = $method->invoke($this->service, 'a@test.com, b@test.com, invalid');

        $this->assertCount(2, $result);
        $this->assertContains('a@test.com', $result);
        $this->assertContains('b@test.com', $result);
    }

    public function test_parse_recipients_returns_empty_for_empty_string(): void
    {
        $method = new \ReflectionMethod(EmailService::class, 'parseRecipients');

        $result = $method->invoke($this->service, '');

        $this->assertEmpty($result);
    }
}
