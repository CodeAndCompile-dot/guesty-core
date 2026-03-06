<?php

namespace Tests\Feature\Admin;

use App\Models\Property;
use App\Models\PropertyAmenity;
use App\Models\PropertyAmenityGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyAmenityGroupControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Property $property;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $this->property = Property::create([
            'name' => 'Test Property',
            'seo_url' => 'test-property',
        ]);
    }

    public function test_index_lists_amenity_groups(): void
    {
        PropertyAmenityGroup::create([
            'property_id' => $this->property->id,
            'name' => 'Kitchen',
        ]);

        $response = $this->actingAs($this->admin)
            ->get("/client-login/properties/{$this->property->id}/group-amenities");

        $response->assertStatus(200);
    }

    public function test_create_page_loads(): void
    {
        $response = $this->actingAs($this->admin)
            ->get("/client-login/properties/{$this->property->id}/group-amenities/create");

        $response->assertStatus(200);
    }

    public function test_store_creates_amenity_group(): void
    {
        $response = $this->actingAs($this->admin)
            ->post("/client-login/properties/{$this->property->id}/group-amenities/create", [
                'name' => 'Pool Area',
            ]);

        $response->assertRedirect(route('properties.edit', $this->property->id));
        $this->assertDatabaseHas('property_amenity_groups', ['name' => 'Pool Area']);
    }

    public function test_destroy_cascades_to_amenities(): void
    {
        $group = PropertyAmenityGroup::create([
            'property_id' => $this->property->id,
            'name' => 'Cascade Group',
        ]);

        PropertyAmenity::create([
            'property_amenity_id' => $group->id,
            'name' => 'WiFi',
        ]);

        $this->actingAs($this->admin)
            ->get("/client-login/properties/{$this->property->id}/group-amenities/{$group->id}/delete");

        $this->assertDatabaseMissing('property_amenity_groups', ['id' => $group->id]);
        $this->assertDatabaseMissing('property_amenities', ['property_amenity_id' => $group->id]);
    }

    public function test_active_activates_group(): void
    {
        $group = PropertyAmenityGroup::create([
            'property_id' => $this->property->id,
            'name' => 'Inactive',
            'status' => 'false',
        ]);

        $this->actingAs($this->admin)
            ->get("/client-login/properties/{$this->property->id}/group-amenities/{$group->id}/active");

        $this->assertEquals('true', $group->fresh()->status);
    }

    public function test_deactive_deactivates_group(): void
    {
        $group = PropertyAmenityGroup::create([
            'property_id' => $this->property->id,
            'name' => 'Active',
            'status' => 'true',
        ]);

        $this->actingAs($this->admin)
            ->get("/client-login/properties/{$this->property->id}/group-amenities/{$group->id}/deactive");

        $this->assertEquals('false', $group->fresh()->status);
    }
}
