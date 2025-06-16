<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'order_id',
        'amount',
        'status',
        'payment_method',
    ];
    
    // Konstanta untuk status pembayaran
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';
    
    /**
     * Mendapatkan daftar status pembayaran
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_PAID => 'Dibayar',
            self::STATUS_FAILED => 'Gagal'
        ];
    }

    /**
     * Relasi: Satu Payment dimiliki oleh satu Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}