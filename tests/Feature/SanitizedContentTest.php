<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SanitizedContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_safe_content_is_cached_on_save(): void
    {
        $post = BlogPost::create([
            'title' => 'XSS Probe',
            'content' => '<p>aman</p><script>alert(1)</script>',
            'is_published' => true,
            'published_at' => now(),
        ]);

        // After save, safe_content_html should be populated
        $fresh = $post->fresh();
        $this->assertNotEmpty($fresh->safe_content_html);
        $this->assertStringContainsString('aman', $fresh->safe_content_html);
        $this->assertStringNotContainsString('<script>', $fresh->safe_content_html);
    }

    public function test_safe_content_uses_cached_value_instead_of_sanitizing_every_time(): void
    {
        $post = BlogPost::create([
            'title' => 'Cached Test',
            'content' => '<p>benar</p><script>malicious()</script>',
            'is_published' => true,
            'published_at' => now(),
        ]);

        // Force save again with same content to ensure caching works
        $post->content = '<p>benar</p><script>malicious()</script>';
        $post->save();

        $cached = $post->fresh()->safe_content_html;
        $this->assertStringContainsString('benar', $cached);
        $this->assertStringNotContainsString('<script>', $cached);
    }

    public function test_legacy_posts_without_cached_safe_content_still_render(): void
    {
        $post = BlogPost::create([
            'title' => 'Legacy Post',
            'content' => '<p>aman</p><script>alert(1)</script>',
            'is_published' => true,
            'published_at' => now(),
        ]);

        // Manually unset the cached field to simulate legacy post
        $post->forceFill(['safe_content_html' => null])->saveQuietly();
        $fresh = $post->fresh();

        // Should still sanitize runtime as fallback
        $content = $fresh->safe_content;
        $this->assertStringContainsString('aman', $content);
        $this->assertStringNotContainsString('<script>', $content);
    }
}
