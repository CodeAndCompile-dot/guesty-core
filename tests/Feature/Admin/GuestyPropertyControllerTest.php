<?php

namespace Tests\Feature\Admin;

use App\Models\GuestyProperty;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestyPropertyControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_displays_guesty_properties(): void
    {
        GuestyProperty::create([
            'title'   => 'Test Villa',
            'seo_url' => 'test-villa',
            '_id'     => 'guesty-123',
            'status'  => 'true',
        ]);

        $response = $this->actingAs($this->user)->get(route('guesty_properties.index'));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_create_redirects_to_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('guesty_properties.create'));

        $response->assertRedirect(route('guesty_properties.index'));
    }

    public function test_store_redirects_to_index(): void
    {
        $response = $this->actingAs($this->user)->post(route('guesty_properties.store'), []);

        $response->assertRedirect(route('guesty_properties.index'));
    }

    public function test_show_redirects_to_index(): void
    {
        $property = GuestyProperty::create([
            'title'   => 'Test Villa',
            'seo_url' => 'test-villa',
            '_id'     => 'guesty-123',
        ]);

        $response = $this->actingAs($this->user)->get(route('guesty_properties.show', $property->id));

        $response->assertRedirect(route('guesty_properties.index'));
    }

    public function test_edit_displays_form(): void
    {
        $property = GuestyProperty::create([
            'title'   => 'Test Villa',
            'seo_url' => 'test-villa',
            '_id'     => 'guesty-123',
        ]);

        $response = $this->actingAs($this->user)->get(route('guesty_properties.edit', $property->id));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_edit_with_invalid_id_redirects(): void
    {
        $response = $this->actingAs($this->user)->get(route('guesty_properties.edit', 9999));

        $response->assertRedirect(route('guesty_properties.index'));
        $response->assertSessionHas('danger');
    }

    public function test_update_saves_seo_data(): void
    {
        $property = GuestyProperty::create([
            'title'   => 'Test Villa',
            'seo_url' => 'test-villa',
            '_id'     => 'guesty-123',
        ]);

        $data = [
            'seo_url'          => 'updated-villa',
            'meta_title'       => 'Updated Title',
            'meta_keywords'    => 'test, keywords',
            'meta_description' => 'Description here',
            'ordering'         => '5',
            'status'           => 'true',
            'is_home'          => 'true',
        ];

        $response = $this->actingAs($this->user)->put(
            route('guesty_properties.update', $property->id),
            $data
        );

        $response->assertRedirect(route('guesty_properties.index'));
        $response->assertSessionHas('success');

        $property->refresh();
        $this->assertEquals('updated-villa', $property->seo_url);
        $this->assertEquals('Updated Title', $property->meta_title);
        $this->assertEquals(5, $property->ordering);
    }

    public function test_update_validates_seo_url_uniqueness(): void
    {
        GuestyProperty::create([
            'title'   => 'Existing Villa',
            'seo_url' => 'taken-url',
            '_id'     => 'guesty-001',
        ]);

        $property = GuestyProperty::create([
            'title'   => 'Test Villa',
            'seo_url' => 'test-villa',
            '_id'     => 'guesty-002',
        ]);

        $response = $this->actingAs($this->user)->put(
            route('guesty_properties.update', $property->id),
            ['seo_url' => 'taken-url']
        );

        $response->assertSessionHasErrors('seo_url');
    }

    public function test_update_allows_same_seo_url_for_same_record(): void
    {
        $property = GuestyProperty::create([
            'title'   => 'Test Villa',
            'seo_url' => 'test-villa',
            '_id'     => 'guesty-123',
        ]);

        $response = $this->actingAs($this->user)->put(
            route('guesty_properties.update', $property->id),
            ['seo_url' => 'test-villa']
        );

        $response->assertRedirect(route('guesty_properties.index'));
        $response->assertSessionHas('success');
    }

    public function test_destroy_redirects_to_index(): void
    {
        $property = GuestyProperty::create([
            'title'   => 'Test Villa',
            'seo_url' => 'test-villa',
            '_id'     => 'guesty-123',
        ]);

        $response = $this->actingAs($this->user)->delete(route('guesty_properties.destroy', $property->id));

        $response->assertRedirect(route('guesty_properties.index'));
        $response->assertSessionHas('danger');
        $this->assertDatabaseHas('guesty_properties', ['id' => $property->id]);
    }

    public function test_get_sub_location_list_returns_html(): void
    {
        $parent = Location::create([
            'name'      => 'Bentonville',
            'seo_url'   => 'bentonville',
            'ordering'  => '1',
            'is_parent' => '0',
        ]);

        $child = Location::create([
            'name'      => 'Downtown',
            'seo_url'   => 'downtown',
            'ordering'  => '1',
            'is_parent' => $parent->id,
        ]);

        $response = $this->actingAs($this->user)->post(route('getSubLocationList'), [
            'id' => $parent->id,
        ]);

        $response->assertStatus(200);
        $this->assertStringContains('Downtown', $response->getContent());
        $this->assertStringContains((string) $child->id, $response->getContent());
    }

    /**
     * Helper: str_contains assertion for older PHPUnit.
     */
    private function assertStringContains(string $needle, string $haystack): void
    {
        $this->assertTrue(str_contains($haystack, $needle), "Failed asserting that '{$haystack}' contains '{$needle}'");
    }
}
