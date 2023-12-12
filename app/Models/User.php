<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

class User extends Authenticatable implements CanResetPassword
{
    use Notifiable;
    use CanResetPasswordTrait;

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
        return $this->belongsToMany(Product::class, 'wishlist', 'user_id', 'product_id');
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
        return $this->hasMany(blockAction::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function getUserPath()
    {
        return $this->userPath;
    }

    public function isBlocked()
    {
        $blockHistory = $this->blockActionsHistory;

        if ($blockHistory->isEmpty()) {
            return false;
        }

        $lastAction = $blockHistory->last();

        return $lastAction->blocked_action !== 'Unblocking';
    }

    public function passwordResetTokens()
    {
        return $this->hasOne(PasswordResetToken::class, 'email', 'email');
    }
}
