<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_price',
        'price_at_order',
    ];

    /**
     * Relasi: Satu Order dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Satu Order memiliki banyak OrderItem.
     */
    public function orderItems() // Nama method harus persis seperti ini
{
    return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
}

    /**
     * Relasi: Satu Order memiliki satu Payment.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}