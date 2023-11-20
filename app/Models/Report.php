<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'report';
    public $timestamps = false;

    protected $fillable = [
        'report_date',
        'reason',
        'user_id',
        'review_id',
    ];
}
