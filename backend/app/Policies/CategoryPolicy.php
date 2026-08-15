<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user, int $companyId): bool
    {
        return $this->hasPermission($user, $companyId, 'categories.manage');
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
