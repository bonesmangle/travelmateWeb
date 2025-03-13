<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Report;
use App\Models\Review;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // Debug the reason filter
    \Log::info('Reason filter value:', ['reason' => $request->reason]);

    $adminLocality = auth()->user()->locality;

    // Start the query for reports in the admin's locality
    $query = Report::whereHas('review.destination', function($q) use ($adminLocality) {
        $q->where('locality', $adminLocality);
    });

    // If filtering by reason (case-insensitive)
    if ($request->filled('reason')) {
        $query->where('reason', 'like', '%' . $request->reason . '%');
    }

    // Sort by created_at in descending order (latest first)
    $query->orderBy('created_at', 'desc');

    // Paginate the results
    $reports = $query
        ->with(['review', 'review.destination'])
        ->paginate(10);

    // Format created_at for each report
    foreach ($reports as $report) {
        if ($report->created_at) {
            $report->formatted_created_at = Carbon::parse($report->created_at)
                ->setTimezone('Asia/Manila')
                ->format('F j, Y'); // Format as "Month, day, year" (e.g., October 11, 2024)
        }
    }

    return view('admin/reports', [
        'title'   => 'Reports',
        'reports' => $reports,
        'reason'  => $request->reason ?? '',
    ]);
}
    public function approve(string $id)
    {
        $report = Report::findOrFail($id);
        $review = Review::findOrFail($report->review_id);

        // 1) Delete the associated review from DB
        $review->delete();

        // 2) Mark the report as approved
        $report->status = 'approved';
        $report->save();

        return redirect()->back()->with('success', 'Review deleted and report approved successfully');
    }

    public function decline(string $id)
    {
        $report = Report::findOrFail($id);
        $review = Review::findOrFail($report->review_id);

        // Mark the review as "declined" (assuming there's a 'status' on review too)
        $review->status = 'declined';
        $review->save();

        // Mark the report as declined
        $report->status = 'declined';
        $report->save();

        return redirect()->back()->with('success', 'Report declined successfully');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log the incoming request for debugging
        \Log::info('Report request received:', $request->all());
    
        // Basic validation for required fields
        $validatedData = $request->validate([
            'review_id'      => 'required|exists:reviews,_id',
            'destination_id' => 'required|exists:destinations,_id',
            'reason'         => 'required|string',
        ]);
    
        $reason = $request->input('reason');
        \Log::info('Reason submitted:', ['reason' => $reason]);
    
        // Check if the reason follows the "reason: explanation" format
        if (!str_contains($reason, ':')) {
            \Log::warning('Invalid reason format (no colon found)');
            return response()->json([
                'success' => false,
                'message' => 'Invalid report format. Please select a reason and provide an explanation.',
            ], 422);
        }
    
        // Split into reason and explanation
        list($reasonType, $explanation) = explode(':', $reason, 2);
        
        // Validate that both parts exist and explanation is substantial
        if (trim($reasonType) === '') {
            \Log::warning('Empty reason type');
            return response()->json([
                'success' => false,
                'message' => 'Please select a valid reason for your report.',
            ], 422);
        }
    
        if (strlen(trim($explanation)) < 5) {
            \Log::warning('Explanation too short');
            return response()->json([
                'success' => false,
                'message' => 'Please provide a more detailed explanation (at least 5 characters).',
            ], 422);
        }
    
        // Create the new report
        try {
            $report = Report::create([
                'review_id'      => $request->input('review_id'),
                'destination_id' => $request->input('destination_id'),
                'reason'         => $reason, // Store the complete "reason: explanation" string
                'status'         => 'pending',
            ]);
    
            \Log::info('Report created successfully:', ['report_id' => $report->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Report submitted successfully!',
            ]);
        } catch (\Exception $e) {
            \Log::error('Report creation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit report. Please try again.',
            ], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
