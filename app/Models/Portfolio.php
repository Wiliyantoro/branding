<?php

namespace App\Models;

use App\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory, ResolvesMediaUrl;

    protected $fillable = [
        'title',
        'description',
        'image',
        'url',
        'category',
        'tech_stack',
        'is_featured',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->mediaUrl($this->image));
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }
}
