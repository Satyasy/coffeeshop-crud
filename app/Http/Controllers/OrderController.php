<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // Pastikan Auth di-import
use Illuminate\Support\Facades\DB;   // Import DB untuk transaction

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user', 'items')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('orders.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => ['required', 'string', Rule::in(['pending', 'processing', 'completed', 'cancelled'])],
            'total_price' => 'required|numeric|min:0',
        ]);

        Order::create($validatedData);

        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.menu', 'payment');
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $users = User::orderBy('name')->get();
        return view('orders.edit', compact('order', 'users'));
    }

    public function update(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => ['required', 'string', Rule::in(['pending', 'processing', 'completed', 'cancelled'])],
            'total_price' => 'required|numeric|min:0',
        ]);

        $order->update($validatedData);

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        // Migrasi dengan onDelete('cascade') akan menghapus order_items dan payment terkait
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');

        
    }

     // =======================================================
    // === TAMBAHKAN METHOD BARU INI ===
    // =======================================================

    /**
     * Menampilkan halaman checkout.
     */
    public function checkout()
{
    $cartItems = session()->get('cart', []);

    // Jangan biarkan checkout jika keranjang kosong
    if (count($cartItems) == 0) {
        return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong, tidak bisa checkout.');
    }
    
    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Kirim data keranjang dan total harga ke view checkout
    return view('pages.checkout', compact('cartItems', 'total'));
}

    /**
     * Memproses pesanan dari halaman checkout.
     */
    // di dalam OrderController.php

public function placeOrder(Request $request)
{
    // 1. Validasi input sederhana dari form checkout
    $validated = $request->validate([
        'delivery_address' => 'nullable|string|max:1000',
        'notes_for_restaurant' => 'nullable|string|max:1000',
    ]);

    // 2. Ambil data penting
    $cartItems = session()->get('cart', []);
    $user = Auth::user();

    // 3. Jangan proses jika keranjang kosong
    if (count($cartItems) == 0) {
        return redirect()->route('menu')->with('error', 'Keranjang Anda kosong.');
    }

    // 4. Mulai database transaction untuk keamanan data
    DB::beginTransaction();

    try {
        // Hitung total harga
        $totalPrice = collect($cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        // 5. Buat pesanan baru di database
        $order = Order::create([
            'user_id' => $user->user_id,
            'status' => 'pending', // Status awal saat pesanan dibuat
            'order_type' => 'pickup', // Nilai default, sesuaikan jika perlu
            'total_price' => $totalPrice,
            'delivery_address' => $validated['delivery_address'] ?? null,
            'notes_for_restaurant' => $validated['notes_for_restaurant'] ?? null,
            // payment_id dibiarkan kosong (null) karena pembayarannya belum ada
        ]);

        // 6. Simpan setiap item di keranjang ke database
        foreach ($cartItems as $menuId => $details) {
            $order->orderItems()->create([
                'menu_id' => $menuId,
                'quantity' => $details['quantity'],
                'price_at_order' => $details['price'],
            ]);
        }

        // 7. Jika semua berhasil, simpan perubahan ke database
        DB::commit();

        // 8. Kosongkan keranjang belanja
        session()->forget('cart');

        // 9. (INI BAGIAN PENTING) Redirect ke halaman pembayaran dengan membawa ID order
        return redirect()->route('payment.show', ['order' => $order->order_id])
                         ->with('success', 'Pesanan Anda berhasil dibuat! Silakan pilih metode pembayaran.');

    } catch (\Exception $e) {
        // 10. Jika ada error, batalkan semua yang sudah dilakukan
        DB::rollBack();

         dd($e);

        // Untuk debugging, Anda bisa melihat error asli di storage/logs/laravel.log
        // \Log::error('Checkout Error: ' . $e->getMessage());

        // Kembalikan ke halaman checkout dengan pesan error umum
        return redirect()->route('checkout')->with('error', 'Terjadi kesalahan internal. Silakan coba lagi.');
    }
}
}