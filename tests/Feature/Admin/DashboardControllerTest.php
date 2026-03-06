<?php

namespace Tests\Feature\Admin;

use App\Models\BasicSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
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

        if (! Schema::hasTable('basic_settings')) {
            Schema::create('basic_settings', function ($table) {
                $table->id();
                $table->string('name');
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_dashboard_redirects_to_guesty_properties(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/client-login');

        $response->assertRedirect('/client-login/guesty_properties');
    }

    public function test_setting_page_loads(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/client-login/setting');

        $response->assertStatus(200);
    }

    public function test_setting_post_saves_settings(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/client-login/setting', [
                'input' => [
                    'site_name' => 'My Site',
                    'site_phone' => '555-1234',
                ],
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Setting updated successfully');

        $this->assertDatabaseHas('basic_settings', ['name' => 'site_name', 'value' => 'My Site']);
        $this->assertDatabaseHas('basic_settings', ['name' => 'site_phone', 'value' => '555-1234']);
    }

    public function test_change_password_page_loads(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/client-login/change-password');

        $response->assertStatus(200);
    }

    public function test_change_password_with_correct_old_password(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/client-login/change-password', [
                'old_password' => 'password',
                'new_password' => 'newpassword',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Password updated successfully');

        $this->admin->refresh();
        $this->assertTrue(Hash::check('newpassword', $this->admin->password));
    }

    public function test_change_password_with_wrong_old_password(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/client-login/change-password', [
                'old_password' => 'wrongpassword',
                'new_password' => 'newpassword',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('danger', 'Password Wrong');

        $this->admin->refresh();
        $this->assertTrue(Hash::check('password', $this->admin->password));
    }

    public function test_media_center_loads(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/client-login/media-center');

        $response->assertStatus(200);
    }
}
