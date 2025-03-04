<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class SubCategoryController extends Controller
{

    public function index()
    {
        return response()->json(SubCategory::all(), 200);
    }



    public function show($id): JsonResponse
    {
        $subCategory = SubCategory::with('category')->find($id);

        if (!$subCategory) {
            return response()->json(['message' => 'SubCategory not found'], 404);
        }

        return response()->json([$subCategory], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'subcategory_name' => 'required|string|max:255',
        ]);

        $subCategory = SubCategory::create([
            'subcategory_name' => $request->subcategory_name,
            'category_id' => $request->category_id,
            'status' => $request -> status,
            'description' => $request -> description,
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
            'subcategory_name' => 'string|max:255',
        ]);

        $subCategory->update([
            'subcategory_name' => $request->subcategory_name ?? $subCategory->subcategory_name,
             'category_id' => $request->category_id  ?? $subCategory->category_id,
            'status' => $request -> status ?? $subCategory->status,
            'description' => $request -> description ?? $subCategory->description,
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
