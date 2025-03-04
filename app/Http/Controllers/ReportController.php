<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function index()
    {
        return response()->json(Report::all(), 200);
    }


    public function show($id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json(['message' => 'Report not found'], 404);
        }

        return response()->json([$report], 200); // Wrap the product inside an array
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'string|nullable',
            'report_date' => 'required|date',
            'status' => 'in:pending,reviewed,approved',
        ]);

        $report = Report::create([
            'title' => $request->title,
            'description' => $request->description,
            'report_date' => $request->report_date,
            'status' => $request->status ?? 'pending',
        ]);

        return response()->json(['message' => 'Report created successfully', 'report' => $report], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json(['message' => 'Report not found'], 404);
        }

        $request->validate([
            'title' => 'string|max:255',
            'description' => 'string|nullable',
            'report_date' => 'date',
            'status' => 'in:pending,reviewed,approved',
        ]);

        $report->update([
            'title' => $request->title ?? $report->title,
            'description' => $request->description ?? $report->description,
            'report_date' => $request->report_date ?? $report->report_date,
            'status' => $request->status ?? $report->status,
        ]);

        return response()->json(['message' => 'Report updated successfully', 'report' => $report], 200);
    }

    public function destroy($id): JsonResponse
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json(['message' => 'Report not found'], 404);
        }

        $report->delete();

        return response()->json(['message' => 'Report deleted successfully'], 200);
    }
}
