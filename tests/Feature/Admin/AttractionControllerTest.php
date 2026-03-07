<?php

namespace Tests\Feature\Admin;

use App\Models\Attraction;
use App\Models\AttractionCategory;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttractionControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_displays_attractions(): void
    {
        Attraction::create(['name' => 'Test Attr', 'seo_url' => 'test-attr']);

        $response = $this->actingAs($this->user)->get(route('attractions.index'));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_create_view_loads(): void
    {
        $response = $this->actingAs($this->user)->get(route('attractions.create'));

        $response->assertStatus(200);
    }

    public function test_store_creates_attraction(): void
    {
        $data = [
            'name'    => 'Crystal Bridges',
            'seo_url' => 'crystal-bridges',
        ];

        $response = $this->actingAs($this->user)->post(route('attractions.store'), $data);

        $response->assertRedirect(route('attractions.index'));
        $this->assertDatabaseHas('attractions', ['seo_url' => 'crystal-bridges']);
    }

    public function test_store_requires_name_and_seo_url(): void
    {
        $response = $this->actingAs($this->user)->post(route('attractions.store'), []);

        $response->assertSessionHasErrors(['name', 'seo_url']);
    }

    public function test_store_enforces_unique_seo_url(): void
    {
        Attraction::create(['name' => 'Existing', 'seo_url' => 'existing-url']);

        $response = $this->actingAs($this->user)->post(route('attractions.store'), [
            'name'    => 'New',
            'seo_url' => 'existing-url',
        ]);

        $response->assertSessionHasErrors(['seo_url']);
    }

    public function test_edit_view_loads(): void
    {
        $attraction = Attraction::create(['name' => 'Edit Me', 'seo_url' => 'edit-me']);

        $response = $this->actingAs($this->user)->get(route('attractions.edit', $attraction));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_update_modifies_attraction(): void
    {
        $attraction = Attraction::create(['name' => 'Old Name', 'seo_url' => 'old-name']);

        $response = $this->actingAs($this->user)->put(route('attractions.update', $attraction), [
            'name'    => 'New Name',
            'seo_url' => 'new-name',
        ]);

        $response->assertRedirect(route('attractions.index'));
        $this->assertDatabaseHas('attractions', ['id' => $attraction->id, 'name' => 'New Name']);
    }

    public function test_update_allows_same_seo_url(): void
    {
        $attraction = Attraction::create(['name' => 'Keep', 'seo_url' => 'keep-url']);

        $response = $this->actingAs($this->user)->put(route('attractions.update', $attraction), [
            'name'    => 'Keep Updated',
            'seo_url' => 'keep-url',
        ]);

        $response->assertRedirect(route('attractions.index'));
    }

    public function test_destroy_deletes_attraction(): void
    {
        $attraction = Attraction::create(['name' => 'Delete Me', 'seo_url' => 'delete-me']);

        $response = $this->actingAs($this->user)->delete(route('attractions.destroy', $attraction));

        $response->assertRedirect(route('attractions.index'));
        $this->assertDatabaseMissing('attractions', ['id' => $attraction->id]);
    }

    public function test_show_redirects_to_index(): void
    {
        $attraction = Attraction::create(['name' => 'Show', 'seo_url' => 'show-me']);

        $response = $this->actingAs($this->user)->get(route('attractions.show', $attraction));

        $response->assertRedirect(route('attractions.index'));
    }
}
