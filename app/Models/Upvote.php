<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upvote extends Model
{
    use HasFactory;

    protected $table = 'upvote';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'review_id',
    ];
}
