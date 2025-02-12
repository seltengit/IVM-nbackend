<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $orders = Order::all();
        return response()->json(['orders' => $orders], 200);
    }

    public function show($id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json(['order' => $order], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'email|nullable',
            'customer_phone' => 'string|nullable',
            'address' => 'required|string',
            'total_price' => 'required|numeric|min:0',
            'status' => 'in:pending,processing,completed,canceled',
        ]);

        $order = Order::create([
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'address' => $request->address,
            'total_price' => $request->total_price,
            'status' => $request->status ?? 'pending',
        ]);

        return response()->json(['message' => 'Order created successfully', 'order' => $order], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $request->validate([
            'customer_name' => 'string|max:255',
            'customer_email' => 'email|nullable',
            'customer_phone' => 'string|nullable',
            'address' => 'string',
            'total_price' => 'numeric|min:0',
            'status' => 'in:pending,processing,completed,canceled',
        ]);

        $order->update([
            'customer_name' => $request->customer_name ?? $order->customer_name,
            'customer_email' => $request->customer_email ?? $order->customer_email,
            'customer_phone' => $request->customer_phone ?? $order->customer_phone,
            'address' => $request->address ?? $order->address,
            'total_price' => $request->total_price ?? $order->total_price,
            'status' => $request->status ?? $order->status,
        ]);

        return response()->json(['message' => 'Order updated successfully', 'order' => $order], 200);
    }

    public function destroy($id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }
}
