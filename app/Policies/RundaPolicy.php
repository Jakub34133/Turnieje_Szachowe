<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\Runda;
use App\Models\Turniej;
use App\Models\TurniejSedzia;
use App\Models\User;

class RundaPolicy
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
    public function view(User $user, Runda $runda): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Runda $runda, Turniej $turniej): bool
    {
        if ((int) $runda->turniej_id !== (int) $turniej->getKey()) {
            return false;
        }

        return $this->isOrganizerWhoCreated($user, $turniej)
            || $this->isSedziaWithPermissions($user, $turniej);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Runda $runda): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Runda $runda): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Runda $runda): bool
    {
        return false;
    }

    private function isOrganizerWhoCreated(User $user, Turniej $turniej): bool
    {
        return $user->hasRole(RoleEnum::Organizator->value)
            && (int) $turniej->organizator_id === (int) $user->getKey();
    }

    private function isSedziaWithPermissions(User $user, Turniej $turniej): bool
    {
        return $user->hasRole(RoleEnum::Sedzia->value)
            && TurniejSedzia::query()
                ->where('turniej_id', $turniej->getKey())
                ->where('sedzia_id', $user->getKey())
                ->exists();
    }
}
