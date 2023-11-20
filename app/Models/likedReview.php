<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class likedReview extends Model
{
    use HasFactory;

    protected $table = 'likedreview';

    public $timestamps = false;

    protected $fillable = [
        'notification_id',
        'review_id',
    ];

    public function review()
    {
        return $this->belongsTo(Review::class, 'review_id');
    }
}
