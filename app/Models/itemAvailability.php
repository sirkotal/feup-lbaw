<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class itemAvailability extends Model
{
    use HasFactory;

    protected $table = 'itemavailability';

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
