<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Database\QueryException;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Events\NotificationsEvent;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class ReviewController extends Controller
{
    public function submitReview(Request $request, $id)
    {
        try {
            $user = auth()->user();
            if ($user->isBlocked()) {
                return redirect()->back()->with('error', 'You are blocked and cannot submit a review.');
            }

            $request->validate([
                'title' => 'required',
                'rating' => 'required|numeric|between:1,5',
                'review' => 'required',
            ]);
    
            $review = new Review();
            $review->review_date = now();
            $review->rating = $request->input('rating');
            $review->title = $request->input('title');
            $review->upvote_count = 0;
            $review->review_text = $request->input('review');
            $review->user_id = auth()->user()->id;
            $review->product_id = $id;
            $review->save();
    
            return redirect()->back()->with('success', 'Review submitted successfully');
        }
        catch (QueryException $e) {
            if ($e->getCode() === 'P0001') { // já tem uma review
                return redirect()->back()->with('error', 'You have already reviewed this product.');
            }

            return redirect()->back()->with('error', 'Failed to submit review. Please try again.');
        }
        
    }
    public function upvoteReview($id)
    {
        try {
            $review = Review::findOrFail($id);

            if (!Auth::check()) {
                return response()->json(['error' => 'You cant upvote a review as a non-authenticated user.'], 400);
            }
            
            if ($review->user_id == Auth::user()->id) {
                return response()->json(['error' => 'You cant upvote your own review.'], 400);
            }
            
            $isUpvoted = Auth::user()->upvotedReviews()->where('review_id', $id)->exists();
            
            if(!$isUpvoted){
                broadcast(new NotificationsEvent($review->user_id));
            }
            
            Auth::user()->upvotedReviews()->toggle($id);
            
            $upvoteCount = $review->upvoters()->count();
            
            
            return response()->json([
                'success' => 'Review upvoted successfully.',
                'review_id' => $id, 'upvoteCount' => $upvoteCount]);
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function deleteReview($id)
    {
        try {
            $review = Review::findOrFail($id);

            $this->authorize('delete', [$review, Review::class]);

            $review->delete();

            return response()->json(['success' => 'Review deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function updateReview(Request $request, $id)
    {
        try {
            $review = Review::findOrFail($id);

            $request->validate([
                'title' => 'required',
                'rating' => 'required|numeric|between:1,5',
                'review_text' => 'required',
            ]);

            if ($review->user_id !== Auth::id()) {
                return response()->json(['error' => 'You are not authorized to update this review.'], 403);
            }

            $review->title = $request->input('title');
            $review->rating = $request->input('rating');
            $review->review_text = $request->input('review_text');
            $review->save();

            return response()->json(['success' => 'Review updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}