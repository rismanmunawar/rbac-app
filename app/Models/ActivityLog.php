<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'causer_id',
        'action',
        'description',
        'properties',
        'subject_type',
        'subject_id'
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function causer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    public function subject()
    {
        return $this->morphTo(null, 'subject_type', 'subject_id');
    }
}
