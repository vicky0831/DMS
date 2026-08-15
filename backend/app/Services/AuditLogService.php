<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditLogService
{
    public function log(
        int $companyId,
        ?int $actorUserId,
        string $action,
        ?Model $auditable = null,
        ?array $previousValues = null,
        ?array $newValues = null
    ): void {
        AuditLog::create([
            'company_id' => $companyId,
            'actor_user_id' => $actorUserId,
            'action' => $action,
            'auditable_type' => $auditable ? get_class($auditable) : null,
            'auditable_id' => $auditable ? $auditable->getKey() : null,
            'previous_values' => $previousValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
