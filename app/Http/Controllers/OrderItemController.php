<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        $orderItems = OrderItem::with('order', 'menu')->latest()->paginate(10);
        return view('order_items.index', compact('orderItems'));
    }

    public function create()
    {
        $orders = Order::orderBy('id', 'desc')->get();
        $menus = Menu::orderBy('name')->get();
        return view('order_items.create', compact('orders', 'menus'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        OrderItem::create($validatedData);

        return redirect()->route('admin.order-items.index')->with('success', 'Order item created successfully.');
    }

    public function show(OrderItem $orderItem)
    {
        $orderItem->load('order', 'menu');
        return view('order_items.show', compact('orderItem'));
    }

    public function edit(OrderItem $orderItem)
    {
        $orders = Order::orderBy('id', 'desc')->get();
        $menus = Menu::orderBy('name')->get();
        return view('order_items.edit', compact('orderItem', 'orders', 'menus'));
    }

    public function update(Request $request, OrderItem $orderItem)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $orderItem->update($validatedData);

        return redirect()->route('admin.order-items.index')->with('success', 'Order item updated successfully.');
    }

    public function destroy(OrderItem $orderItem)
    {
        $orderItem->delete();
        return redirect()->route('admin.order-items.index')->with('success', 'Order item deleted successfully.');
    }
}