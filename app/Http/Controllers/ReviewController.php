<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewHelpful;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string|max:500',
        ]);

        $review = Review::updateOrCreate(
            [
                'product_id' => $request->product_id,
                'user_id' => Auth::id(),
            ],
            [
                'rating' => $request->rating,
                'message' => $request->message,
            ]
        );

        return response()->json([
            'success' => true,
            'updated' => !$review->wasRecentlyCreated,
            'review' => $review->load('user'),
        ]);
    }

    public function helpful($id)
    {
        $review = Review::findOrFail($id);

        $exists = ReviewHelpful::where('review_id', $review->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($exists) {
            ReviewHelpful::where('review_id', $review->id)
                ->where('user_id', Auth::id())
                ->delete();

            if ($review->helpful_count > 0) {
                $review->decrement('helpful_count');
            }

            return response()->json([
                'success' => true,
                'active' => false,
                'helpful_count' => $review->helpful_count
            ]);
        }

        ReviewHelpful::create([
            'review_id' => $review->id,
            'user_id' => Auth::id(),
        ]);

        $review->increment('helpful_count');

        return response()->json([
            'success' => true,
            'active' => true,
            'helpful_count' => $review->helpful_count
        ]);
    }
}
