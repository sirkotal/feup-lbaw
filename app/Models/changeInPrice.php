<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class changeInPrice extends Model
{
    use HasFactory;

    protected $table = 'changeinprice';

    public $timestamps = false;

    protected $fillable = [
        'notification_id',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
