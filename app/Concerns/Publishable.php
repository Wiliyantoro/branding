<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Shared query scopes for content models that have an is_published flag
 * plus a sort_order/created_at display order.
 */
trait Publishable
{
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }
}
