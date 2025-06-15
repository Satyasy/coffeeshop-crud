<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('user', 'menu')->latest()->paginate(10);
        return view('reviews.index', compact('reviews'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $menus = Menu::orderBy('name')->get();
        return view('reviews.create', compact('users', 'menus'));
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
            'user_id' => 'required|exists:users,user_id', // Corrected: users table uses user_id
            'menu_id' => 'required|exists:menus,menu_id', // Corrected: menus table uses menu_id
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