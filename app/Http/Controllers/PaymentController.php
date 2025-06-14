<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('order.user', 'user')->latest();

        if ($request->filled('order_id')) {
            $query->where('order_id', $request->order_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(15);
        $orders = Order::orderBy('order_id', 'desc')->get();
        $statuses = Payment::getStatuses();

        return view('admin.payments.index', compact('payments', 'orders', 'statuses'));
    }

    public function create(Request $request)
    {
        $orders = Order::whereDoesntHave('payment', function ($query) {
            $query->whereIn('status', ['paid', 'pending']);
        })->orderBy('order_id', 'desc')->get();
        
        $users = User::orderBy('name')->get();
        $statuses = Payment::getStatuses();
        $selectedOrderId = $request->input('order_id');

        return view('admin.payments.create', compact('orders', 'users', 'statuses', 'selectedOrderId'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,order_id',
            'user_id' => 'required|exists:users,user_id',
            'amount' => 'required|numeric|min:0',
            'status' => ['required', Rule::in(array_keys(Payment::getStatuses()))],
            'payment_method' => 'required|string|max:50',
            'payment_time' => 'nullable|date',
            'payment_gateway_reference' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('payments.create', ['order_id' => $request->order_id])
                        ->withErrors($validator)
                        ->withInput();
        }

        $payment = Payment::create($request->all());
        
        $order = Order::find($request->order_id);
        if($order) {
            $order->payment_id = $payment->payment_id;
            $order->save();
        }
        
        return redirect()->route('payments.index')->with('success', 'Data pembayaran berhasil disimpan.');
    }

    public function edit(Payment $payment)
    {
        $payment->load('order', 'user');
        $orders = Order::where('order_id', $payment->order_id)->get();
        $users = User::orderBy('name')->get();
        $statuses = Payment::getStatuses();

        return view('admin.payments.edit', compact('payment', 'orders', 'users', 'statuses'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,user_id',
            'amount' => 'required|numeric|min:0',
            'status' => ['required', Rule::in(array_keys(Payment::getStatuses()))],
            'payment_method' => 'required|string|max:50',
            'payment_time' => 'nullable|date',
            'payment_gateway_reference' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('payments.edit', $payment->payment_id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $payment->update($request->all());
        return redirect()->route('payments.index')->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    public function destroy(Payment $payment)
    {
        if ($payment->order) {
            $payment->order()->update(['payment_id' => null]);
        }
        
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Data pembayaran berhasil dihapus.');
    }
}