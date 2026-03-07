<?php

namespace Tests\Feature\Admin;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private BlogCategory $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->category = BlogCategory::create([
            'title'    => 'General',
            'seo_url'  => 'general',
            'ordering' => '0',
        ]);
    }

    public function test_index_displays_blogs(): void
    {
        Blog::create([
            'title'            => 'My Post',
            'seo_url'          => 'my-post',
            'blog_category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('blogs.index'));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_create_view_loads(): void
    {
        $response = $this->actingAs($this->user)->get(route('blogs.create'));

        $response->assertStatus(200);
    }

    public function test_store_creates_blog(): void
    {
        $data = [
            'title'            => 'New Blog',
            'seo_url'          => 'new-blog',
            'blog_category_id' => $this->category->id,
        ];

        $response = $this->actingAs($this->user)->post(route('blogs.store'), $data);

        $response->assertRedirect(route('blogs.index'));
        $this->assertDatabaseHas('blogs', ['seo_url' => 'new-blog']);
    }

    public function test_store_requires_title_seo_url_category(): void
    {
        $response = $this->actingAs($this->user)->post(route('blogs.store'), []);

        $response->assertSessionHasErrors(['title', 'seo_url', 'blog_category_id']);
    }

    public function test_edit_view_loads(): void
    {
        $blog = Blog::create([
            'title'            => 'Edit Post',
            'seo_url'          => 'edit-post',
            'blog_category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('blogs.edit', $blog));

        $response->assertStatus(200);
        $response->assertViewHas('data');
    }

    public function test_update_modifies_blog(): void
    {
        $blog = Blog::create([
            'title'            => 'Old Title',
            'seo_url'          => 'old-title',
            'blog_category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)->put(route('blogs.update', $blog), [
            'title'            => 'New Title',
            'seo_url'          => 'new-title',
            'blog_category_id' => $this->category->id,
        ]);

        $response->assertRedirect(route('blogs.index'));
        $this->assertDatabaseHas('blogs', ['id' => $blog->id, 'title' => 'New Title']);
    }

    public function test_destroy_deletes_blog(): void
    {
        $blog = Blog::create([
            'title'            => 'Delete Me',
            'seo_url'          => 'delete-me',
            'blog_category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)->delete(route('blogs.destroy', $blog));

        $response->assertRedirect(route('blogs.index'));
        $this->assertDatabaseMissing('blogs', ['id' => $blog->id]);
    }

    public function test_active_sets_status_to_active(): void
    {
        $blog = Blog::create([
            'title'            => 'Activate Me',
            'seo_url'          => 'activate-me',
            'blog_category_id' => $this->category->id,
            'status'           => 'deactive',
        ]);

        $response = $this->actingAs($this->user)->get(route('blogs.active', $blog->id));

        $response->assertRedirect(route('blogs.index'));
        $this->assertDatabaseHas('blogs', ['id' => $blog->id, 'status' => 'active']);
    }

    public function test_deactive_sets_status_to_deactive(): void
    {
        $blog = Blog::create([
            'title'            => 'Deactivate Me',
            'seo_url'          => 'deactivate-me',
            'blog_category_id' => $this->category->id,
            'status'           => 'active',
        ]);

        $response = $this->actingAs($this->user)->get(route('blogs.deactive', $blog->id));

        $response->assertRedirect(route('blogs.index'));
        $this->assertDatabaseHas('blogs', ['id' => $blog->id, 'status' => 'deactive']);
    }

    public function test_copy_data_duplicates_blog(): void
    {
        $blog = Blog::create([
            'title'            => 'Copy Me',
            'seo_url'          => 'copy-me',
            'blog_category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('blogs.copyData', $blog->id));

        $response->assertRedirect(route('blogs.index'));
        $this->assertDatabaseHas('blogs', ['seo_url' => 'copy-me-copy']);
        $this->assertEquals(2, Blog::count());
    }
}
