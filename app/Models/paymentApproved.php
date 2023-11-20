<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paymentApproved extends Model
{
    use HasFactory;

    protected $table = 'paymentapproved';

    public $timestamps = false;

    protected $fillable = [
        'notification_id',
        'payment_transaction_id',
    ];

    public function paymentTransaction()
    {
        return $this->belongsTo(PaymentTransaction::class, 'payment_transaction_id');
    }
}
