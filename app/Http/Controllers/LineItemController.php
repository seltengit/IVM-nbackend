<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LineItem;
use Illuminate\Http\JsonResponse;

class LineItemController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(LineItem::all(), 200);
    }

    public function show($id): JsonResponse
    {
        $lineItem = LineItem::find($id);

        if (!$lineItem) {
            return response()->json(['message' => 'Line item not found'], 404);
        }

        return response()->json($lineItem, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_code' => 'required|string|unique:line_items|max:50',
            'product_name' => 'required|string|max:255',
            'stocks' => 'required|integer|min:0',
            'available' => 'required|integer|min:0',
            'pending' => 'required|integer|min:0',
        ]);

        $lineItem = LineItem::create([
            'product_code' => $request->product_code,
            'product_name' => $request->product_name,
            'stocks' => $request->stocks,
            'available' => $request->available,
            'pending' => $request->pending,
        ]);

        return response()->json(['message' => 'Line item created successfully', 'line_item' => $lineItem], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $lineItem = LineItem::find($id);

        if (!$lineItem) {
            return response()->json(['message' => 'Line item not found'], 404);
        }

        $request->validate([
            'product_code' => 'string|max:50|unique:line_items,product_code,' . $id,
            'product_name' => 'string|max:255',
            'stocks' => 'integer|min:0',
            'available' => 'integer|min:0',
            'pending' => 'integer|min:0',
        ]);

        $lineItem->update([
            'product_code' => $request->product_code ?? $lineItem->product_code,
            'product_name' => $request->product_name ?? $lineItem->product_name,
            'stocks' => $request->stocks ?? $lineItem->stocks,
            'available' => $request->available ?? $lineItem->available,
            'pending' => $request->pending ?? $lineItem->pending,
        ]);

        return response()->json(['message' => 'Line item updated successfully', 'line_item' => $lineItem], 200);
    }

    public function destroy($id): JsonResponse
    {
        $lineItem = LineItem::find($id);

        if (!$lineItem) {
            return response()->json(['message' => 'Line item not found'], 404);
        }

        $lineItem->delete();

        return response()->json(['message' => 'Line item deleted successfully'], 200);
    }
}
