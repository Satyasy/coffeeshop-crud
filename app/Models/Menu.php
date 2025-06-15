<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
    ];

    /**
     * Relasi: Satu item Menu bisa ada di banyak OrderItem.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi: Satu item Menu bisa memiliki banyak Review.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getRouteKeyName()
    {
        return 'menu_id'; // Beritahu Laravel untuk menggunakan 'menu_id' untuk Route Model Binding
    }
}