<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;



class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all(), 200);
    }


    public function show($id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json([$category], 200);
    }


    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'category_name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'image' => 'string|nullable',
            'parent_category' => 'nullable|integer|exists:categories,id',
            'created_by' => 'nullable|integer',
            'updated_by' => 'nullable|integer',
        ]);

        $category = Category::create($validated);

        return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);
    }
    public function update(Request $request, $id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $request->validate([
            'description' => 'string|max:255|nullable',
            'category_name' => 'string|max:255|nullable',
            'status' => 'in:active,inactive|nullable',
            'image' => 'string|nullable',
            'parent_category' => 'nullable|integer|exists:categories,id',
            'updated_by' => 'nullable|integer',
        ]);

        $category->update([
            'description' => $request->description ?? $category->description,
            'category_name' => $request->category_name ?? $category->category_name,
            'status' => $request->status ?? $category->status,
            'image' => $request->image ?? $category->image,
            'parent_category' => $request->parent_category ?? $category->parent_category,
            'updated_by' => $request->updated_by ?? $category->updated_by,
        ]);

        return response()->json(['message' => 'Category updated successfully', 'category' => $category], 200);
    }

    public function destroy($id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
