<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_favicon_setting_renders_when_configured(): void
    {
        Setting::create(['key' => 'favicon', 'value' => 'favicon-123abc.png', 'group' => 'general']);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('favicon-123abc.png?v=', escape: false);
    }

    public function test_falls_back_to_default_favicon_when_not_set(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('favicon.ico?v=', escape: false);
    }

    public function test_home_page_renders_with_content(): void
    {
        Setting::create(['key' => 'site_name', 'value' => 'KANG WILLY', 'group' => 'general']);
        Setting::create(['key' => 'email', 'value' => 'hi@example.com', 'group' => 'contact']);
        Setting::create(['key' => 'github', 'value' => 'https://github.com/kw', 'group' => 'social']);

        Portfolio::create(['title' => 'Proyek A', 'description' => 'Deskripsi A', 'is_published' => true]);
        Service::create(['title' => 'Web Dev', 'description' => 'Bikin web', 'is_published' => true]);
        Testimonial::create(['client_name' => 'Budi', 'content' => 'Mantap', 'rating' => 5, 'is_published' => true]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('KANG WILLY')
            ->assertSee('Proyek A')
            ->assertSee('Web Dev')
            ->assertSee('Budi')
            ->assertSee('hi@example.com')
            ->assertSee('https://github.com/kw');
    }

    public function test_home_page_renders_without_any_settings_or_content(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_blog_index_lists_only_published_posts(): void
    {
        $published = BlogPost::create([
            'title' => 'Live Post', 'content' => '<p>hi</p>',
            'is_published' => true, 'published_at' => now(),
        ]);
        $draft = BlogPost::create([
            'title' => 'Draft Post', 'content' => '<p>secret</p>', 'is_published' => false,
        ]);

        $this->get('/blog')
            ->assertOk()
            ->assertSee($published->title)
            ->assertDontSee($draft->title);
    }

    public function test_draft_post_is_not_reachable_by_direct_url(): void
    {
        // Regression: route binding previously exposed drafts with a 200.
        $draft = BlogPost::create([
            'title' => 'Draft Post', 'content' => '<p>unreleased</p>',
            'is_published' => false, 'published_at' => now(),
        ]);

        $this->get("/blog/{$draft->slug}")->assertNotFound();
    }

    public function test_published_post_with_null_published_at_is_not_reachable(): void
    {
        // Regression: null published_at used to crash the view with a 500.
        $post = BlogPost::create([
            'title' => 'No Date', 'content' => '<p>x</p>',
        ]);
        $post->forceFill(['is_published' => true, 'published_at' => null])->saveQuietly();

        $this->get("/blog/{$post->slug}")->assertNotFound();
    }

    public function test_post_body_is_sanitized_before_output(): void
    {
        $post = BlogPost::create([
            'title' => 'XSS Probe',
            'content' => '<p>aman</p><script>alert(1)</script><img src=x onerror=alert(2)>',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get("/blog/{$post->slug}");

        $response->assertOk()
            ->assertSee('aman', escape: false)
            ->assertSee('img src="x"', escape: false)
            ->assertDontSee('alert(1)', escape: false)
            ->assertDontSee('alert(2)', escape: false);
    }

    public function test_views_count_increments_once_per_session(): void
    {
        $post = BlogPost::create([
            'title' => 'Counted', 'content' => '<p>x</p>',
            'is_published' => true, 'published_at' => now(),
        ]);

        $this->get("/blog/{$post->slug}")->assertOk();
        $this->get("/blog/{$post->slug}")->assertOk();

        $this->assertSame(1, $post->fresh()->views_count);
    }
}
