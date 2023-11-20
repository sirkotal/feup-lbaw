<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class blockAction extends Model
{
    use HasFactory;

    protected $table = 'blockaction';

    public $timestamps = false;

    protected $fillable = [
        'block_date',
        'blocked_action',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
