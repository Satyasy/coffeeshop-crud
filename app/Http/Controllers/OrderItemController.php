<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderItemController extends Controller
{
    public function index(Request $request)
    {
        $query = OrderItem::with(['order.user', 'menu'])->latest();
        if ($request->filled('order_id')) {
            $query->where('order_id', $request->order_id);
        }
        $orderItems = $query->paginate(15);
        $orders = Order::orderBy('order_id', 'desc')->get();
        return view('admin.order_items.index', compact('orderItems', 'orders'));
    }

    public function create(Request $request)
    {
        $orders = Order::orderBy('order_id', 'desc')->get();
        $menus = Menu::where('is_available', true)->orderBy('name')->get();
        $selectedOrderId = $request->input('order_id');
        return view('admin.order_items.create', compact('orders', 'menus', 'selectedOrderId'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,order_id',
            'menu_id' => 'required|exists:menus,menu_id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('order_items.create', ['order_id' => $request->order_id])
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $menu = Menu::findOrFail($request->menu_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Menu yang dipilih tidak valid.')->withInput();
        }

        $data = $request->only(['order_id', 'menu_id', 'quantity', 'notes']);
        $data['price_at_order'] = $menu->price;

        OrderItem::create($data);
        $this->updateOrderTotalPrice($request->order_id);

        return redirect()->route('orders.show', $request->order_id)->with('success', 'Item berhasil ditambahkan ke pesanan.');
    }

    public function edit(OrderItem $order_item)
    {
        $order_item->load('order');
        $orders = Order::orderBy('order_id', 'desc')->get();
        $menus = Menu::orderBy('name')->get();
        return view('admin.order_items.edit', compact('order_item', 'orders', 'menus'));
    }

    public function update(Request $request, OrderItem $order_item)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,order_id',
            'menu_id' => 'required|exists:menus,menu_id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('order_items.edit', $order_item->order_item_id)
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $menu = Menu::findOrFail($request->menu_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Menu yang dipilih tidak valid.')->withInput();
        }

        $data = $request->only(['order_id', 'menu_id', 'quantity', 'notes']);
        $data['price_at_order'] = $menu->price;
        $order_item->update($data);
        $this->updateOrderTotalPrice($order_item->order_id);

        return redirect()->route('orders.show', $order_item->order_id)->with('success', 'Item pesanan berhasil diperbarui.');
    }

    public function destroy(OrderItem $order_item)
    {
        $orderId = $order_item->order_id;
        $order_item->delete();
        if ($orderId) {
            $this->updateOrderTotalPrice($orderId);
        }
        return redirect()->back()->with('success', 'Item pesanan berhasil dihapus.');
    }

    protected function updateOrderTotalPrice($orderId)
    {
        if (!$orderId) return;
        $order = Order::with('orderItems')->find($orderId);
        if ($order) {
            $totalPrice = $order->orderItems->sum(function ($item) {
                return $item->quantity * $item->price_at_order;
            });
            $order->total_price = $totalPrice;
            $order->save();
        }
    }
}