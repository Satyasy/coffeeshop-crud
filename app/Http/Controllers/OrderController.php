<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
}