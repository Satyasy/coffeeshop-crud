<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu; // Import model Menu
use App\Models\Review; // Import model Review
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

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
    
    /**
     * Menampilkan halaman dashboard admin dengan data real-time.
     */
    public function dashboard()
    {
        // Hitung total pengguna
        $totalUsers = User::count();
        
        // Hitung total penjualan (dari order yang sudah selesai)
        $totalSales = Order::where('status', Order::STATUS_COMPLETED)
                          ->sum('total_price');
        
        // Hitung total stok menu yang tersedia
        $totalStock = Menu::sum('stock');
        
        // Hitung total pembayaran sukses
        $totalPayments = Payment::where('status', Payment::STATUS_PAID)
                              ->count();
        
        // Data untuk grafik pendapatan 7 hari terakhir
        $revenueData = Order::where('status', Order::STATUS_COMPLETED)
                           ->where('created_at', '>=', now()->subDays(7))
                           ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as revenue'))
                           ->groupBy('date')
                           ->orderBy('date')
                           ->get();
        
        $dates = [];
        $revenues = [];
        
        // Siapkan data untuk 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = now()->subDays($i)->format('D'); // Format hari (Sen, Sel, dst)
            
            // Cari revenue untuk tanggal ini
            $revenue = $revenueData->firstWhere('date', $date);
            $revenues[] = $revenue ? $revenue->revenue : 0;
        }
        
        // Data untuk grafik peran pengguna
        $userRoles = User::select('role', DB::raw('count(*) as total'))
                        ->groupBy('role')
                        ->get();
        
        $roles = [];
        $roleCounts = [];
        
        foreach ($userRoles as $role) {
            $roles[] = ucfirst($role->role); // Kapitalisasi nama peran
            $roleCounts[] = $role->total;
        }
        
        // Data untuk grafik menu terlaris
        $topMenus = DB::table('order_items')
                     ->join('menus', 'order_items.menu_id', '=', 'menus.menu_id')
                     ->select('menus.name', DB::raw('SUM(order_items.quantity) as total_sold'))
                     ->groupBy('menus.name')
                     ->orderByDesc('total_sold')
                     ->limit(5)
                     ->get();
        
        $menuNames = [];
        $menuSales = [];
        
        foreach ($topMenus as $menu) {
            $menuNames[] = $menu->name;
            $menuSales[] = $menu->total_sold;
        }
        
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalSales', 
            'totalStock', 
            'totalPayments',
            'dates',
            'revenues',
            'roles',
            'roleCounts',
            'menuNames',
            'menuSales'
        ));
    }
}