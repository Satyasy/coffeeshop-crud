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
        'user_id',
        'amount',
        'status',
        'payment_method',
        'payment_gateway_reference',
        'payment_time'
    ];
    
    // Konstanta untuk status pembayaran
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';
    
    // Konstanta untuk metode pembayaran
    const METHOD_CASH = 'cash';
    const METHOD_TRANSFER = 'transfer';
    const METHOD_EWALLET = 'e-wallet';
    
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
     * Mendapatkan daftar metode pembayaran
     */
    public static function getMethods()
    {
        return [
            self::METHOD_CASH => 'Tunai',
            self::METHOD_TRANSFER => 'Transfer Bank',
            self::METHOD_EWALLET => 'E-Wallet'
        ];
    }

    /**
     * Relasi: Satu Payment dimiliki oleh satu Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
    
    /**
     * Relasi: Satu Payment dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}