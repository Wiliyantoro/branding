<?php

namespace App\Models;

use App\Concerns\Publishable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory, Publishable;

    protected $fillable = [
        'icon',
        'title',
        'description',
        'price',
        'price_label',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_published' => 'boolean',
    ];
}
