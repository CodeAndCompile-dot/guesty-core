<?php

namespace Tests\Feature\Admin;

use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationControllerTest extends TestCase
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

    public function test_index_lists_locations(): void
    {
        Location::create(['name' => 'Downtown', 'seo_url' => 'downtown']);

        $response = $this->actingAs($this->admin)->get('/client-login/locations');

        $response->assertStatus(200);
    }

    public function test_create_page_loads(): void
    {
        $response = $this->actingAs($this->admin)->get('/client-login/locations/create');

        $response->assertStatus(200);
    }

    public function test_store_creates_location(): void
    {
        $response = $this->actingAs($this->admin)->post('/client-login/locations', [
            'name'    => 'Lakeside',
            'seo_url' => 'lakeside',
        ]);

        $response->assertRedirect(route('locations.index'));
        $this->assertDatabaseHas('locations', ['seo_url' => 'lakeside']);
    }

    public function test_store_validates_unique_seo_url(): void
    {
        Location::create(['name' => 'Existing', 'seo_url' => 'existing']);

        $response = $this->actingAs($this->admin)->post('/client-login/locations', [
            'name'    => 'New',
            'seo_url' => 'existing',
        ]);

        $response->assertSessionHasErrors(['seo_url']);
    }

    public function test_edit_page_loads(): void
    {
        $location = Location::create(['name' => 'Edit', 'seo_url' => 'edit']);

        $response = $this->actingAs($this->admin)->get("/client-login/locations/{$location->id}/edit");

        $response->assertStatus(200);
    }

    public function test_update_modifies_location(): void
    {
        $location = Location::create(['name' => 'Old', 'seo_url' => 'old']);

        $response = $this->actingAs($this->admin)->put("/client-login/locations/{$location->id}", [
            'name'    => 'New',
            'seo_url' => 'new-url',
        ]);

        $response->assertRedirect(route('locations.index'));
        $this->assertDatabaseHas('locations', ['id' => $location->id, 'name' => 'New']);
    }

    public function test_destroy_deletes_location(): void
    {
        $location = Location::create(['name' => 'Delete', 'seo_url' => 'delete']);

        $response = $this->actingAs($this->admin)->delete("/client-login/locations/{$location->id}");

        $response->assertRedirect(route('locations.index'));
        $this->assertDatabaseMissing('locations', ['id' => $location->id]);
    }
}
