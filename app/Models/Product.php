<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    public $timestamps = false;

    protected $fillable = [
        'product_name',
        'description',
        'extra_information',
        'price',
        'product_path',
        'stock',
        'brand_id',
        'discount_id',
    ];

    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlist', 'product_id', 'user_id');
    }

    public function shoppers()
    {
        return $this->belongsToMany(User::class, 'shoppingcart', 'product_id', 'user_id')
            ->withPivot('quantity');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'orderedproduct', 'product_id', 'order_id')
            ->withPivot('quantity', 'price_bought');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'productcategory');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    public function changeInPrices()
    {
        return $this->hasMany(ChangeInPrice::class, 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function itemAvailabilities()
    {
        return $this->hasMany(ItemAvailability::class, 'product_id');
    }
}
