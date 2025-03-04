<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Po_lineitem;

class PoLineItemController extends Controller
{
    // Get all line items
    public function index()
    {
        return response()->json(Po_lineitem::all());
    }

    // Store a new line item
    public function store(Request $request)
    {
        $request->validate([
            'purchaseorder_id' => 'required|exists:purchase_orders,id',
            'product_name' => 'required|string',
            'name' => 'required|string',
        ]);

        $lineItem = Po_lineitem::create($request->all());

        return response()->json(['message' => 'Line item added', 'line_item' => $lineItem], 201);
    }

    // Get a single line item
    public function show($id)
    {
        $lineItem = Po_lineitem::findOrFail($id);
        return response()->json($lineItem);
    }

    // Update a line item
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'string',
            'name' => 'string',
        ]);

        $lineItem = Po_lineitem::findOrFail($id);
        $lineItem->update($request->all());

        return response()->json(['message' => 'Line item updated', 'line_item' => $lineItem]);
    }

    // Delete a line item
    public function destroy($id)
    {
        Po_lineitem::findOrFail($id)->delete();
        return response()->json(['message' => 'Line item deleted']);
    }
}
