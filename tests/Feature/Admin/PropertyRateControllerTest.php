<?php

namespace Tests\Feature\Admin;

use App\Models\Property;
use App\Models\PropertyRateGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyRateControllerTest extends TestCase
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

    public function test_index_lists_rate_groups(): void
    {
        PropertyRateGroup::create([
            'property_id'          => $this->property->id,
            'name_of_price'        => 'Summer Rate',
            'type_of_price'        => 'default',
            'start_date'           => '2025-06-01',
            'end_date'             => '2025-08-31',
            'start_date_timestamp' => strtotime('2025-06-01'),
            'end_date_timestamp'   => strtotime('2025-08-31'),
        ]);

        $response = $this->actingAs($this->admin)
            ->get("/client-login/properties/{$this->property->id}/rates");

        $response->assertStatus(200);
    }

    public function test_create_page_loads(): void
    {
        $response = $this->actingAs($this->admin)
            ->get("/client-login/properties/{$this->property->id}/rates/create");

        $response->assertStatus(200);
    }

    public function test_store_creates_rate_group_and_daily_rates(): void
    {
        $response = $this->actingAs($this->admin)
            ->post("/client-login/properties/{$this->property->id}/rates/create", [
                'name_of_price'  => 'Weekend Special',
                'type_of_price'  => 'default',
                'start_date'     => '2025-07-01',
                'end_date'       => '2025-07-03',
                'price'          => 200,
            ]);

        $response->assertRedirect(route('properties-rates', $this->property->id));
        $this->assertDatabaseHas('properties_rates_group', ['name_of_price' => 'Weekend Special']);
        // 3 days: July 1, 2, 3
        $this->assertEquals(3, \App\Models\PropertyRate::where('property_id', $this->property->id)->count());
    }

    public function test_store_validates_required_dates(): void
    {
        $response = $this->actingAs($this->admin)
            ->post("/client-login/properties/{$this->property->id}/rates/create", []);

        $response->assertSessionHasErrors(['start_date', 'end_date']);
    }

    public function test_destroy_deletes_group_and_daily_rates(): void
    {
        $group = PropertyRateGroup::create([
            'property_id'          => $this->property->id,
            'name_of_price'        => 'Delete Me',
            'type_of_price'        => 'default',
            'start_date'           => '2025-06-01',
            'end_date'             => '2025-06-01',
            'start_date_timestamp' => strtotime('2025-06-01'),
            'end_date_timestamp'   => strtotime('2025-06-01'),
        ]);

        \App\Models\PropertyRate::create([
            'property_id'          => $this->property->id,
            'rate_group_id'        => $group->id,
            'single_date'          => '2025-06-01',
            'single_date_timestamp' => strtotime('2025-06-01'),
            'price'                => 100,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete("/client-login/properties/{$this->property->id}/rates/{$group->id}/delete");

        $response->assertRedirect(route('properties-rates', $this->property->id));
        $this->assertDatabaseMissing('properties_rates_group', ['id' => $group->id]);
        $this->assertDatabaseMissing('property_rates', ['rate_group_id' => $group->id]);
    }
}
