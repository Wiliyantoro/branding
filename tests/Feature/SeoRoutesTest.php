<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_lists_published_posts_only(): void
    {
        $live = BlogPost::create([
            'title' => 'Live', 'content' => '<p>x</p>',
            'is_published' => true, 'published_at' => now(),
        ]);
        $draft = BlogPost::create(['title' => 'Draft', 'content' => '<p>x</p>']);

        $response = $this->get('/sitemap.xml');

        $response->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('<urlset', escape: false)
            ->assertSee(route('blog.show', $live->slug), escape: false)
            ->assertDontSee(route('blog.show', $draft->slug), escape: false);
    }

    public function test_robots_blocks_admin_and_points_to_sitemap(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Disallow: /admin')
            ->assertSee(route('sitemap'), escape: false);
    }
}
