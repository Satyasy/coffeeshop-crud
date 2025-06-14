<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
    'user_id',
    'payment_id',
    'order_type',
    'status',
    'total_price',
    'delivery_address',
    'estimated_delivery_time',
    'notes_for_restaurant',
];

    protected $casts = [
        'total_price' => 'decimal:2',
        'estimated_delivery_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'payment_id');
    }

    // INI RELASI YANG MEMPERBAIKI ERROR
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'order_id', 'order_id');
    }

    public const TYPE_PICKUP = 'pickup';
    public const TYPE_DELIVERY = 'delivery';
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public static function getOrderTypes(): array
    {
        return [
            self::TYPE_PICKUP => 'Ambil Sendiri (Pickup)',
            self::TYPE_DELIVERY => 'Diantar (Delivery)',
        ];
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_PROCESSING => 'Sedang Diproses',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];
    }
}