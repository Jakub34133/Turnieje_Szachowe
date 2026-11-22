<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\Turniej;
use App\Models\User;

class TurniejPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Turniej $turniej): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(RoleEnum::Organizator->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Turniej $turniej): bool
    {
        return $this->isOrganizerWhoCreated($user, $turniej);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Turniej $turniej): bool
    {
        return $this->isOrganizerWhoCreated($user, $turniej);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Turniej $turniej): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Turniej $turniej): bool
    {
        return false;
    }

    private function isOrganizerWhoCreated(User $user, Turniej $turniej): bool
    {
        return $user->hasRole(RoleEnum::Organizator->value)
            && $turniej->organizator_id === $user->getKey();
    }
}
