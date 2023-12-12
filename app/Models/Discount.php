<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $table = 'discount';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'percentage',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'discount_id');
    }
}
