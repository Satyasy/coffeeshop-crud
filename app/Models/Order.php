<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'user_id',
        'payment_id',
        'status',
        'total_price',
        'order_type',
        'delivery_address',
        'notes_for_restaurant'
    ];
    
    // Konstanta untuk status order
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    
    // Konstanta untuk tipe order
    const TYPE_DINE_IN = 'dine_in';
    const TYPE_TAKEAWAY = 'takeaway';
    const TYPE_DELIVERY = 'delivery';

    /**
     * Relasi: Satu Order dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relasi: Satu Order memiliki banyak OrderItem.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    /**
     * Relasi: Satu Order memiliki satu Payment.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }
}