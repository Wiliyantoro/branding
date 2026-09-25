<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_log';

    public $timestamps = false;

    protected $fillable = [
        'type',
        'title',
        'record_id',
        'action',
        'created_at',
        'updated_at',
    ];

    protected static function booted(): void
    {
        static::orderByDesc('updated_at');
    }
}