<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracked_item_id',
        'version_number',
        'file_disk',
        'file_path',
        'original_filename',
        'stored_filename',
        'mime_type',
        'file_size',
        'sha256_hash',
        'uploaded_by',
        'uploaded_at',
        'comment',
    ];

    protected function casts(): array
    {
        return [
            'uploaded_at' => 'datetime',
        ];
    }

    public function trackedItem(): BelongsTo
    {
        return $this->belongsTo(TrackedItem::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
