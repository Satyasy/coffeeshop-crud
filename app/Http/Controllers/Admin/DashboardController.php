<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Menu;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung total pengguna dengan peran 'customer'
        $totalUsers = User::where('role', 'customer')->count();

        // Menghitung total pendapatan dari pembayaran yang sudah 'completed'
        $totalSales = Payment::where('status', 'completed')->sum('amount');

        // Menghitung total sisa stok dari semua menu
        $totalStock = Menu::sum('stock');

        // Menghitung jumlah pembayaran yang sukses
        $totalPayments = Payment::where('status', 'completed')->count();

        // Mengirim semua data statistik ke view
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalSales',
            'totalStock',
            'totalPayments'
        ));
    }
}