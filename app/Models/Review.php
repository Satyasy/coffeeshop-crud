<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'menu_id',
        'rating',
        'comment',
    ];

    /**
     * Relasi: Satu Review dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Satu Review merujuk ke satu Menu.
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}