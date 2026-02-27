<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['product', 'user']);

        if ($productTerm = $request->input('product')) {
            $query->whereHas('product', fn ($sub) => $sub->where('name', 'like', "%{$productTerm}%"));
        }

        if ($userTerm = $request->input('user')) {
            $query->whereHas('user', fn ($sub) => $sub
                ->where('name', 'like', "%{$userTerm}%")
                ->orWhere('email', 'like', "%{$userTerm}%"));
        }

        if ($rating = $request->input('rating')) {
            $query->where('rating', (int) $rating);
        }

        $direction = $request->input('sort') === 'oldest' ? 'asc' : 'desc';
        $query->orderBy('created_at', $direction);

        $reviews = $query->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggleVisibility(Review $review)
    {
        $review->update([
            'approved' => ! $review->approved,
        ]);

        return back()->with('status', 'Статус відгуку оновлено.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('status', 'Відгук видалено.');
    }
}
