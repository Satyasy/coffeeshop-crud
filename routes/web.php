<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Import semua controller yang akan digunakan
use App\Http\Controllers\PageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Rute Halaman Publik (Bisa diakses siapa saja) ---
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/menu', [PageController::class, 'menu'])->name('menu');


// --- Rute Autentikasi untuk Pengunjung (Guest) ---
Route::middleware('guest')->group(function () {
    // Form Login
    Route::get('/login', fn() => view('auth.login'))->name('login');

    // Proses Login
    Route::post('/login', function (Request $request) {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required']);
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role === 'admin' || $user->role === 'cashier') {
                return redirect()->intended(route('admin.dashboard'));
            }
            return redirect()->intended(route('home'));
        }
        return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
    });

    // Form Registrasi
    Route::get('/register', fn() => view('auth.register'))->name('register');

    // Proses Registrasi
    Route::post('/register', function (Request $request) {
        $request->validate(['name' => 'required|string', 'email' => 'required|email|unique:users,email', 'password' => 'required|string|min:8|confirmed']);
        $user = User::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password), 'role' => 'customer']);
        Auth::login($user);
        return redirect()->route('home')->with('success', 'Registrasi berhasil!');
    })->name('register.store');
});

// --- Rute yang Memerlukan Login ---
Route::middleware('auth')->group(function () {
    // Proses Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');


    // --- Rute untuk Pengguna Biasa (Customer) ---

    // Grup Route untuk Keranjang Belanja (Cart)
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{menu:menu_id}', [CartController::class, 'add'])->name('add');
        Route::patch('/update/{menu}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{menu}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    });

    // Route untuk Checkout
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('checkout.place');
    Route::get('/payment/order/{order}', [PaymentController::class, 'showPaymentPage'])->name('payment.show');


    // --- GRUP ADMIN (Hanya bisa diakses oleh role 'admin' atau 'cashier') ---
    Route::prefix('admin')->middleware('role:admin,cashier')->name('admin.')->group(function () {
        
        Route::get('/dashboard', fn() => redirect()->route('admin.menus.index'))->name('dashboard');
        Route::get('/', fn() => redirect()->route('admin.dashboard'));
        
        // Semua route resource CRUD untuk admin
        Route::resource('users', UserController::class);
        Route::resource('menus', MenuController::class);
        Route::resource('orders', OrderController::class);
        Route::resource('order-items', OrderItemController::class)->parameters(['order-items' => 'order_item']);
        Route::resource('payments', PaymentController::class)->parameters(['payments' => 'payment']);
        Route::resource('reviews', ReviewController::class)->parameters(['reviews' => 'review']);
    });
});