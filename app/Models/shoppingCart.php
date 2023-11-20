<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shoppingCart extends Model
{
    use HasFactory;

    protected $table = 'shoppingcart';
    public $timestamps = false;

    protected $fillable = [
        'quantity',
        'user_id',
        'product_id',
    ];

}
