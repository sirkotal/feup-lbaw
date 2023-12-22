<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'review';
    public $timestamps = false;

    protected $fillable = [
        'review_date',
        'rating',
        'title',
        'upvote_count',
        'review_text',
        'user_id',
        'product_id',
    ];

    public function reportingUsers()
    {
        return $this->belongsToMany(User::class, 'report', 'review_id', 'user_id')
                    ->withPivot('date', 'reason');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function upvoters()
    {
        return $this->belongsToMany(User::class, 'upvote', 'review_id', 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function likedReviews()
    {
        return $this->hasMany(LikedReview::class, 'review_id');
    }

    public function scopeOrderBySortOption($query, $sortOption)
    {
        switch ($sortOption) {
            case 'date_desc':
                return $query->orderBy('review_date', 'desc');
            case 'date_asc':
                return $query->orderBy('review_date', 'asc');
            case 'rating_desc':
                return $query->orderBy('rating', 'desc');
            case 'rating_asc':
                return $query->orderBy('rating', 'asc');
            case 'upvotes':
                return $query->orderBy('upvote_count', 'desc');
            default:
                return $query->orderBy('review_date', 'desc');
        }
    }
}
