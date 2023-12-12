<?php

namespace App\Policies;

use App\Models\Discount;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;


class DiscountPolicy
{

    public function editPromotion(){
        return Auth::check() && Auth::user()->id==1;
    }
    
}
