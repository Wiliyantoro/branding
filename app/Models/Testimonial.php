<?php

namespace App\Models;

use App\Concerns\Publishable;
use App\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory, ResolvesMediaUrl, Publishable;

    protected $fillable = [
        'client_name',
        'client_title',
        'client_company',
        'client_avatar',
        'content',
        'rating',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_published' => 'boolean',
    ];

    protected function clientAvatarUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->mediaUrl($this->client_avatar));
    }
}
