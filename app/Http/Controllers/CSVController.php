<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Po_lineitem;
use App\Models\PurchaseOrder;
use App\Models\Category;
use App\Models\Subcategory;

class CSVController extends Controller
{
    public function uploadCSV(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
            'module'   => 'required|string|in:products,po_lineitems,purchase_orders,categories,subcategories'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Read CSV file
        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();
        $data = array_map('str_getcsv', file($filePath));

        if (count($data) <= 1) {
            return response()->json(['error' => 'No data found in CSV file'], 400);
        }

        $header = $data[0]; // Extract header row
        unset($data[0]); // Remove header from data

        // Determine which module to process
        switch ($request->module) {
            case 'products':
                foreach ($data as $row) {
                    Product::create([
                        'name' => $row[0],
                        'description' => $row[1],
                        'price' => $row[2],
                        'stocks' => $row[3],
                        'unit' => $row[4],
                        'brand' => $row[5],
                        'design' => $row[6],
                        'hsincode' => $row[7],
                        'varient' => $row[8],
                        'category' => $row[9],
                        'sub_category' => $row[10],
                        'status' => $row[11],
                        'test1' => $row[12],
                        'test2' => $row[13],
                    ]);
                }
                break;

            case 'po_lineitems':
                foreach ($data as $row) {
                    Po_lineitem::create([
                        'product_name' => $row[0],
                        'product_code' => $row[1],
                        'quantity' => $row[2],
                        'purchaseorder_id' => $row[3], // Ensure this ID exists in purchase_orders
                    ]);
                }
                break;

            case 'purchase_orders':
                foreach ($data as $row) {
                    PurchaseOrder::create([
                        'order_number' => $row[0],
                        'name' => $row[1],
                    ]);
                }
                break;

            case 'categories':
                foreach ($data as $row) {
                    Category::create([
                        'description' => $row[0],
                        'category_name' => $row[1],
                        'status' => $row[2],
                        'image' => $row[3],
                        'parent_category' => $row[4] ?? null,
                        'created_by' => $row[5] ?? null,
                        'updated_by' => $row[6] ?? null,
                    ]);
                }
                break;

            case 'subcategories':
                foreach ($data as $row) {
                    Subcategory::create([
                        'category_id' => $row[0],
                        'subcategory_name' => $row[1],
                        'description' => $row[2],
                        'parent_subcategory' => $row[3] ?? null,
                        'status' => $row[4],
                        'image' => $row[5],
                        'created_by' => $row[6] ?? null,
                        'updated_by' => $row[7] ?? null,
                    ]);
                }
                break;

            default:
                return response()->json(['error' => 'Invalid module'], 400);
        }

        return response()->json(['message' => 'CSV uploaded and data inserted successfully!'], 200);
    }
}
