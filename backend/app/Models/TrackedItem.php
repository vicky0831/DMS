<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrackedItem extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_EXPIRING_SOON = 'EXPIRING_SOON';
    public const STATUS_RENEWAL_REQUIRED = 'RENEWAL_REQUIRED';
    public const STATUS_RENEWAL_IN_PROGRESS = 'RENEWAL_IN_PROGRESS';
    public const STATUS_PENDING_APPROVAL = 'PENDING_APPROVAL';
    public const STATUS_RENEWED = 'RENEWED';
    public const STATUS_EXPIRED = 'EXPIRED';
    public const STATUS_CANCELLED = 'CANCELLED';

    protected $fillable = [
        'company_id',
        'category_id',
        'name',
        'description',
        'reference_number',
        'owner_user_id',
        'responsible_user_id',
        'issue_date',
        'expiry_date',
        'reminder_schedule',
        'status',
        'priority',
        'notes',
        'tags',
        'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'expiry_date' => 'date',
            'reminder_schedule' => 'array',
            'tags' => 'array',
            'archived_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(DocumentVersion::class)->orderByDesc('version_number');
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    public function auditLogs(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    public function getDerivedStatusAttribute(): string
    {
        if ($this->status === self::STATUS_RENEWAL_IN_PROGRESS || $this->status === self::STATUS_PENDING_APPROVAL) {
            return $this->status;
        }

        if ($this->expiry_date === null) {
            return self::STATUS_ACTIVE;
        }

        $daysRemaining = now()->startOfDay()->diffInDays($this->expiry_date->copy()->startOfDay(), false);

        if ($daysRemaining < 0) {
            return self::STATUS_EXPIRED;
        }

        if ($daysRemaining <= 30) {
            return self::STATUS_EXPIRING_SOON;
        }

        return self::STATUS_ACTIVE;
    }
}
