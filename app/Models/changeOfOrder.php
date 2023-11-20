<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class changeOfOrder extends Model
{
    use HasFactory;

    protected $table = 'changeoforder';

    public $timestamps = false;

    protected $fillable = [
        'notification_id',
        'order_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
