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

// --- Rute Halaman Publik ---
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/menu', [PageController::class, 'menu'])->name('menu');
// ... tambahkan rute publik lain jika perlu

// --- Rute Autentikasi ---
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('auth.login'))->name('login');
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

    Route::get('/register', fn() => view('auth.register'))->name('register');
    Route::post('/register', function (Request $request) {
        $request->validate(['name' => 'required|string', 'email' => 'required|email|unique:users,email', 'password' => 'required|string|min:8|confirmed']);
        $user = User::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password), 'role' => 'customer']);
        Auth::login($user);
        return redirect()->route('home')->with('success', 'Registrasi berhasil!');
    })->name('register.store');
});

// --- Rute yang Memerlukan Login ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

    // --- GRUP ADMIN ---
    Route::prefix('admin')->middleware('role:admin,cashier')->name('admin.')->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
        Route::get('/', fn() => redirect()->route('admin.dashboard'));
        
        Route::resource('users', UserController::class);
        Route::resource('menus', MenuController::class);
        Route::resource('orders', OrderController::class);
        Route::resource('order-items', OrderItemController::class)->parameters(['order-items' => 'order_item']);
        Route::resource('payments', PaymentController::class)->parameters(['payments' => 'payment']);
        Route::resource('reviews', ReviewController::class)->parameters(['reviews' => 'review']);
    });
});