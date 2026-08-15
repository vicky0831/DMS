<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Renewal extends Model
{
    protected $fillable = [
        'tracked_item_id',
        'company_id',
        'started_by',
        'status',
        'target_completion_date',
        'completed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'target_completion_date' => 'date',
            'completed_at' => 'date',
        ];
    }

    public function trackedItem()
    {
        return $this->belongsTo(TrackedItem::class);
    }

    public function steps()
    {
        return $this->hasMany(RenewalStep::class)->orderBy('order');
    }
}
