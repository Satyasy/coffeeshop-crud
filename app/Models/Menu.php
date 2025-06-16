<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'menu_id';
    
    protected $fillable = [
        'name',
        'description',
        'price',
        'image_url', // pastikan ini ada
        'stock',
        'category',
        'is_available'
    ];

    // Accessor untuk URL gambar lengkap
    public function getImageUrlAttribute($value)
    {
        return $value ? '/storage/' . $value : null;
    }

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