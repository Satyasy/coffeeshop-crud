<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Models\User;

// Import semua controller yang akan digunakan
use App\Http\Controllers\PageController; // Asumsi Anda punya PageController
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Rute Halaman Publik (Tidak Perlu Login) ---
// Ganti ini dengan route ke halaman depan Anda jika ada
Route::get('/', function () {
    return view('welcome');
})->name('home');


// --- Rute Autentikasi ---
Route::middleware('guest')->group(function () {
    Route::get('login', fn() => view('auth.login'))->name('login');
    Route::post('login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            // Arahkan ke halaman utama admin setelah login
            return redirect()->intended(route('users.index'));
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
    });

    Route::get('register', fn() => view('auth.register'))->name('register');
    Route::post('register', function (Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        Auth::login($user);
        return redirect()->route('home');
    });
});


// --- Rute Panel Admin (Membutuhkan Login) ---
Route::middleware('auth')->group(function () {

    Route::post('logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

    // Menggunakan Route::resource standar tanpa prefix nama.
    // Ini akan membuat nama route seperti 'users.index', 'menus.store', dll.
    // yang cocok dengan view dan controller yang sudah kita perbaiki.
    Route::resource('users', UserController::class)->parameters(['users' => 'user']);
    Route::resource('menus', MenuController::class)->parameters(['menus' => 'menu']);
    Route::resource('orders', OrderController::class)->parameters(['orders' => 'order']);
    Route::resource('order_items', OrderItemController::class)->parameters(['order_items' => 'order_item']);
    Route::resource('payments', PaymentController::class)->parameters(['payments' => 'payment']);
    Route::resource('reviews', ReviewController::class)->parameters(['reviews' => 'review']);

});