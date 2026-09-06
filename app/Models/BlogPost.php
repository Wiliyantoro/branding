<?php

namespace App\Models;

use App\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    }

    /**
     * Body HTML with scripts/handlers stripped — the only form safe for {!! !!}.
     * ponytail: sanitizes on read (protects rows already in the DB). Move to a
     * saving() hook once the post volume makes per-request sanitizing measurable.
     */
    protected function safeContent(): Attribute
    {
        return Attribute::get(
            fn (): string => app(HtmlSanitizerInterface::class)->sanitize((string) $this->content)
        )->shouldCache();
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
