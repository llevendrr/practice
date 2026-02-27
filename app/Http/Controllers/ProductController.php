<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        $product->load([
            'images',
            'reviews' => fn ($query) => $query->approved()->with('user')->orderByDesc('created_at'),
        ]);

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->orderByDesc('popularity')
            ->limit(4)
            ->get();

        $specFields = $product->category?->specFields->keyBy('key') ?? collect();

        return view('product', compact('product', 'related', 'specFields'));
    }
}
