<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderedProduct extends Model
{
    use HasFactory;

    protected $table = 'orderedproduct';

    public $timestamps = false;

    protected $fillable = [
        'quantity',
        'price_bought',
        'product_id',
        'order_id',
    ];
}
