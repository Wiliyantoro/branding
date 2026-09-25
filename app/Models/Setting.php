<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    public const CACHE_KEY = 'settings.map';

    protected $fillable = [
        'group',
        'key',
        'value',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /**
     * key => value for every setting, cached until a setting is written.
     */
    public static function map(): array
    {
        return Cache::rememberForever(
            self::CACHE_KEY,
            fn () => static::query()->pluck('value', 'key')->all()
        );
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::map()[$key] ?? $default;
    }

    public static function set(string $key, mixed $value, string $group = 'general'): static
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }

}
