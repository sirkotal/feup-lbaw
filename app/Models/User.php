<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    public $timestamps = false;

    protected $fillable = [
        'date_of_birth',
        'username',
        'user_path',
        'password',
        'email',
        'phone_number',
        'is_deleted',
    ];

    protected $hidden = [
        'password'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function wishlist()
    {
        return $this->belongsToMany(User::class, 'wishlist', 'product_id', 'user_id');
    }

    public function shoppingCart()
    {
        return $this->belongsToMany(Product::class, 'shoppingcart', 'user_id', 'product_id')
            ->withPivot('quantity');
    }

    public function reportedReviews()
    {
        return $this->belongsToMany(Review::class, 'report', 'user_id', 'review_id')
                    ->withPivot('date', 'reason');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function upvotedReviews()
    {
        return $this->belongsToMany(Review::class, 'upvote', 'user_id', 'review_id');
    }

    public function blockActionsHistory()
    {
        return $this->hasMany(BlockAction::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }
    public function getUserPath()
    {
        return $this->userPath;
    }
}
