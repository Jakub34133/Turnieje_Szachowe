<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\Turniej;
use App\Models\User;
use App\Models\Zgloszenie;

class ZgloszeniePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Turniej $turniej): bool
    {
        return $this->isOrganizerWhoCreated($user, $turniej);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Zgloszenie $zgloszenie): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Turniej $turniej): bool
    {
        return $user->hasRole(RoleEnum::Zawodnik->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Zgloszenie $zgloszenie, Turniej $turniej): bool
    {
        return (int) $zgloszenie->turniej_id === (int) $turniej->getKey()
            && $this->isOrganizerWhoCreated($user, $turniej);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Zgloszenie $zgloszenie): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Zgloszenie $zgloszenie): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Zgloszenie $zgloszenie): bool
    {
        return false;
    }

    private function isOrganizerWhoCreated(User $user, Turniej $turniej): bool
    {
        return $user->hasRole(RoleEnum::Organizator->value)
            && (int) $turniej->organizator_id === (int) $user->getKey();
    }
}
