<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'tracked_item_id',
        'due_at',
        'channel',
        'status',
        'scheduled_days_before',
        'sent_at',
        'last_error',
    ];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function trackedItem(): BelongsTo
    {
        return $this->belongsTo(TrackedItem::class);
    }
}
