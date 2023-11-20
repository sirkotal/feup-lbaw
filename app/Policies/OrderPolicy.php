<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;


class OrderPolicy
{

    public function createOrder(){
        return Auth::check() && Auth::user()->id!=1;
    }
    
    public function showCheckout(){
        return Auth::check() && Auth::user()->id!=1;
    }
}
