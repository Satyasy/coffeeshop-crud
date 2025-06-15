<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja.
     */
    public function index()
    {
        $cartItems = session()->get('cart', []);
        
        // Hitung total harga
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('pages.cart', compact('cartItems', 'total'));
    }

    /**
     * Menambahkan item ke keranjang belanja.
     */
    public function add(Request $request, Menu $menu)
    {
        // Validasi input kuantitas
        $request->validate(['quantity' => 'required|integer|min:1']);
        
        // Ambil keranjang dari session, atau buat array kosong jika belum ada
        $cart = session()->get('cart', []);
        $menuId = $menu->menu_id;

        // Cek apakah item sudah ada di keranjang
        if (isset($cart[$menuId])) {
            // Jika sudah ada, tambahkan kuantitasnya
            $cart[$menuId]['quantity'] += $request->quantity;
        } else {
            // Jika belum ada, tambahkan sebagai item baru
            $cart[$menuId] = [
                "name" => $menu->name,
                "quantity" => $request->quantity,
                "price" => $menu->price,
                "image_url" => $menu->image_url
            ];
        }

        // Simpan kembali array $cart ke dalam session
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Menu berhasil ditambahkan ke keranjang!');
    }

    /**
     * Mengupdate kuantitas item di keranjang.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cart = session()->get('cart', []);
        $menuId = $menu->menu_id;

        if (isset($cart[$menuId])) {
            $cart[$menuId]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui.');
        }

        return redirect()->route('cart.index')->with('error', 'Item tidak ditemukan di keranjang.');
    }

    /**
     * Menghapus item dari keranjang.
     */
    public function remove(Menu $menu)
    {
        $cart = session()->get('cart', []);
        $menuId = $menu->menu_id;

        if (isset($cart[$menuId])) {
            unset($cart[$menuId]); // Hapus item dari array
            session()->put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
        }

        return redirect()->route('cart.index')->with('error', 'Item tidak ditemukan di keranjang.');
    }

    /**
     * Mengosongkan semua isi keranjang.
     */
    public function clear()
    {
        session()->forget('cart'); // Hapus key 'cart' dari session
        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan.');
    }
}