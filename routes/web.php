<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Rute Halaman Publik (Bisa diakses siapa saja) ---
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/menu', [PageController::class, 'menu'])->name('menu');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
// Tambahkan rute publik lain jika ada, misal:
// Route::get('/blog-single', [PageController::class, 'blogSingle'])->name('blog-single');

// --- Rute Autentikasi untuk Pengunjung (Guest) ---
Route::middleware('guest')->group(function () {
    // Form Login
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    // Proses Login
    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

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
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    // Proses Registrasi
    Route::post('/register', function (Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'address' => ['nullable', 'string'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'role' => 'customer', // Default role
        ]);

        Auth::login($user);
        return redirect(route('home'))->with('success', 'Registration successful! Welcome, ' . $user->name);
    });
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

    // --- GRUP ADMIN (Admin & Kasir) ---
    Route::middleware('role:admin,cashier')->prefix('admin')->name('admin.')->group(function () {

        // DIPERBARUI: Tambahkan route ini untuk redirect /admin ke dashboard
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });

        Route::get('/dashboard', function () {
            // Logika untuk dashboard Anda
            return view('admin.dashboard'); // Pastikan Anda punya view ini
        })->name('dashboard');

        // ... sisa resource route Anda ...
        Route::resource('users', UserController::class);
        Route::resource('menus', MenuController::class);
        Route::resource('orders', OrderController::class);
        Route::resource('order-items', OrderItemController::class);
        Route::resource('payments', PaymentController::class);
        Route::resource('reviews', ReviewController::class);
    });

    // --- GRUP ADMIN (Admin & Kasir) ---
    Route::middleware('role:admin,cashier')->prefix('admin')->name('admin.')->group(function () {

        // Redirect '/admin' ke dashboard admin
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });

        // DIPERBARUI: Menggunakan DashboardController
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // ... sisa resource route Anda ...
    });
});