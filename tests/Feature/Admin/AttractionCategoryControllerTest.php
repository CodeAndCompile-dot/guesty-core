<?php

namespace Tests\Feature\Admin;

use App\Models\AttractionCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttractionCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_displays_categories(): void
    {
        AttractionCategory::create(['name' => 'Outdoor', 'seo_url' => 'outdoor']);

        $response = $this->actingAs($this->user)->get(route('attraction-categories.index'));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_create_view_loads(): void
    {
        $response = $this->actingAs($this->user)->get(route('attraction-categories.create'));

        $response->assertStatus(200);
    }

    public function test_store_creates_category(): void
    {
        $data = [
            'name'    => 'Museums',
            'seo_url' => 'museums',
        ];

        $response = $this->actingAs($this->user)->post(route('attraction-categories.store'), $data);

        $response->assertRedirect(route('attraction-categories.index'));
        $this->assertDatabaseHas('attraction_categories', ['seo_url' => 'museums']);
    }

    public function test_store_requires_name_and_seo_url(): void
    {
        $response = $this->actingAs($this->user)->post(route('attraction-categories.store'), []);

        $response->assertSessionHasErrors(['name', 'seo_url']);
    }

    public function test_edit_view_loads(): void
    {
        $cat = AttractionCategory::create(['name' => 'Edit Cat', 'seo_url' => 'edit-cat']);

        $response = $this->actingAs($this->user)->get(route('attraction-categories.edit', $cat));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_update_modifies_category(): void
    {
        $cat = AttractionCategory::create(['name' => 'Old', 'seo_url' => 'old-cat']);

        $response = $this->actingAs($this->user)->put(route('attraction-categories.update', $cat), [
            'name'    => 'Updated',
            'seo_url' => 'updated-cat',
        ]);

        $response->assertRedirect(route('attraction-categories.index'));
        $this->assertDatabaseHas('attraction_categories', ['id' => $cat->id, 'name' => 'Updated']);
    }

    public function test_destroy_deletes_category(): void
    {
        $cat = AttractionCategory::create(['name' => 'Remove', 'seo_url' => 'remove-cat']);

        $response = $this->actingAs($this->user)->delete(route('attraction-categories.destroy', $cat));

        $response->assertRedirect(route('attraction-categories.index'));
        $this->assertDatabaseMissing('attraction_categories', ['id' => $cat->id]);
    }
}
