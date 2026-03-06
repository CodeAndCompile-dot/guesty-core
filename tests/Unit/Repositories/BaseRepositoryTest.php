<?php

namespace Tests\Unit\Repositories;

use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BaseRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected BaseRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test table for an anonymous model
        Schema::create('test_items', function ($table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        $model = new class extends Model
        {
            protected $table = 'test_items';

            protected $fillable = ['name', 'description', 'status'];
        };

        $this->repository = new BaseRepository($model);
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('test_items');

        parent::tearDown();
    }

    public function test_create_persists_record(): void
    {
        $record = $this->repository->create([
            'name' => 'Test Item',
            'description' => 'A test description',
        ]);

        $this->assertDatabaseHas('test_items', ['name' => 'Test Item']);
        $this->assertEquals('Test Item', $record->name);
    }

    public function test_all_returns_all_records(): void
    {
        $this->repository->create(['name' => 'Item 1']);
        $this->repository->create(['name' => 'Item 2']);
        $this->repository->create(['name' => 'Item 3']);

        $results = $this->repository->all();

        $this->assertCount(3, $results);
    }

    public function test_find_returns_record_by_id(): void
    {
        $created = $this->repository->create(['name' => 'Findable']);

        $found = $this->repository->find($created->id);

        $this->assertNotNull($found);
        $this->assertEquals('Findable', $found->name);
    }

    public function test_find_returns_null_for_missing_id(): void
    {
        $found = $this->repository->find(999);

        $this->assertNull($found);
    }

    public function test_find_or_fail_throws_for_missing_id(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->repository->findOrFail(999);
    }

    public function test_find_by_returns_matching_records(): void
    {
        $this->repository->create(['name' => 'Alpha', 'status' => 1]);
        $this->repository->create(['name' => 'Beta', 'status' => 0]);
        $this->repository->create(['name' => 'Gamma', 'status' => 1]);

        $active = $this->repository->findBy('status', 1);

        $this->assertCount(2, $active);
    }

    public function test_find_first_by_returns_single_match(): void
    {
        $this->repository->create(['name' => 'First', 'status' => 1]);
        $this->repository->create(['name' => 'Second', 'status' => 1]);

        $found = $this->repository->findFirstBy('name', 'First');

        $this->assertNotNull($found);
        $this->assertEquals('First', $found->name);
    }

    public function test_update_modifies_record(): void
    {
        $record = $this->repository->create(['name' => 'Original']);

        $result = $this->repository->update($record->id, ['name' => 'Updated']);

        $this->assertTrue($result);
        $this->assertDatabaseHas('test_items', ['id' => $record->id, 'name' => 'Updated']);
    }

    public function test_delete_removes_record(): void
    {
        $record = $this->repository->create(['name' => 'Deletable']);

        $result = $this->repository->delete($record->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('test_items', ['id' => $record->id]);
    }

    public function test_all_active_returns_only_active_records(): void
    {
        $this->repository->create(['name' => 'Active 1', 'status' => 1]);
        $this->repository->create(['name' => 'Inactive', 'status' => 0]);
        $this->repository->create(['name' => 'Active 2', 'status' => 1]);

        $active = $this->repository->allActive();

        $this->assertCount(2, $active);
        $this->assertTrue($active->every(fn ($item) => $item->status === 1));
    }

    public function test_activate_sets_status_to_one(): void
    {
        $record = $this->repository->create(['name' => 'Inactive', 'status' => 0]);

        $result = $this->repository->activate($record->id);

        $this->assertTrue($result);
        $this->assertDatabaseHas('test_items', ['id' => $record->id, 'status' => 1]);
    }

    public function test_deactivate_sets_status_to_zero(): void
    {
        $record = $this->repository->create(['name' => 'Active', 'status' => 1]);

        $result = $this->repository->deactivate($record->id);

        $this->assertTrue($result);
        $this->assertDatabaseHas('test_items', ['id' => $record->id, 'status' => 0]);
    }

    public function test_duplicate_creates_copy_without_id(): void
    {
        $original = $this->repository->create([
            'name' => 'Original',
            'description' => 'Original description',
            'status' => 1,
        ]);

        $copy = $this->repository->duplicate($original->id);

        $this->assertNotEquals($original->id, $copy->id);
        $this->assertEquals('Original', $copy->name);
        $this->assertEquals('Original description', $copy->description);
        $this->assertDatabaseCount('test_items', 2);
    }

    public function test_paginate_returns_paginated_results(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $this->repository->create(['name' => "Item {$i}"]);
        }

        $paginated = $this->repository->paginate(10);

        $this->assertCount(10, $paginated->items());
        $this->assertEquals(20, $paginated->total());
        $this->assertEquals(2, $paginated->lastPage());
    }
}
