<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index()
    {
        $cartItems = session()->get('cart', []);
        $total = 0;
        
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('pages.checkout', compact('cartItems', 'total'));
    }

    /**
     * Process the order and payment.
     */
    public function placeOrder(Request $request)
    {
        // Validate request
        $request->validate([
            'order_type' => 'required|in:pickup,delivery',
            'delivery_address' => 'required_if:order_type,delivery',
            'payment_method' => 'required|in:cash,transfer,e-wallet',
        ]);

        // Get cart items
        $cartItems = session()->get('cart', []);
        
        if (empty($cartItems)) {
            return redirect()->route('menu')->with('error', 'Keranjang belanja kosong');
        }

        // Calculate total
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        try {
            DB::beginTransaction();
            
            // Create order
            $order = new Order([
                'user_id' => Auth::id(),
                'order_type' => $request->order_type,
                'status' => Order::STATUS_PENDING,
                'total_price' => $total,
                'delivery_address' => $request->order_type === 'delivery' ? $request->delivery_address : null,
                'notes_for_restaurant' => $request->notes_for_restaurant,
            ]);
            $order->save();
            
            // Debug
            if (!$order->order_id) {
                throw new \Exception("Order ID tidak tersedia setelah menyimpan order");
            }
            
            // Create order items
            foreach ($cartItems as $id => $details) {
                $menu = Menu::find($id);
                
                if (!$menu) {
                    throw new \Exception("Menu dengan ID {$id} tidak ditemukan");
                }
                
                OrderItem::create([
                    'order_id' => $order->order_id,
                    'menu_id' => $id,
                    'quantity' => $details['quantity'],
                    'price_at_order' => $details['price'],
                    'notes' => $details['notes'] ?? null,
                ]);
            }
            
            // Create payment record - hanya menggunakan kolom yang ada di database
            $payment = Payment::create([
                'order_id' => $order->order_id,
                'user_id' => Auth::id(),
                'amount' => $total,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
            ]);
            
            // Update order with payment_id
            $order->payment_id = $payment->payment_id;
            $order->save();
            
            DB::commit();
            
            // Clear cart
            session()->forget('cart');
            
            return redirect()->route('payment.show', $order->order_id)
                ->with('success', 'Pesanan berhasil dibuat');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}