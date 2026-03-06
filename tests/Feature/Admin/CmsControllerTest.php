<?php

namespace Tests\Feature\Admin;

use App\Models\Cms;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CmsControllerTest extends TestCase
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

        if (! Schema::hasTable('cms')) {
            Schema::create('cms', function ($table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('seo_url')->nullable();
                $table->string('image')->nullable();
                $table->string('bannerImage')->nullable();
                $table->string('ogimage')->nullable();
                $table->text('longDescription')->nullable();
                $table->text('shortDescription')->nullable();
                $table->string('meta_title')->nullable();
                $table->text('meta_keywords')->nullable();
                $table->text('meta_description')->nullable();
                $table->timestamps();
            });
        }

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_store_requires_name_and_seo_url(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/client-login/cms', []);

        $response->assertSessionHasErrors(['name', 'seo_url']);
    }

    public function test_store_creates_cms(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/client-login/cms', [
                'name' => 'About Page',
                'seo_url' => 'about-us',
                'longDescription' => 'Content here',
            ]);

        $response->assertRedirect(route('cms.index'));
        $response->assertSessionHas('success', 'Successfully Added');
        $this->assertDatabaseHas('cms', ['name' => 'About Page', 'seo_url' => 'about-us']);
    }

    public function test_store_validates_unique_seo_url(): void
    {
        Cms::create(['name' => 'Existing', 'seo_url' => 'duplicate']);

        $response = $this->actingAs($this->admin)
            ->post('/client-login/cms', [
                'name' => 'New',
                'seo_url' => 'duplicate',
            ]);

        $response->assertSessionHasErrors('seo_url');
    }

    public function test_update_allows_same_seo_url_for_own_record(): void
    {
        $cms = Cms::create(['name' => 'Page', 'seo_url' => 'my-url']);

        $response = $this->actingAs($this->admin)
            ->put("/client-login/cms/{$cms->id}", [
                'name' => 'Updated Page',
                'seo_url' => 'my-url',
            ]);

        $response->assertRedirect(route('cms.index'));
        $response->assertSessionHas('success', 'Successfully Updated');
    }

    public function test_destroy_deletes_cms(): void
    {
        $cms = Cms::create(['name' => 'Delete Me', 'seo_url' => 'del']);

        $response = $this->actingAs($this->admin)
            ->delete("/client-login/cms/{$cms->id}");

        $response->assertRedirect(route('cms.index'));
        $this->assertDatabaseMissing('cms', ['id' => $cms->id]);
    }
}
