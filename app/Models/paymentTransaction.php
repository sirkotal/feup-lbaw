<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paymentTransaction extends Model
{
    use HasFactory;

    protected $table = 'paymenttransaction';

    public $timestamps = false;

    protected $fillable = [
        'method',
        'payment_status',
        'order_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /* acho que isto para fazer sentido a notificação deveria ser sobre o status do payment e nao se foi approved,
    porque se for approved deveria ser uma relação 1-1 e não 1-*, porque só vai ter 1 payment approved, logo, só
    1 notificação de paymentApproved por transaction */

    public function paymentApproveds()
    {
        return $this->hasMany(PaymentApproved::class, 'payment_transaction_id');
    }
}
