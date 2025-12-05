<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $transaction = Transaction::findOrFail($request->transaction_id);

        // Ensure transaction belongs to user
        if ($transaction->buyer_id !== $user->buyer->id) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure transaction is completed
        if ($transaction->order_status !== 'completed' || $transaction->payment_status !== 'paid') {
            return back()->with('error', 'You can only review products from completed orders.');
        }

        // Check if review already exists
        $existingReview = ProductReview::where('transaction_id', $transaction->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this product for this order.');
        }

        ProductReview::create([
            'transaction_id' => $request->transaction_id,
            'buyer_id' => $user->buyer->id,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return back()->with('success', 'Review submitted successfully.');
    }
}
