<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Review;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function reportReview(Request $request, $id)
    {
        try {
            if (!auth()->check()) {
                return response()->json(['error' => 'You must be authenticated to report a review.'], 401);
            }

            $review = Review::findOrFail($id);

            if ($review->user_id === auth()->id()) {
                return response()->json(['error' => 'You can\'t report your own reviews.'], 400);
            }

            if (Report::where('user_id', auth()->id())->where('review_id', $review->id)->exists()) {
                return response()->json(['error' => 'You have already reported this review.'], 400);
            }

            $selectedReason = $request->input('reason');

            $request->validate([
                'reason' => 'required|string',
            ]);

            $review->reportingUsers()->attach(auth()->id(), [
                'report_date' => now(),
                'reason' => $selectedReason,
            ]);

            
            return response()->json(['success' => 'Review reported successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function deleteReport(Request $request){
        $this->authorize('deleteReport', Report::class);
        $report = Report::where('user_id', $request->input('user_id'))->where('review_id', $request->input('review_id'));
        $report->delete();
        return redirect()->route('admin_products')->with('success', 'Report deleted successfully!');
    }
}
