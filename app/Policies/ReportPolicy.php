<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\Response;

use Illuminate\Support\Facades\Auth;

class ReportPolicy
{
    public function deleteReport(User $user)
    {
        return Auth::user()->id == $user->id && Auth::user()->id == 1;
    }
}
