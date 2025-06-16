<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('user', 'menu')->latest()->paginate(10);
        return view('reviews.index', compact('reviews'));
    }

    public function create(Request $request)
    {
        // Cek apakah ada order_id yang dikirim dari URL
        if ($request->has('order_id')) {
            $order = Order::findOrFail($request->order_id);
            $orders = collect([$order]);
            $selectedOrderId = $order->order_id;
        } else {
            // Ambil order yang sudah selesai dan belum direview
            $orders = Order::where('status', 'completed')
                ->whereDoesntHave('reviews')
                ->orderBy('order_id', 'desc')
                ->get();
            $selectedOrderId = null;
        }
        
        return view('reviews.create', compact('orders', 'selectedOrderId'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'menu_id' => 'required|exists:menus,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        Review::create($validatedData);

        return redirect()->route('admin.reviews.index')->with('success', 'Review created successfully.');
    }

    public function show(Review $review)
    {
        $review->load('user', 'menu');
        return view('reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
        $users = User::orderBy('name')->get();
        $menus = Menu::orderBy('name')->get();
        return view('reviews.edit', compact('review', 'users', 'menus'));
    }

    public function update(Request $request, Review $review)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'menu_id' => 'required|exists:menus,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $review->update($validatedData);

        return redirect()->route('admin.reviews.index')->with('success', 'Review updated successfully.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully.');
    }
}