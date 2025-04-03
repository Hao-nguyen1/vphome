<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'method_name',
        'payment_id',
        'payment_detail',
    ];

    protected $casts = [
        'payment_detail' => 'json'
    ];

    protected $table = 'order_paymentable';

    public function orders(){
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

}
