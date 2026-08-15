<?php

namespace App\Policies;

use App\Models\TrackedItem;
use App\Models\User;

class TrackedItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Usually handled by scope in controller
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TrackedItem $trackedItem): bool
    {
        return $this->hasPermission($user, $trackedItem->company_id, 'tracked_items.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, int $companyId): bool
    {
        return $this->hasPermission($user, $companyId, 'tracked_items.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TrackedItem $trackedItem): bool
    {
        return $this->hasPermission($user, $trackedItem->company_id, 'tracked_items.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TrackedItem $trackedItem): bool
    {
        return $this->hasPermission($user, $trackedItem->company_id, 'tracked_items.delete');
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
