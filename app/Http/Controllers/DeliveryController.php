<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Delivery;
use App\Models\LineItem;
use App\Models\Product; // Ensure the Product model is included

class DeliveryController extends Controller
{
    public function index()
    {
        return response()->json(Delivery::with('lineItems')->get(), 200);
    }

    public function show($id)
    {
        $delivery = Delivery::with('lineItems')->find($id);

        if (!$delivery) {
            return response()->json(['message' => 'Delivery not found'], 404);
        }

        return response()->json($delivery, 200);
    }


    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'customer_address' => 'required|string',
            'customer_phone' => 'required|string',
            'driver_name' => 'required|string',
            'driver_vehicle_no' => 'required|string',
            'driver_phone' => 'required|string',
            'pending' => 'boolean',
            'reason' => 'nullable|in:In sufficient products,Delivery in person,Payment pending',
            'line_items' => 'required|array|min:1',
            'line_items.*.product_name' => 'required|string',
            'line_items.*.product_code' => 'required|string',
            'line_items.*.quantity_required' => 'required|integer|min:1',
            'line_items.*.quantity_delivered' => 'required|integer|min:0',
        ]);

        // Validate product stock before creating the delivery
        foreach ($request->line_items as $item) {
            $product = Product::where('hsincode', $item['product_code'])->first();

            if (!$product) {
                return response()->json(['error' => "Product not found for product code: {$item['product_code']}"], 404);
            }

            if ($product->stocks < $item['quantity_required']) {
                return response()->json(['error' => "Insufficient stock for product: {$item['product_name']}"], 400);
            }
        }

        // Create the delivery entry
        $delivery = Delivery::create($request->only([
            'customer_name',
            'customer_address',
            'customer_phone',
            'driver_name',
            'driver_vehicle_no',
            'driver_phone',
            'pending',
            'reason'

        ]));

        // Insert line items and update product stock
        foreach ($request->line_items as $item) {
            $product = Product::where('hsincode', $item['product_code'])->first();

            if ($product) {
                // Deduct stock from the product table
                $product->stocks -= $item['quantity_required'];
                $product->save();
            }

            // Insert line item
            LineItem::create(array_merge($item, ['delivery_id' => $delivery->id]));
        }

        return response()->json([
            'message' => 'Delivery created successfully',
            'delivery' => $delivery->load('lineItems')
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $delivery = Delivery::find($id);

        if (!$delivery) {
            return response()->json(['message' => 'Delivery not found'], 404);
        }

        $delivery->update($request->only([
            'customer_name',
            'customer_address',
            'customer_phone',
            'driver_name',
            'driver_vehicle_no',
            'driver_phone',
            'pending',
            'reason',
            'remarks',

        ]));
        foreach ($request->line_items as $item) {
            if (!isset($item['id'])) {
                $product = Product::where('hsincode', $item['product_code'])->first();

                if (!$product) {
                    return response()->json(['error' => "Product not found for product code: {$item['product_code']}"], 404);
                }

                if ($product->stocks < $item['quantity_required']) {
                    return response()->json(['error' => "Insufficient stock for product: {$item['product_name']}"], 400);
                }


                if ($product) {
                    // Deduct stock from the product table
                    $product->stocks -= $item['quantity_required'];
                    $product->save();
                }

                LineItem::create(array_merge($item, ['delivery_id' => $delivery->id]));
            }
        }
        return response()->json([
            'message' => 'Delivery updated successfully',
            'delivery' => $delivery->load('lineItems')
        ], 200);
    }
    public function destroy($id)
    {
        $delivery = Delivery::with('lineItems')->find($id);

        if (!$delivery) {
            return response()->json(['message' => 'Delivery not found'], 404);
        }

        DB::transaction(function () use ($delivery) {
            // Retrieve all line items related to this delivery
            $lineItems = $delivery->lineItems;

            // Prepare stock restoration array
            $productStockUpdates = [];
            foreach ($lineItems as $item) {
                $productStockUpdates[$item->product_code] =
                    ($productStockUpdates[$item->product_code] ?? 0) + $item->quantity_required;
            }

            // Restore stock in bulk
            Product::whereIn('hsincode', array_keys($productStockUpdates))
                ->get()
                ->each(function ($product) use ($productStockUpdates) {
                    $product->increment('stocks', $productStockUpdates[$product->hsincode]);
                });

            // Delete all line items in one query
            LineItem::where('delivery_id', $delivery->id)->delete();

            // Delete the delivery
            $delivery->delete();
        });

        return response()->json(['message' => 'Delivery deleted successfully'], 200);
    }
}
