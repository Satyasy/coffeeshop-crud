<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use App\Models\User;
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
            $orders = Order::whereDoesntHave('payments', function ($query) {
                $query->whereIn('status', ['paid', 'pending']);
            })->orderBy('order_id', 'desc')->get();
            $selectedOrderId = null;
        }
        
        $statuses = Payment::getStatuses();

        return view('payments.create', compact('orders', 'statuses', 'selectedOrderId'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,order_id|unique:payments,order_id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:255',
            'status' => ['required', Rule::in(array_keys(Payment::getStatuses()))],
        ]);

        $payment = Payment::create($validatedData);
        
        // Update order status jika pembayaran berhasil
        if ($payment->status === Payment::STATUS_PAID) {
            $order = Order::find($payment->order_id);
            if ($order) {
                $order->status = Order::STATUS_PROCESSING;
                $order->save();
            }
        }

        return redirect()->route('admin.payments.index')->with('success', 'Payment record created successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load('order.user');
        $statuses = Payment::getStatuses();
        return view('payments.show', compact('payment', 'statuses'));
    }

    public function edit(Payment $payment)
    {
        $orders = Order::orderBy('order_id', 'desc')->get();
        $statuses = Payment::getStatuses();
        return view('payments.edit', compact('payment', 'orders', 'statuses'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validatedData = $request->validate([
            'order_id' => ['required', 'exists:orders,order_id', Rule::unique('payments')->ignore($payment->payment_id, 'payment_id')],
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:255',
            'status' => ['required', Rule::in(array_keys(Payment::getStatuses()))],
            'payment_time' => 'nullable|date',
        ]);

        $oldStatus = $payment->status;
        $payment->update($validatedData);
        
        // Update order status jika pembayaran berhasil
        if ($oldStatus !== Payment::STATUS_PAID && $payment->status === Payment::STATUS_PAID) {
            $order = Order::find($payment->order_id);
            if ($order) {
                $order->status = Order::STATUS_PROCESSING;
                $order->save();
            }
        }

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
        if ($order->payment && $order->payment->status === Payment::STATUS_PAID) {
            return redirect()->route('home')->with('info', 'Pesanan ini sudah dibayar.');
        }

        // Kirim data order ke view
        return view('pages.payment', compact('order'));
    }
}