<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_id',
        'quantity',
        'price',
    ];

    /**
     * Relasi: Satu OrderItem dimiliki oleh satu Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi: Satu OrderItem merujuk ke satu Menu.
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}