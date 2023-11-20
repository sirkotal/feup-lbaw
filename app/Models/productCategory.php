<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productCategory extends Model
{
    use HasFactory;

    protected $table = 'productcategory';

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'category_id',
    ];
}
