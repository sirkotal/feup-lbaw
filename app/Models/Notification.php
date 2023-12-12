<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    public $timestamps = false;

    protected $fillable = [
        'notification_date',
        'notification_text',
        'is_read',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function changeOfOrder()
    {
        return $this->hasOne(changeOfOrder::class, 'notification_id');
    }

    public function changeInPrice()
    {
        return $this->hasOne(changeInPrice::class, 'notification_id');
    }

    public function itemAvailability()
    {
        return $this->hasOne(itemAvailability::class, 'notification_id');
    }

    public function paymentApproved()
    {
        return $this->hasOne(paymentApproved::class, 'notification_id');
    }

    public function likedReview()
    {
        return $this->hasOne(likedReview::class, 'notification_id');
    }
    
}
