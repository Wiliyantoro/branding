<?php

namespace App\Models;

use App\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

class BlogPost extends Model
{
    use HasFactory, ResolvesMediaUrl;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'category',
        'is_published',
        'published_at',
        'views_count',
        'safe_content_html',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (BlogPost $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            if ($post->is_published && empty($post->published_at)) {
                $post->published_at = now();
            }
        });

        static::updating(function (BlogPost $post) {
            if ($post->is_published && $post->wasChanged('is_published') && empty($post->published_at)) {
                $post->published_at = now();
            }
        });

        // Cache sanitized HTML proactively during save
        static::saving(function (BlogPost $post) {
            if ($post->isDirty('content')) {
                $post->safe_content_html = app(HtmlSanitizerInterface::class)->sanitize((string) $post->content);
            }
        });

        static::saved(fn () => Cache::forget('sitemap.urls'));
        static::deleted(fn () => Cache::forget('sitemap.urls'));
    }

    /**
     * Body HTML with scripts/handlers stripped — the only form safe for {!! !!}.
     * Uses cached safe_content_html if available, falls back to runtime sanitization.
     */
    protected function safeContent(): Attribute
    {
        return Attribute::get(function (): string {
            if (! blank($this->safe_content_html)) {
                return $this->safe_content_html;
            }
            // Fallback for existing rows without cached safe HTML
            return app(HtmlSanitizerInterface::class)->sanitize((string) $this->content);
        })->shouldCache();
    }

    /**
     * Resolved featured image URL (accepts external URLs and uploaded paths).
     */
    protected function featuredImageUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->mediaUrl($this->featured_image));
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->whereNotNull('published_at');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
