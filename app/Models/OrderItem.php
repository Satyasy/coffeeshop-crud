<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'order_item_id';

    protected $fillable = [
        'order_id',
        'menu_id',
        'quantity',
        'price_at_order',
        'notes'
    ];

    /**
     * Relasi: Satu OrderItem dimiliki oleh satu Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /**
     * Relasi: Satu OrderItem merujuk ke satu Menu.
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }
}