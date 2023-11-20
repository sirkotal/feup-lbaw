<?php

namespace App\Models;
use App\Models\paymentTransaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    public $timestamps = false;

    protected $fillable = [
        'order_date',
        'item_quantity',
        'order_status',
        'total',
        'address',
        'country',
        'city',
        'zip_code',
        'user_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'orderedproduct', 'order_id', 'product_id')
            ->withPivot('quantity', 'price_bought');
    }

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'order_id');
    }

    public function changesOfOrder()
    {
        return $this->hasMany(ChangeOfOrder::class, 'order_id');
    }
}
