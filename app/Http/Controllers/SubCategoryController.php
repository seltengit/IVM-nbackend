<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class SubCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $subCategories = SubCategory::with('category')->get();
        return response()->json(['sub_categories' => $subCategories], 200);
    }

    public function show($id): JsonResponse
    {
        $subCategory = SubCategory::with('category')->find($id);

        if (!$subCategory) {
            return response()->json(['message' => 'SubCategory not found'], 404);
        }

        return response()->json(['sub_category' => $subCategory], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
        ]);

        $subCategory = SubCategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'SubCategory created successfully', 'sub_category' => $subCategory], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $subCategory = SubCategory::find($id);

        if (!$subCategory) {
            return response()->json(['message' => 'SubCategory not found'], 404);
        }

        $request->validate([
            'category_id' => 'exists:categories,id',
            'name' => 'string|max:255',
        ]);

        $subCategory->update([
            'category_id' => $request->category_id ?? $subCategory->category_id,
            'name' => $request->name ?? $subCategory->name,
        ]);

        return response()->json(['message' => 'SubCategory updated successfully', 'sub_category' => $subCategory], 200);
    }

    public function destroy($id): JsonResponse
    {
        $subCategory = SubCategory::find($id);

        if (!$subCategory) {
            return response()->json(['message' => 'SubCategory not found'], 404);
        }

        $subCategory->delete();

        return response()->json(['message' => 'SubCategory deleted successfully'], 200);
    }
}
