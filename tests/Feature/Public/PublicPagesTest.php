<?php

namespace Tests\Feature\Public;

use App\Models\Attraction;
use App\Models\AttractionCategory;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Cms;
use App\Models\GuestyProperty;
use App\Models\Location;
use App\Models\OurTeam;
use App\Models\SeoCms;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    /*
    |----------------------------------------------------------------------
    | Homepage
    |----------------------------------------------------------------------
    */

    public function test_homepage_returns_200_when_cms_exists(): void
    {
        Cms::create(['name' => 'Home', 'seo_url' => 'home', 'templete' => 'home']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('front.static.home');
    }

    public function test_homepage_returns_404_when_no_cms(): void
    {
        $response = $this->get('/');

        $response->assertStatus(404);
    }

    /*
    |----------------------------------------------------------------------
    | CMS Dynamic Pages
    |----------------------------------------------------------------------
    */

    public function test_cms_page_renders_default_template(): void
    {
        Cms::create(['name' => 'About Us', 'seo_url' => 'about-us', 'templete' => 'default']);

        $response = $this->get('/about-us');

        $response->assertStatus(200);
        $response->assertViewIs('front.static.default');
        $response->assertViewHas('data');
    }

    public function test_cms_blogs_template_renders_with_pagination(): void
    {
        Cms::create(['name' => 'Blogs', 'seo_url' => 'blogs', 'templete' => 'blogs']);
        Blog::create(['title' => 'First Post', 'seo_url' => 'first-post']);

        $response = $this->get('/blogs');

        $response->assertStatus(200);
        $response->assertViewIs('front.static.blogs');
        $response->assertViewHas('blogs');
    }

    public function test_cms_page_home_slug_redirects_to_root(): void
    {
        $response = $this->get('/home');

        $response->assertRedirect('/');
    }

    public function test_cms_page_falls_through_to_guesty_property(): void
    {
        GuestyProperty::create([
            '_id'          => 'guesty-123',
            'seo_url'      => 'luxury-villa',
            'title'        => 'Luxury Villa',
            'accommodates' => 6,
        ]);

        $response = $this->get('/luxury-villa');

        $response->assertStatus(200);
        $response->assertViewIs('front.property.singleGuesty');
        $response->assertViewHas('data');
    }

    public function test_unknown_slug_returns_404(): void
    {
        $response = $this->get('/nonexistent-page');

        $response->assertStatus(404);
    }

    /*
    |----------------------------------------------------------------------
    | Blog Pages
    |----------------------------------------------------------------------
    */

    public function test_blog_single_returns_200(): void
    {
        $category = BlogCategory::create(['title' => 'Travel', 'seo_url' => 'travel']);
        Blog::create([
            'title'            => 'My Post',
            'seo_url'          => 'my-post',
            'blog_category_id' => $category->id,
        ]);

        $response = $this->get('/blog/my-post');

        $response->assertStatus(200);
        $response->assertViewIs('front.group.single');
        $response->assertViewHas(['data', 'category']);
    }

    public function test_blog_single_returns_404_for_unknown_slug(): void
    {
        $response = $this->get('/blog/unknown');

        $response->assertStatus(404);
    }

    public function test_blog_category_returns_200(): void
    {
        $category = BlogCategory::create(['title' => 'Travel', 'seo_url' => 'travel']);
        Blog::create([
            'title'            => 'Cat Post',
            'seo_url'          => 'cat-post',
            'blog_category_id' => $category->id,
        ]);

        $response = $this->get('/blogs/category/travel');

        $response->assertStatus(200);
        $response->assertViewIs('front.group.category');
        $response->assertViewHas(['data', 'blogs']);
    }

    /*
    |----------------------------------------------------------------------
    | Attraction Pages
    |----------------------------------------------------------------------
    */

    public function test_attraction_single_returns_200(): void
    {
        Attraction::create(['name' => 'Crystal Bridges', 'seo_url' => 'crystal-bridges']);

        $response = $this->get('/attractions/detail/crystal-bridges');

        $response->assertStatus(200);
        $response->assertViewIs('front.attractions.single');
    }

    public function test_attraction_category_returns_200(): void
    {
        AttractionCategory::create(['name' => 'Outdoor', 'seo_url' => 'outdoor']);

        $response = $this->get('/attractions/category/outdoor');

        $response->assertStatus(200);
        $response->assertViewIs('front.attractions.category');
    }

    public function test_attraction_location_returns_200(): void
    {
        Location::create(['name' => 'Downtown', 'seo_url' => 'downtown']);

        $response = $this->get('/attractions/location/downtown');

        $response->assertStatus(200);
        $response->assertViewIs('front.attractions.location');
    }

    /*
    |----------------------------------------------------------------------
    | Property Location
    |----------------------------------------------------------------------
    */

    public function test_property_location_returns_200(): void
    {
        Location::create(['name' => 'Lakeside', 'seo_url' => 'lakeside']);

        $response = $this->get('/properties/location/lakeside');

        $response->assertStatus(200);
        $response->assertViewIs('front.property.location');
    }

    /*
    |----------------------------------------------------------------------
    | Team Member
    |----------------------------------------------------------------------
    */

    public function test_team_member_returns_200(): void
    {
        OurTeam::create([
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'seo_url'    => 'john-doe',
            'email'      => 'john@example.com',
        ]);

        $response = $this->get('/meet-the-team/john-doe');

        $response->assertStatus(200);
        $response->assertViewIs('front.meet-the-teams.common');
    }

    public function test_team_member_returns_404_for_unknown(): void
    {
        $response = $this->get('/meet-the-team/nobody');

        $response->assertStatus(404);
    }

    /*
    |----------------------------------------------------------------------
    | Vacation (SEO pages)
    |----------------------------------------------------------------------
    */

    public function test_vacation_page_returns_200(): void
    {
        SeoCms::create([
            'name'     => 'Summer Getaway',
            'seo_url'  => 'summer-getaway',
            'templete' => 'default',
        ]);

        $response = $this->get('/vacation/summer-getaway');

        $response->assertStatus(200);
        $response->assertViewIs('front.seo-pages.default');
    }

    /*
    |----------------------------------------------------------------------
    | Utility Routes
    |----------------------------------------------------------------------
    */

    public function test_sitemap_xml_returns_xml_response(): void
    {
        Cms::create(['name' => 'Home', 'seo_url' => 'home', 'templete' => 'home']);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $response->assertSee('<urlset', false);
    }

    public function test_robots_txt_returns_plain_text(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('User-agent');
        $response->assertSee('Sitemap');
    }

    public function test_captcha_reload_returns_json(): void
    {
        $response = $this->getJson('/reload-captcha');

        $response->assertStatus(200);
        $response->assertJsonStructure(['captcha']);
    }
}
