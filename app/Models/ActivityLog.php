<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_log';

    protected $fillable = [
        'type',
        'title',
        'record_id',
        'action',
        'updated_at',
    ];

    protected static function booted(): void
    {
        static::orderByDesc('updated_at');
    }
}