<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'status',
        'payment_method',
    ];

    /**
     * Relasi: Satu Payment dimiliki oleh satu Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}