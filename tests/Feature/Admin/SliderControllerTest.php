<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SliderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create users table
        if (! Schema::hasTable('users')) {
            Schema::create('users', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->timestamps();
            });
        }

        // Create sliders table
        if (! Schema::hasTable('sliders')) {
            Schema::create('sliders', function ($table) {
                $table->id();
                $table->string('title')->nullable();
                $table->string('link')->nullable();
                $table->string('image')->nullable();
                $table->unsignedBigInteger('cms_id')->nullable();
                $table->text('description')->nullable();
                $table->string('status', 100)->default('active');
                $table->timestamps();
            });
        }

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_index_shows_sliders(): void
    {
        \App\Models\Slider::create(['title' => 'Test Slider']);

        $response = $this->actingAs($this->admin)
            ->get('/client-login/sliders');

        $response->assertStatus(200);
    }

    public function test_store_creates_slider(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/client-login/sliders', [
                'title' => 'New Slider',
                'link' => 'https://example.com',
                'description' => 'Test description',
            ]);

        $response->assertRedirect(route('sliders.index'));
        $response->assertSessionHas('success', 'Successfully Added');
        $this->assertDatabaseHas('sliders', ['title' => 'New Slider']);
    }

    public function test_update_modifies_slider(): void
    {
        $slider = \App\Models\Slider::create(['title' => 'Original']);

        $response = $this->actingAs($this->admin)
            ->put("/client-login/sliders/{$slider->id}", [
                'title' => 'Updated',
            ]);

        $response->assertRedirect(route('sliders.index'));
        $response->assertSessionHas('success', 'Successfully Updated');
        $this->assertDatabaseHas('sliders', ['title' => 'Updated']);
    }

    public function test_destroy_deletes_slider(): void
    {
        $slider = \App\Models\Slider::create(['title' => 'Delete Me']);

        $response = $this->actingAs($this->admin)
            ->delete("/client-login/sliders/{$slider->id}");

        $response->assertRedirect(route('sliders.index'));
        $response->assertSessionHas('success', 'Successfully Deleted');
        $this->assertDatabaseMissing('sliders', ['id' => $slider->id]);
    }

    public function test_copy_data_duplicates_slider(): void
    {
        $slider = \App\Models\Slider::create(['title' => 'Copy Me', 'link' => 'test']);

        $response = $this->actingAs($this->admin)
            ->get("/client-login/sliders/copydata/{$slider->id}");

        $response->assertRedirect(route('sliders.index'));
        $response->assertSessionHas('success', 'Successfully Coppied');
        $this->assertEquals(2, \App\Models\Slider::count());
    }

    public function test_active_sets_status(): void
    {
        $slider = \App\Models\Slider::create(['title' => 'Test', 'status' => 'deactive']);

        $response = $this->actingAs($this->admin)
            ->get("/client-login/sliders/active/{$slider->id}");

        $response->assertRedirect(route('sliders.index'));
        $slider->refresh();
        $this->assertEquals('active', $slider->status);
    }

    public function test_deactive_sets_status(): void
    {
        $slider = \App\Models\Slider::create(['title' => 'Test', 'status' => 'active']);

        $response = $this->actingAs($this->admin)
            ->get("/client-login/sliders/deactive/{$slider->id}");

        $response->assertRedirect(route('sliders.index'));
        $slider->refresh();
        $this->assertEquals('deactive', $slider->status);
    }

    public function test_unauthenticated_access_redirects(): void
    {
        $response = $this->get('/client-login/sliders');

        $response->assertRedirect();
    }
}
