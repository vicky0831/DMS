<?php

namespace App\Policies;

use App\Models\DocumentVersion;
use App\Models\User;

class DocumentVersionPolicy
{
    public function view(User $user, DocumentVersion $documentVersion): bool
    {
        return $this->hasPermission($user, $documentVersion->trackedItem->company_id, 'documents.view');
    }

    public function create(User $user, int $companyId): bool
    {
        return $this->hasPermission($user, $companyId, 'documents.upload');
    }

    private function hasPermission(User $user, int $companyId, string $permission): bool
    {
        $company = $user->companies()->where('company_id', $companyId)->first();
        
        if (!$company) {
            return false;
        }

        if ($company->pivot->role === 'owner') {
            return true;
        }

        $permissions = json_decode($company->pivot->permissions, true) ?? [];
        
        return in_array('*', $permissions) || in_array($permission, $permissions);
    }
}
