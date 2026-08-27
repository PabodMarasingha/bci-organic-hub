<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductItem; 
use App\Models\Review; 
use Illuminate\Support\Facades\Auth; 

class ProductReviewController extends Controller
{
    public function store(Request $request, ProductItem $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback_text' => 'nullable|string|max:1000',
        ]);

        // Use food_rating instead of rating
        $product->reviews()->updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'food_rating' => $validated['rating'], 
                'comment' => $validated['feedback_text'],
            ]
        );

        // Calculate average using food_rating
        $averageRating = $product->reviews()->avg('food_rating'); 
        $reviewCount = $product->reviews()->count();

        $product->update([
            'average_rating' => $averageRating,
            'reviews_count' => $reviewCount,
        ]);

        return back()->with('success', 'Thank you for your valuable feedback!');
    }
}