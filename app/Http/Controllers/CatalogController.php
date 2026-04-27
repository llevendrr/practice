<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('primaryImage', 'category')
            ->withApprovedRatings()
            ->active();

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($sub) => $sub
                ->where('name', 'like', "%{$term}%")
                ->orWhere('brand', 'like', "%{$term}%")
                ->orWhere('model', 'like', "%{$term}%"));
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->input('brand'));
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', (float) $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float) $request->input('price_max'));
        }

        if ($request->filled('stock')) {
            $query->where('stock', '>', 0);
        }

        match ($request->input('sort')) {
            'popular' => $query->orderByDesc('popularity'),
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'newest' => $query->orderByDesc('created_at'),
            default => $query->inRandomOrder(),
        };

        $products = $query->paginate(12)->withQueryString();

        $filters = [
            'categories' => Category::active()->orderBy('order')->get(),
            'brands' => Product::active()->orderBy('brand')->distinct('brand')->pluck('brand'),
        ];

        return view('catalog', [
            'products' => $products,
            'filters' => $filters,
        ]);
    }
}
