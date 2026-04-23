<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Location;

class LocationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Semua user bisa lihat
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Location $location): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage-locations') || $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Location $location): bool
    {
        return $user->hasPermissionTo('manage-locations') || $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Location $location): bool
    {
        // Cegah hapus jika ada children atau ada stok movement
        if ($location->hasChildren() || $location->stockMovements()->exists()) {
            return false;
        }

        return $user->hasPermissionTo('manage-locations') || $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Location $location): bool
    {
        return $user->hasPermissionTo('manage-locations') || $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Location $location): bool
    {
        return $user->hasPermissionTo('manage-locations') || $user->is_admin;
    }
}