<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model.
     *
     * @var string
     */
    protected $table = 'order_items';

    /**
     * Primary key untuk model.
     * INI ADALAH PERBAIKAN UTAMA UNTUK ERROR ANDA.
     *
     * @var string
     */
    protected $primaryKey = 'order_item_id';

    /**
     * Menunjukkan jika primary key adalah auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Tipe data dari primary key.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'menu_id',
        'quantity',
        'price_at_order', // DITAMBAHKAN: Ini sangat penting agar `create()` berfungsi
        'notes',
    ];

    /**
     * Tipe data native untuk atribut.
     *
     * @var array
     */
    protected $casts = [
        'quantity' => 'integer',
        'price_at_order' => 'decimal:2',
    ];


    // --- RELASI ---

    /**
     * Relasi ke model Order (Induk).
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /**
     * Relasi ke model Menu (Produk yang dipesan).
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }

    
    // --- ACCESSOR (Contoh Getter) ---

    /**
     * Accessor untuk menghitung subtotal secara dinamis.
     * Anda bisa memanggilnya di view dengan `$item->subtotal`.
     *
     * @return float
     */
    public function getSubtotalAttribute(): float
    {
        // Menggunakan price_at_order agar subtotal konsisten dengan harga saat pemesanan.
        return $this->quantity * $this->price_at_order;
    }
}