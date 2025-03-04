<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Http\JsonResponse;
use App\Models\Po_lineitem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;




class PurchaseOrderController extends Controller
{
    // Get all purchase orders
    public function index(): JsonResponse
    {
        return response()->json(PurchaseOrder::with('polineItems')->get(), 200);
    }

    // Get a single purchase order by ID
    public function show($id): JsonResponse
    {
        $purchaseOrder = PurchaseOrder::with('polineItems')->find($id);


        if (!$purchaseOrder) {
            return response()->json(['message' => 'Purchase order not found'], 404);
        }

        return response()->json($purchaseOrder, 200);
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'order_number' => 'required|string',
            'name' => 'string',

            'poline_items' => 'required|array|min:1',
            'poline_items.*.product_name' => 'required|string',
            'poline_items.*.product_code' => 'required|string', // Ensure product_code is validated
            'poline_items.*.quantity' => 'integer|min:1', // Ensure quantity is valid
        ]);

        // Create the purchase order entry
        $purchaseorder = PurchaseOrder::create($request->only(['order_number', 'name']));

        // Insert line items and update stock
        foreach ($request->poline_items as $item) {
            $product = Product::where('hsincode', $item['product_code'])->first();

            if (!$product) {
                return response()->json(['error' => "Product not found for product code: {$item['product_code']}"], 404);
            }

            // ✅ Increase stock since this is a purchase order
            $product->increment('stocks', $item['quantity']);

            // ✅ Insert line item into `Po_lineitems` table
            Po_lineitem::create(array_merge($item, ['purchaseorder_id' => $purchaseorder->id]));
        }

        return response()->json([
            'message' => 'Purchase order created successfully',
            'purchaseorder' => $purchaseorder->load('polineItems') // ✅ Ensure relationship exists
        ], 201);
    }
    public function update(Request $request, $id): JsonResponse
    {
        $purchaseOrder = PurchaseOrder::find($id);

        if (!$purchaseOrder) {
            return response()->json(['message' => 'Purchase order not found'], 404);
        }

        $purchaseOrder->update($request->only(['order_number', 'name']));

        if (!empty($request->poline_items)) {
            foreach ($request->poline_items as $item) {
                if (isset($item['id'])) {
                    $polineItem = Po_lineitem::find($item['id']);

                    if ($polineItem) {
                        $polineItem->update([
                            'product_name' => $item['product_name'],
                            'name' => $item['name'] ?? null,
                            'product_code' => $item['product_code'],
                            'quantity' => $item['quantity']
                        ]);
                    } else {
                        return response()->json(['error' => "PoLine item not found with ID: {$item['id']}"], 404);
                    }
                } else {
                    $product = Product::where('hsincode', $item['product_code'])->first();

                    if (!$product) {
                        return response()->json(['error' => "Product not found for product code: {$item['product_code']}"], 404);
                    }

                    $product->increment('stocks', $item['quantity']);

                    Po_lineitem::create([
                        'purchaseorder_id' => $purchaseOrder->id,
                        'product_name' => $item['product_name'],
                        'name' => $item['name'] ?? null,
                        'product_code' => $item['product_code'],
                        'quantity' => $item['quantity']
                    ]);
                }
            }
        }

        return response()->json([
            'message' => 'Purchase order updated successfully',
            'purchaseorder' => $purchaseOrder->load('polineItems')
        ], 200);
    }


    public function destroy($id): JsonResponse
    {
        // Find the purchase order with related line items
        $purchaseOrder = PurchaseOrder::with('polineItems')->find($id);

        if (!$purchaseOrder) {
            return response()->json(['message' => 'Purchase order not found'], 404);
        }

        DB::transaction(function () use ($purchaseOrder) {
            // Prepare stock restoration
            $stockUpdates = [];

            foreach ($purchaseOrder->polineItems as $item) {
                $stockUpdates[$item->product_code] =
                    ($stockUpdates[$item->product_code] ?? 0) + $item->quantity;
            }

            // Restore stock in bulk
            Product::whereIn('hsincode', array_keys($stockUpdates))
                ->get()
                ->each(function ($product) use ($stockUpdates) {
                    $product->decrement('stocks', $stockUpdates[$product->hsincode]);
                });

            // Delete line items
            Po_lineitem::where('purchaseorder_id', $purchaseOrder->id)->delete();

            // Delete purchase order
            $purchaseOrder->delete();
        });

        return response()->json(['message' => 'Purchase order deleted successfully'], 200);
    }
}
