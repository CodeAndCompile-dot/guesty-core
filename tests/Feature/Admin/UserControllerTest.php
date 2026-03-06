<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserControllerTest extends TestCase
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
                $table->string('image')->nullable();
                $table->string('bannerImage')->nullable();
                $table->timestamps();
            });
        }

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_store_requires_name_email_password(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/client-login/users', []);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_store_creates_user_with_hashed_password(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/client-login/users', [
                'name' => 'New User',
                'email' => 'new@test.com',
                'password' => 'secret',
            ]);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'Successfully Added');

        $user = User::where('email', 'new@test.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('secret', $user->password));
    }

    public function test_store_validates_unique_email(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/client-login/users', [
                'name' => 'Dup User',
                'email' => 'admin@test.com', // already exists
                'password' => 'secret',
            ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_update_does_not_require_password(): void
    {
        $user = User::create([
            'name' => 'Edit Me',
            'email' => 'edit@test.com',
            'password' => bcrypt('old'),
        ]);

        $response = $this->actingAs($this->admin)
            ->put("/client-login/users/{$user->id}", [
                'name' => 'Edited',
                'email' => 'edit@test.com',
            ]);

        $response->assertRedirect(route('users.index'));
        $user->refresh();
        $this->assertEquals('Edited', $user->name);
        $this->assertTrue(Hash::check('old', $user->password));
    }

    public function test_destroy_deletes_user(): void
    {
        $user = User::create([
            'name' => 'Delete User',
            'email' => 'del@test.com',
            'password' => bcrypt('p'),
        ]);

        $response = $this->actingAs($this->admin)
            ->delete("/client-login/users/{$user->id}");

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['email' => 'del@test.com']);
    }
}
