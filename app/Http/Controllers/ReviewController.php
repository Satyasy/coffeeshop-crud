<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use App\Models\User;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'menu', 'order'])->latest('created_at');

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $menus = Menu::orderBy('name')->get();
        $orders = Order::orderBy('order_id', 'desc')->get();
        return view('admin.reviews.create', compact('users', 'menus', 'orders'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,order_id',
            'user_id' => 'required|exists:users,user_id',
            'menu_id' => 'required|exists:menus,menu_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'is_anonymous' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('reviews.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $request->all();
        $data['is_anonymous'] = $request->has('is_anonymous') ? 1 : 0;

        Review::create($data);
        return redirect()->route('reviews.index')->with('success', 'Review berhasil ditambahkan.');
    }

    public function edit(Review $review)
    {
        $review->load(['order', 'user', 'menu']);
        $users = User::orderBy('name')->get();
        $menus = Menu::orderBy('name')->get();
        $orders = Order::orderBy('order_id', 'desc')->get();
        return view('admin.reviews.edit', compact('review', 'users', 'menus', 'orders'));
    }

    public function update(Request $request, Review $review)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,order_id',
            'user_id' => 'required|exists:users,user_id',
            'menu_id' => 'required|exists:menus,menu_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'is_anonymous' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('reviews.edit', $review->review_id)
                        ->withErrors($validator)
                        ->withInput();
        }
        
        $data = $request->all();
        $data['is_anonymous'] = $request->has('is_anonymous') ? 1 : 0;

        $review->update($data);
        return redirect()->route('reviews.index')->with('success', 'Review berhasil diperbarui.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('reviews.index')->with('success', 'Review berhasil dihapus.');
    }
}