<?php

namespace App\Policies;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

class UserPolicy
{

    public function show(User $user): bool
    {   
        return Auth::user()->id == $user->id && !$user->isDeleted;
    }

    public function edit(User $user): bool
    {   
        return Auth::user()->id == $user->id && !$user->isDeleted;
    }

    public function blockUser(User $user): bool
    {   
        return Auth::user()->id == $user->id && Auth::user()->id == 1;
    }

    public function deleteUser(User $user): bool
    {   
        return Auth::user()->id == $user->id && Auth::user()->id == 1;
    }

}
