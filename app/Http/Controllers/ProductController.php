<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Protect all routes
    }

    // Get all products
    public function index()
    {
        return response()->json(Product::all(), 200);
    }

    // Create a new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'hsincode' => 'required|string',
            'category' => 'required|nullable|string',
            'sub_category' => 'required|nullable|string',
            'description' => 'nullable|string',
            'brand' => 'nullable|string',
            'design' => 'nullable|string',
            'price' => 'numeric',
            'stocks' => 'required|integer',
            'unit' => 'string',
            'varient' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $product = Product::create($request->all());

        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }

    // Get a single product
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json([$product], 200);
    }

    // Update a product
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'hsincode' => 'string',

            'stocks' => 'integer',
            'unit' => 'nullable|string',
            'brand' => 'nullable|string',
            'design' => 'nullable|string',
            'varient' => 'nullable|string',
            'category' => 'string',
            'sub_category' => 'string',
            'status' => 'nullable|in:0,1',
        ]);

        $product->update($request->all());
        return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
    }

    // Delete a product
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
