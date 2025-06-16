<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('orderItems')->get();
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,user_id',
            'order_type' => 'required|in:pickup,delivery',
            'total_price' => 'required|numeric',
            'delivery_address' => 'required_if:order_type,delivery|nullable|string',
            'notes_for_restaurant' => 'nullable|string',
            'items' => 'required|array',
            'items.*.menu_id' => 'required|exists:menus,menu_id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price_at_order' => 'required|numeric',
            'items.*.notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            // Create order
            $order = Order::create([
                'user_id' => $request->user_id,
                'order_type' => $request->order_type,
                'status' => Order::STATUS_PENDING,
                'total_price' => $request->total_price,
                'delivery_address' => $request->delivery_address,
                'notes_for_restaurant' => $request->notes_for_restaurant
            ]);
            
            // Create order items
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->order_id,
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'price_at_order' => $item['price_at_order'],
                    'notes' => $item['notes'] ?? null
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $order->load('orderItems')
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::with('orderItems')->find($id);
        
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|required|string',
            'delivery_address' => 'nullable|string',
            'notes_for_restaurant' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $order->update($request->only(['status', 'delivery_address', 'notes_for_restaurant']));

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully',
            'data' => $order
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        try {
            DB::beginTransaction();
            
            // Delete related order items first
            $order->orderItems()->delete();
            
            // Then delete the order
            $order->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}