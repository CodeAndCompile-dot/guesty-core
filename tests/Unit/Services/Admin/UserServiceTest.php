<?php

namespace Tests\Unit\Services\Admin;

use App\Models\User;
use App\Repositories\Eloquent\UserRepository;
use App\Services\Admin\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $service;

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

        $repository = new UserRepository(new User);
        $this->service = new UserService($repository);
    }

    public function test_create_user_hashes_password(): void
    {
        $user = $this->service->createUser([
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'password' => 'secret123',
        ]);

        $this->assertTrue(Hash::check('secret123', $user->password));
        $this->assertDatabaseHas('users', ['email' => 'john@test.com']);
    }

    public function test_update_user_hashes_password_when_provided(): void
    {
        $user = User::create([
            'name' => 'Jane',
            'email' => 'jane@test.com',
            'password' => bcrypt('old'),
        ]);

        $this->service->updateUser($user->id, [
            'name' => 'Jane Updated',
            'password' => 'newpassword',
        ]);

        $user->refresh();
        $this->assertEquals('Jane Updated', $user->name);
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }

    public function test_update_user_preserves_password_when_empty(): void
    {
        $user = User::create([
            'name' => 'Bob',
            'email' => 'bob@test.com',
            'password' => bcrypt('original'),
        ]);

        $this->service->updateUser($user->id, [
            'name' => 'Bob Updated',
            'password' => '',
        ]);

        $user->refresh();
        $this->assertEquals('Bob Updated', $user->name);
        $this->assertTrue(Hash::check('original', $user->password));
    }

    public function test_delete_user(): void
    {
        $user = User::create([
            'name' => 'Delete Me',
            'email' => 'delete@test.com',
            'password' => bcrypt('pass'),
        ]);

        $this->service->deleteUser($user->id);

        $this->assertDatabaseMissing('users', ['email' => 'delete@test.com']);
    }

    public function test_change_password_success(): void
    {
        $user = User::create([
            'name' => 'Pass User',
            'email' => 'pass@test.com',
            'password' => bcrypt('oldpass'),
        ]);

        $result = $this->service->changePassword($user, 'oldpass', 'newpass');

        $this->assertTrue($result);
        $user->refresh();
        $this->assertTrue(Hash::check('newpass', $user->password));
    }

    public function test_change_password_fails_with_wrong_old_password(): void
    {
        $user = User::create([
            'name' => 'Fail User',
            'email' => 'fail@test.com',
            'password' => bcrypt('correct'),
        ]);

        $result = $this->service->changePassword($user, 'wrong', 'newpass');

        $this->assertFalse($result);
        $user->refresh();
        $this->assertTrue(Hash::check('correct', $user->password));
    }
}
