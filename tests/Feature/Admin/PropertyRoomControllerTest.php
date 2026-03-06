<?php

namespace Tests\Feature\Admin;

use App\Models\Property;
use App\Models\PropertyRoom;
use App\Models\PropertyRoomItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyRoomControllerTest extends TestCase
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

    public function test_index_lists_rooms(): void
    {
        PropertyRoom::create([
            'property_id' => $this->property->id,
            'room_title' => 'Master Bedroom',
        ]);

        $response = $this->actingAs($this->admin)
            ->get("/client-login/properties/{$this->property->id}/rooms");

        $response->assertStatus(200);
    }

    public function test_create_page_loads(): void
    {
        $response = $this->actingAs($this->admin)
            ->get("/client-login/properties/{$this->property->id}/rooms/create");

        $response->assertStatus(200);
    }

    public function test_store_creates_room(): void
    {
        $response = $this->actingAs($this->admin)
            ->post("/client-login/properties/{$this->property->id}/rooms/create", [
                'room_title' => 'Guest Room',
            ]);

        $response->assertRedirect(route('properties-group-rooms', $this->property->id));
        $this->assertDatabaseHas('property_rooms', ['room_title' => 'Guest Room']);
    }

    public function test_store_validates_required_title(): void
    {
        $response = $this->actingAs($this->admin)
            ->post("/client-login/properties/{$this->property->id}/rooms/create", []);

        $response->assertSessionHasErrors(['room_title']);
    }

    public function test_destroy_cascades_to_items(): void
    {
        $room = PropertyRoom::create([
            'property_id' => $this->property->id,
            'room_title' => 'Delete Room',
        ]);

        PropertyRoomItem::create([
            'room_id' => $room->id,
            'sub_room_title' => 'Sub Room',
        ]);

        $this->actingAs($this->admin)
            ->delete("/client-login/properties/{$this->property->id}/rooms/{$room->id}/delete");

        $this->assertDatabaseMissing('property_rooms', ['id' => $room->id]);
        $this->assertDatabaseMissing('property_room_items', ['room_id' => $room->id]);
    }

    public function test_active_activates_room(): void
    {
        $room = PropertyRoom::create([
            'property_id' => $this->property->id,
            'room_title' => 'Inactive Room',
            'room_status' => 'inactive',
        ]);

        $this->actingAs($this->admin)
            ->get("/client-login/properties/{$this->property->id}/rooms/{$room->id}/active");

        $this->assertEquals('active', $room->fresh()->room_status);
    }

    public function test_deactive_deactivates_room(): void
    {
        $room = PropertyRoom::create([
            'property_id' => $this->property->id,
            'room_title' => 'Active Room',
            'room_status' => 'active',
        ]);

        $this->actingAs($this->admin)
            ->get("/client-login/properties/{$this->property->id}/rooms/{$room->id}/deactive");

        $this->assertEquals('inactive', $room->fresh()->room_status);
    }
}
