<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu; // JANGAN LUPA IMPORT MODEL MENU

class PageController extends Controller
{
    public function home()
    {
        // ... (logika untuk halaman home)
        return view('pages.index'); // Asumsi nama view
    }

    public function about()
    {
        // ... (logika untuk halaman about)
        return view('pages.about'); // Asumsi nama view
    }

    /**
     * Menampilkan halaman menu publik dengan data dari database.
     */
    public function menu()
    {
        // Ambil semua menu yang tersedia dan kelompokkan berdasarkan kategori
        $menus = Menu::where('is_available', true)
                     ->get()
                     ->groupBy('category'); // Ini akan mengelompokkan menu

        // Kirim data menu yang sudah dikelompokkan ke view
        return view('pages.menu', compact('menus'));
    }

    // ... method lain untuk halaman publik ...
    public function services()
    {
        return view('pages.services');
    }

    public function blog()
    {
        return view('pages.blog');
    }
    
    public function contact()
    {
        return view('pages.contact');
    }
}