<?php
// 

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
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi: Satu Order memiliki satu Payment.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}