<?php

namespace Tests\Feature\Admin;

use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogCategoryControllerTest extends TestCase
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
        BlogCategory::create(['title' => 'Travel', 'seo_url' => 'travel', 'ordering' => '0']);

        $response = $this->actingAs($this->user)->get(route('blog-category.index'));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_create_view_loads(): void
    {
        $response = $this->actingAs($this->user)->get(route('blog-category.create'));

        $response->assertStatus(200);
    }

    public function test_store_creates_category(): void
    {
        $data = [
            'title'    => 'Adventures',
            'seo_url'  => 'adventures',
            'ordering' => '1',
        ];

        $response = $this->actingAs($this->user)->post(route('blog-category.store'), $data);

        $response->assertRedirect(route('blog-category.index'));
        $this->assertDatabaseHas('blog_categories', ['seo_url' => 'adventures']);
    }

    public function test_store_requires_title_seo_url_ordering(): void
    {
        $response = $this->actingAs($this->user)->post(route('blog-category.store'), []);

        $response->assertSessionHasErrors(['title', 'seo_url', 'ordering']);
    }

    public function test_edit_view_loads(): void
    {
        $cat = BlogCategory::create(['title' => 'Edit Cat', 'seo_url' => 'edit-cat', 'ordering' => '0']);

        $response = $this->actingAs($this->user)->get(route('blog-category.edit', $cat));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_update_modifies_category(): void
    {
        $cat = BlogCategory::create(['title' => 'Old', 'seo_url' => 'old', 'ordering' => '0']);

        $response = $this->actingAs($this->user)->put(route('blog-category.update', $cat), [
            'title'    => 'Updated',
            'seo_url'  => 'updated',
            'ordering' => '1',
        ]);

        $response->assertRedirect(route('blog-category.index'));
        $this->assertDatabaseHas('blog_categories', ['id' => $cat->id, 'title' => 'Updated']);
    }

    public function test_destroy_deletes_category(): void
    {
        $cat = BlogCategory::create(['title' => 'Remove', 'seo_url' => 'remove', 'ordering' => '0']);

        $response = $this->actingAs($this->user)->delete(route('blog-category.destroy', $cat));

        $response->assertRedirect(route('blog-category.index'));
        $this->assertDatabaseMissing('blog_categories', ['id' => $cat->id]);
    }

    public function test_copy_data_duplicates_category(): void
    {
        $cat = BlogCategory::create(['title' => 'Copy Me', 'seo_url' => 'copy-me', 'ordering' => '0']);

        $response = $this->actingAs($this->user)->get(route('blog-category.copyData', $cat->id));

        $response->assertRedirect(route('blog-category.index'));
        $this->assertDatabaseHas('blog_categories', ['seo_url' => 'copy-me-copy']);
        $this->assertEquals(2, BlogCategory::count());
    }
}
