<?php

namespace App\Policies;

use App\Models\ShortLink;
use App\Models\User;

class ShortLinkPolicy
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
    public function view(User $user, ShortLink $shortLink): bool
    {
        return $user->id === $shortLink->user_id || $user->hasRole('Superadmin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ShortLink $shortLink): bool
    {
        return $user->id === $shortLink->user_id || $user->hasRole('Superadmin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ShortLink $shortLink): bool
    {
        return $user->id === $shortLink->user_id || $user->hasRole('Superadmin');
    }
}
