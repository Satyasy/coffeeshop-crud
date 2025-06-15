<?php

namespace App\Helpers;

class CartHelper
{
    /**
     * Menghitung jumlah total item di dalam keranjang.
     *
     * @return int
     */
    public static function itemCount(): int
    {
        // Ambil data keranjang dari session
        $cart = session()->get('cart', []);
        
        // Kembalikan jumlah item unik di keranjang
        return count($cart);
    }
}