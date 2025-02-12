<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    // Get all stocks
    public function index()
    {
        return response()->json(Stock::all(), 200);
    }

    // Store new stock
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_code' => 'required|string|unique:stocks,product_code|max:255',
            'stock' => 'required|integer',
        ]);

        $stock = Stock::create($request->all());

        return response()->json($stock, 201);
    }

    // Show single stock
    public function show($id)
    {
        $stock = Stock::find($id);
        if (!$stock) {
            return response()->json(['message' => 'Stock not found'], 404);
        }
        return response()->json($stock, 200);
    }

    // Update stock
    public function update(Request $request, $id)
    {
        $stock = Stock::find($id);
        if (!$stock) {
            return response()->json(['message' => 'Stock not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'product_code' => 'sometimes|required|string|unique:stocks,product_code,' . $id . '|max:255',
            'stock' => 'sometimes|required|integer',
        ]);

        $stock->update($request->all());

        return response()->json($stock, 200);
    }

    // Delete stock
    public function destroy($id)
    {
        $stock = Stock::find($id);
        if (!$stock) {
            return response()->json(['message' => 'Stock not found'], 404);
        }

        $stock->delete();

        return response()->json(['message' => 'Stock deleted successfully'], 200);
    }
}
