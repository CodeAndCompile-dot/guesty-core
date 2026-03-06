<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure users table has image columns for the tests
        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'image')) {
            Schema::table('users', function ($table) {
                $table->string('image')->nullable();
                $table->string('bannerImage')->nullable();
            });
        }
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/client-login/login');

        $response->assertStatus(200);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => 'secret123',
        ]);

        $response = $this->post('/client-login', [
            'email' => 'admin@test.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/client-login/guesty_properties');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => 'secret123',
        ]);

        $response = $this->post('/client-login', [
            'email' => 'admin@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect();
        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => 'secret123',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/client-login/sliders');

        $response->assertRedirect('/client-login/login');
    }

    public function test_default_login_path_returns_404(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(404);
    }

    public function test_authenticated_user_is_redirected_from_login_page(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => 'secret123',
        ]);

        $response = $this->actingAs($user)->get('/client-login/login');

        $response->assertRedirect('/client-login/guesty_properties');
    }
}
