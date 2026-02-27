<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cartService)
    {
    }

    public function index()
    {
        return view('cart', [
            'items' => $this->cartService->items(),
            'total' => $this->cartService->total(),
        ]);
    }

    public function store(Request $request, Product $product)
    {
        if ($product->stock < 1) {
            return back()->with('error', 'Товар тимчасово відсутній.');
        }

        $quantity = max(1, (int) $request->input('quantity', 1));

        $this->cartService->add($product, $quantity);

        return back()->with('status', 'Товар додано до кошика.');
    }

    public function update(Request $request, Product $product)
    {
        $quantity = max(1, (int) $request->input('quantity', 1));

        $this->cartService->update($product->id, $quantity);

        return back()->with('status', 'Кількість оновлено.');
    }

    public function remove(Product $product)
    {
        $this->cartService->remove($product->id);

        return back()->with('status', 'Товар видалено з кошика.');
    }
}
