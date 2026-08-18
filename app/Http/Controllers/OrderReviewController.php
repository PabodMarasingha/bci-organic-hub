<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderReviewController extends Controller
{
    /**
     * Display the order review form page.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CustomerOrder $order
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create(Request $request, CustomerOrder $order)
    {
        // පරිශීලකයා අදාළ Order එකේ හිමිකරුදැයි පරීක්ෂා කිරීම
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // මීට පෙර Review එකක් ලබාදී ඇත්නම් නැවත ලබාදීමට ඉඩ නොදීම
        if ($order->review) {
            return redirect()->route('orders.show', $order->id)
                ->with('message', 'You have already submitted a review for this order.');
        }

        return view('customers.orders.review', compact('order'));
    }

    /**
     * Store order and delivery review in database & update driver rating points.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CustomerOrder $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, CustomerOrder $order)
    {
        // පරිශීලකයා අදාළ Order එකේ හිමිකරුදැයි පරීක්ෂා කිරීම
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // මීට පෙර Review එකක් ලබාදී ඇත්නම් වැළැක්වීම
        if ($order->review) {
            return back()->withErrors(['review' => 'Review already exists for this order.']);
        }

        // UI Form එකෙන් rating ලෙස හෝ food_rating/delivery_rating ලෙස පැමිණිය හැකි බැවින් validate කිරීම
        $validated = $request->validate([
            'rating'          => 'nullable|integer|min:1|max:5',
            'food_rating'     => 'nullable|integer|min:1|max:5',
            'delivery_rating' => 'nullable|integer|min:1|max:5',
            'comment'         => 'nullable|string|max:500',
        ]);

        $finalFoodRating     = $validated['food_rating'] ?? $validated['rating'] ?? 5;
        $finalDeliveryRating = $validated['delivery_rating'] ?? $validated['rating'] ?? 5;

        DB::transaction(function () use ($validated, $order, $finalFoodRating, $finalDeliveryRating) {
            // 1. Review එක Save කිරීම (මෙහි table එකේ 'rating' කණුවක් තිබේ නම් දෝෂය මඟහරවා ගැනීමට එයටද අගයක් ලබා දී ඇත)
            Review::create([
                'customer_order_id' => $order->id,
                'user_id'           => Auth::id(),
                'rating'            => $finalFoodRating, // Database එකේ 'rating' column එක අත්‍යවශ්‍ය නම් සඳහා
                'food_rating'       => $finalFoodRating,
                'delivery_rating'   => $finalDeliveryRating,
                'comment'           => $validated['comment'] ?? null,
            ]);

            // 2. Delivery Driver සොයාගෙන Points Calculate කර Update කිරීම
            $delivery = $order->delivery;
            if ($delivery && $delivery->driver) {
                $driver = $delivery->driver;

                // Stars අනුව Points වෙනස් වන ආකාරය:
                // 5 Stars = +10 Points
                // 4 Stars = +5 Points
                // 3 Stars = 0 Points
                // 2 Stars = -5 Points
                // 1 Star  = -10 Points
                $pointChange = match ((int) $finalDeliveryRating) {
                    5 => 10,
                    4 => 5,
                    3 => 0,
                    2 => -5,
                    1 => -10,
                    default => 0,
                };

                // Points 0 ට වඩා අඩු නොවන ලෙස Update කිරීම
                $newPoints = max(0, ($driver->rating_points ?? 100) + $pointChange);
                $driver->update(['rating_points' => $newPoints]);
            }
        });

        return redirect()->route('orders.show', $order->id)
            ->with('message', 'Thank you for your feedback & rating!');
    }
}