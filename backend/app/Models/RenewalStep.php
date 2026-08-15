<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RenewalStep extends Model
{
    protected $fillable = [
        'renewal_id',
        'name',
        'description',
        'status',
        'order',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'date',
        ];
    }

    public function renewal()
    {
        return $this->belongsTo(Renewal::class);
    }
}
