<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $orderTypes = Order::getOrderTypes();
        $statuses = Order::getStatuses();
        return view('admin.orders.create', compact('users', 'orderTypes', 'statuses'));
    }

    public function store(Request $request)
{
    // Hapus 'order_type' dari validasi
    $validator = Validator::make($request->all(), [
        'user_id' => 'nullable|exists:users,user_id',
        // 'order_type' => ['required', Rule::in(array_keys(Order::getOrderTypes()))], // HAPUS BARIS INI
        'status' => ['required', Rule::in(array_keys(Order::getStatuses()))],
        'total_price' => 'required|numeric|min:0',
        'delivery_address' => 'nullable|string', // Tidak lagi required_if
        'notes_for_restaurant' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return redirect()->route('orders.create')
                    ->withErrors($validator)
                    ->withInput();
    }

    $data = $request->except('order_type'); // Ambil semua data kecuali order_type

    // Tetapkan nilai default secara manual
    $data['order_type'] = 'pickup'; // atau nilai default lain yang Anda inginkan

    Order::create($data);
    return redirect()->route('orders.index')->with('success', 'Order baru berhasil dibuat.');
}

    public function show(Order $order)
    {
        $order->load('user', 'orderItems.menu');
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $users = User::orderBy('name')->get();
        $orderTypes = Order::getOrderTypes();
        $statuses = Order::getStatuses();
        return view('admin.orders.edit', compact('order', 'users', 'orderTypes', 'statuses'));
    }

    public function update(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,user_id',
            'status' => ['required', Rule::in(array_keys(Order::getStatuses()))],
            'total_price' => 'required|numeric|min:0',
            'delivery_address' => 'nullable|string|required_if:order_type,' . Order::TYPE_DELIVERY,
            'notes_for_restaurant' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('orders.edit', $order->order_id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $order->update($request->all());
        return redirect()->route('orders.index')->with('success', 'Data order berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        if ($order->orderItems()->count() > 0) {
            return redirect()->route('orders.index')->with('error', 'Order tidak bisa dihapus karena memiliki item pesanan.');
        }
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order berhasil dihapus.');
    }
}