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

    public function create(Request $request)
{
    // Cek apakah ada order_id yang dikirim dari URL
    if ($request->has('order_id')) {
        $order = Order::findOrFail($request->order_id);
        // Kirim hanya satu order ini ke view
        $orders = collect([$order]);
        $selectedOrderId = $order->order_id;
    } else {
        // Logika lama jika membuka halaman create payment secara manual
        $orders = Order::whereDoesntHave('payment', function ($query) {
            $query->whereIn('status', ['paid', 'pending']);
        })->orderBy('order_id', 'desc')->get();
        $selectedOrderId = null;
    }
    
    $users = User::orderBy('name')->get();
    $statuses = Payment::getStatuses();

    return view('pages.payment', compact('orders', 'users', 'statuses', 'selectedOrderId'));
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

    public function showPaymentPage(Order $order)
{
    // Pastikan order ini milik user yang sedang login (keamanan)
    if ($order->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    // Pastikan order belum dibayar
    if ($order->payment && $order->payment->status === 'paid') {
        return redirect()->route('home')->with('info', 'Pesanan ini sudah dibayar.');
    }

    // Kirim data order ke view
    return view('pages.payment', compact('order'));
}
}