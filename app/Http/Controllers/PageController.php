<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu; // Import model Menu
use App\Models\Review; // Import model Review

class PageController extends Controller
{
    /**
     * Menampilkan halaman utama (home/index) dengan data testimoni/review.
     */
// di dalam PageController.php

public function home()
{
    // Ambil 5 review terbaru untuk testimoni
    $reviews = Review::with('user')
                     ->where('is_anonymous', false)
                     ->latest()
                     ->take(5)
                     ->get();

    // Hapus pengambilan data $featuredMenus
    // $featuredMenus = Menu::where('is_available', true)->latest()->take(4)->get();

    // Kirim hanya data reviews ke view
    return view('pages.index', compact('reviews'));
}

    /**
     * Menampilkan halaman 'About'.
     */
    public function about()
    {
        // Jika halaman 'about' juga butuh data review, tambahkan di sini.
        // Jika tidak, biarkan kosong.
        return view('pages.about');
    }

    /**
     * Menampilkan halaman menu publik dengan data dari database.
     */
    public function menu()
{
    // Ambil semua menu yang tersedia dan kelompokkan berdasarkan kategori
    $menus = Menu::where('is_available', true)
                 ->latest()
                 ->get()
                 ->groupBy('category'); // Ini sudah benar

    // Ambil semua kategori unik untuk membuat tombol/tab navigasi
    $categories = Menu::where('is_available', true)
                      ->distinct()
                      ->pluck('category');

    // Kirim kedua data ke view
    return view('pages.menu', compact('menus', 'categories'));
}

    /**
     * Menampilkan halaman 'Services'.
     */
    public function services()
    {
        // Dihapus dari routes/web.php untuk menghindari error,
        // tapi jika ingin digunakan lagi, pastikan view-nya ada.
        return view('pages.services');
    }

    /**
     * Menampilkan halaman 'Blog'.
     */
    public function blog()
    {
        return view('pages.blog');
    }
    
    /**
     * Menampilkan halaman 'Contact'.
     */
    public function contact()
    {
        return view('pages.contact');
    }
}