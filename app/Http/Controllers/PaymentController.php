<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('order.user')->latest()->paginate(10);
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $orders = Order::whereDoesntHave('payment')->orderBy('id', 'desc')->get();
        return view('payments.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id|unique:payments,order_id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:255',
            'status' => ['required', Rule::in(['pending', 'completed', 'failed'])],
        ]);

        Payment::create($validatedData);

        return redirect()->route('admin.payments.index')->with('success', 'Payment record created successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load('order.user');
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $orders = Order::orderBy('id', 'desc')->get();
        return view('payments.edit', compact('payment', 'orders'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validatedData = $request->validate([
            'order_id' => ['required', 'exists:orders,id', Rule::unique('payments')->ignore($payment->id)],
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:255',
            'status' => ['required', Rule::in(['pending', 'completed', 'failed'])],
        ]);

        $payment->update($validatedData);

        return redirect()->route('admin.payments.index')->with('success', 'Payment record updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Payment record deleted successfully.');
    }
}