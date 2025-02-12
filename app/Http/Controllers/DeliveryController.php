<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Delivery;
use Illuminate\Http\JsonResponse;

class DeliveryController extends Controller
{
    public function index(): JsonResponse
    {
        $deliveries = Delivery::all();
        return response()->json(['deliveries' => $deliveries], 200);
    }

    public function show($id): JsonResponse
    {
        $delivery = Delivery::find($id);

        if (!$delivery) {
            return response()->json(['message' => 'Delivery not found'], 404);
        }

        return response()->json(['delivery' => $delivery], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'status' => 'in:pending,in-transit,delivered',
            'delivery_date' => 'date|nullable',
        ]);

        $delivery = Delivery::create([
            'recipient_name' => $request->recipient_name,
            'address' => $request->address,
            'status' => $request->status ?? 'pending',
            'delivery_date' => $request->delivery_date,
        ]);

        return response()->json(['message' => 'Delivery created successfully', 'delivery' => $delivery], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $delivery = Delivery::find($id);

        if (!$delivery) {
            return response()->json(['message' => 'Delivery not found'], 404);
        }

        $request->validate([
            'recipient_name' => 'string|max:255',
            'address' => 'string|max:500',
            'status' => 'in:pending,in-transit,delivered',
            'delivery_date' => 'date|nullable',
        ]);

        $delivery->update([
            'recipient_name' => $request->recipient_name ?? $delivery->recipient_name,
            'address' => $request->address ?? $delivery->address,
            'status' => $request->status ?? $delivery->status,
            'delivery_date' => $request->delivery_date ?? $delivery->delivery_date,
        ]);

        return response()->json(['message' => 'Delivery updated successfully', 'delivery' => $delivery], 200);
    }

    public function destroy($id): JsonResponse
    {
        $delivery = Delivery::find($id);

        if (!$delivery) {
            return response()->json(['message' => 'Delivery not found'], 404);
        }

        $delivery->delete();

        return response()->json(['message' => 'Delivery deleted successfully'], 200);
    }
}
