<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Product;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request, Product $product)
    {
        $product->reviews()->create([
            'user_id' => $request->user()->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'approved' => true,
        ]);

        return back()->with('status', 'Дякуємо за відгук! Ми опрацюємо його найближчим часом.');
    }
}
