<?php

namespace Tests\Feature\Admin;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FaqControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('users')) {
            Schema::create('users', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('faqs')) {
            Schema::create('faqs', function ($table) {
                $table->id();
                $table->text('question')->nullable();
                $table->text('answer')->nullable();
                $table->string('type')->nullable();
                $table->string('image')->nullable();
                $table->string('bannerImage')->nullable();
                $table->text('question_ger')->nullable();
                $table->text('answer_ger')->nullable();
                $table->timestamps();
            });
        }

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_store_requires_question(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/client-login/faqs', [
                'answer' => 'No question provided',
            ]);

        $response->assertSessionHasErrors('question');
    }

    public function test_store_creates_faq(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/client-login/faqs', [
                'question' => 'What is this?',
                'answer' => 'A test FAQ',
            ]);

        $response->assertRedirect(route('faqs.index'));
        $response->assertSessionHas('success', 'Successfully Added');
        $this->assertDatabaseHas('faqs', ['question' => 'What is this?']);
    }

    public function test_update_creates_faq(): void
    {
        $faq = Faq::create(['question' => 'Old Q', 'answer' => 'Old A']);

        $response = $this->actingAs($this->admin)
            ->put("/client-login/faqs/{$faq->id}", [
                'question' => 'New Q',
                'answer' => 'New A',
            ]);

        $response->assertRedirect(route('faqs.index'));
        $this->assertDatabaseHas('faqs', ['question' => 'New Q']);
    }

    public function test_copy_data_duplicates_faq(): void
    {
        $faq = Faq::create(['question' => 'Copy Q']);

        $response = $this->actingAs($this->admin)
            ->get("/client-login/faqs/copydata/{$faq->id}");

        $response->assertRedirect(route('faqs.index'));
        $this->assertEquals(2, Faq::count());
    }

    public function test_destroy_deletes_faq(): void
    {
        $faq = Faq::create(['question' => 'Delete Q']);

        $response = $this->actingAs($this->admin)
            ->delete("/client-login/faqs/{$faq->id}");

        $response->assertRedirect(route('faqs.index'));
        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }
}
