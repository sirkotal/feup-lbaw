<?php

namespace App\Policies;

use App\Models\User;
use App\Models\shoppingCart;
use Illuminate\Auth\Access\Response;

class shoppingCartPolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, shoppingCart $shoppingCart): bool
    {   
        
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->id !== 1;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, shoppingCart $shoppingCart): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, shoppingCart $shoppingCart): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, shoppingCart $shoppingCart): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, shoppingCart $shoppingCart): bool
    {
        //
    }
}
