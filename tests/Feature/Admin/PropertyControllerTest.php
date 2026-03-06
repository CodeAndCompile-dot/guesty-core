<?php

namespace Tests\Feature\Admin;

use App\Models\Property;
use App\Models\PropertyGallery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);
    }

    public function test_index_lists_properties(): void
    {
        Property::create(['name' => 'Beach House', 'seo_url' => 'beach-house']);

        $response = $this->actingAs($this->admin)->get('/client-login/properties');

        $response->assertStatus(200);
    }

    public function test_create_page_loads(): void
    {
        $response = $this->actingAs($this->admin)->get('/client-login/properties/create');

        $response->assertStatus(200);
    }

    public function test_store_creates_property(): void
    {
        $response = $this->actingAs($this->admin)->post('/client-login/properties', [
            'name'    => 'Mountain Cabin',
            'seo_url' => 'mountain-cabin',
        ]);

        $response->assertRedirect(route('properties.index'));
        $this->assertDatabaseHas('properties', ['seo_url' => 'mountain-cabin']);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post('/client-login/properties', []);

        $response->assertSessionHasErrors(['name', 'seo_url']);
    }

    public function test_store_validates_unique_seo_url(): void
    {
        Property::create(['name' => 'Existing', 'seo_url' => 'existing-url']);

        $response = $this->actingAs($this->admin)->post('/client-login/properties', [
            'name'    => 'New Property',
            'seo_url' => 'existing-url',
        ]);

        $response->assertSessionHasErrors(['seo_url']);
    }

    public function test_edit_page_loads(): void
    {
        $property = Property::create(['name' => 'Edit Me', 'seo_url' => 'edit-me']);

        $response = $this->actingAs($this->admin)->get("/client-login/properties/{$property->id}/edit");

        $response->assertStatus(200);
    }

    public function test_update_modifies_property(): void
    {
        $property = Property::create(['name' => 'Old Name', 'seo_url' => 'old-name']);

        $response = $this->actingAs($this->admin)->put("/client-login/properties/{$property->id}", [
            'name'    => 'New Name',
            'seo_url' => 'new-name',
        ]);

        $response->assertRedirect(route('properties.index'));
        $this->assertDatabaseHas('properties', ['id' => $property->id, 'name' => 'New Name']);
    }

    public function test_destroy_deletes_property(): void
    {
        $property = Property::create(['name' => 'Delete Me', 'seo_url' => 'delete-me']);

        $response = $this->actingAs($this->admin)->delete("/client-login/properties/{$property->id}");

        $response->assertRedirect(route('properties.index'));
        $this->assertDatabaseMissing('properties', ['id' => $property->id]);
    }

    public function test_destroy_cascades_to_galleries(): void
    {
        $property = Property::create(['name' => 'Cascade', 'seo_url' => 'cascade']);
        PropertyGallery::create(['property_id' => $property->id, 'image' => 'test.jpg']);

        $this->actingAs($this->admin)->delete("/client-login/properties/{$property->id}");

        $this->assertDatabaseMissing('property_galleries', ['property_id' => $property->id]);
    }

    public function test_copydata_duplicates_property(): void
    {
        $property = Property::create(['name' => 'Original', 'seo_url' => 'original']);

        $response = $this->actingAs($this->admin)->get("/client-login/properties/copydata/{$property->id}");

        $response->assertRedirect(route('properties.index'));
        $this->assertEquals(2, Property::count());
    }

    public function test_active_activates_property(): void
    {
        $property = Property::create(['name' => 'Inactive', 'seo_url' => 'inactive', 'status' => 'false']);

        $this->actingAs($this->admin)->get("/client-login/properties/active/{$property->id}");

        $this->assertEquals(1, $property->fresh()->status);
    }

    public function test_deactive_deactivates_property(): void
    {
        $property = Property::create(['name' => 'Active', 'seo_url' => 'active', 'status' => 'true']);

        $this->actingAs($this->admin)->get("/client-login/properties/deactive/{$property->id}");

        $this->assertEquals(0, $property->fresh()->status);
    }

    public function test_unauthenticated_user_redirected(): void
    {
        $this->get('/client-login/properties')->assertRedirect(route('login'));
    }
}
