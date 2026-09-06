<?php

namespace App\Concerns;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ResolvesMediaUrl
{
    /**
     * Accept both external URLs (legacy/seeded values) and uploaded disk paths.
     */
    protected function mediaUrl(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        return Str::startsWith($value, ['http://', 'https://', '//', '/'])
            ? $value
            : Storage::disk('public')->url($value);
    }
}
