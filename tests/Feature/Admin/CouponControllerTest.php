<?php

namespace Tests\Feature\Admin;

use App\Models\Coupon;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponControllerTest extends TestCase
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

    public function test_index_lists_coupons(): void
    {
        Coupon::create([
            'name' => 'Discount',
            'code' => 'SAVE10',
            'amount' => '10',
            'type' => 'fixed',
            'property_id' => $this->property->id,
        ]);

        $response = $this->actingAs($this->admin)->get('/client-login/coupons');

        $response->assertStatus(200);
    }

    public function test_create_page_loads(): void
    {
        $response = $this->actingAs($this->admin)->get('/client-login/coupons/create');

        $response->assertStatus(200);
    }

    public function test_store_creates_coupon(): void
    {
        $response = $this->actingAs($this->admin)->post('/client-login/coupons', [
            'name'        => 'Summer Sale',
            'code'        => 'SUMMER25',
            'amount'      => '25',
            'type'        => 'percentage',
            'property_id' => $this->property->id,
        ]);

        $response->assertRedirect(route('coupons.index'));
        $this->assertDatabaseHas('coupons', ['code' => 'SUMMER25']);
    }

    public function test_store_validates_unique_code(): void
    {
        Coupon::create([
            'name' => 'Existing',
            'code' => 'EXISTING',
            'amount' => '10',
            'type' => 'fixed',
            'property_id' => $this->property->id,
        ]);

        $response = $this->actingAs($this->admin)->post('/client-login/coupons', [
            'name'        => 'Duplicate',
            'code'        => 'EXISTING',
            'type'        => 'fixed',
            'property_id' => $this->property->id,
        ]);

        $response->assertSessionHasErrors(['code']);
    }

    public function test_edit_page_loads(): void
    {
        $coupon = Coupon::create([
            'name' => 'Edit Me',
            'code' => 'EDIT',
            'amount' => '5',
            'type' => 'fixed',
            'property_id' => $this->property->id,
        ]);

        $response = $this->actingAs($this->admin)->get("/client-login/coupons/{$coupon->id}/edit");

        $response->assertStatus(200);
    }

    public function test_destroy_deletes_coupon(): void
    {
        $coupon = Coupon::create([
            'name' => 'Delete',
            'code' => 'DELETE',
            'amount' => '5',
            'type' => 'fixed',
            'property_id' => $this->property->id,
        ]);

        $response = $this->actingAs($this->admin)->delete("/client-login/coupons/{$coupon->id}");

        $response->assertRedirect(route('coupons.index'));
        $this->assertDatabaseMissing('coupons', ['id' => $coupon->id]);
    }
}
