<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VCardTemplate;

class VCardTemplatePolicy
{
    /**
     * Determine if the user can view any templates.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view templates
    }

    /**
     * Determine if the user can view the template.
     */
    public function view(User $user, VCardTemplate $vCardTemplate): bool
    {
        return true; // All authenticated users can view templates
    }

    /**
     * Determine if the user can create templates.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create templates
    }

    /**
     * Determine if the user can update the template.
     */
    public function update(User $user, VCardTemplate $vCardTemplate): bool
    {
        // Users can only update their own templates, or superadmin can update any
        return $user->id === $vCardTemplate->user_id || $user->hasRole('superadmin');
    }

    /**
     * Determine if the user can delete the template.
     */
    public function delete(User $user, VCardTemplate $vCardTemplate): bool
    {
        // Users can only delete their own templates, or superadmin can delete any
        return $user->id === $vCardTemplate->user_id || $user->hasRole('superadmin');
    }
}
