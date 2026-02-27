<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    private const SESSION_KEY = 'cart.items';

    public function items(): Collection
    {
        $items = session()->get(self::SESSION_KEY, []);

        if (! count($items)) {
            return collect();
        }

        $products = Product::whereIn('id', array_keys($items))->get()->keyBy('id');

        return collect($items)
            ->map(function (int $quantity, string $id) use ($products) {
                $product = $products[$id] ?? null;

                if (! $product) {
                    return null;
                }

                $effectiveQty = min($product->stock, max($quantity, 1));

                return [
                    'product' => $product,
                    'quantity' => $effectiveQty,
                    'subtotal' => $product->discounted_price * $effectiveQty,
                ];
            })
            ->filter()
            ->values();
    }

    public function count(): int
    {
        return $this->items()->sum('quantity');
    }

    public function total(): float
    {
        return $this->items()->sum('subtotal');
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $items = session()->get(self::SESSION_KEY, []);

        $current = $items[$product->id] ?? 0;
        $items[$product->id] = min($product->stock, max(1, $current + $quantity));

        session()->put(self::SESSION_KEY, $items);
    }

    public function update(int $productId, int $quantity): void
    {
        $quantity = max(1, $quantity);
        $product = Product::find($productId);

        if (! $product) {
            return;
        }

        $items = session()->get(self::SESSION_KEY, []);

        if ($quantity > $product->stock) {
            $quantity = $product->stock;
        }

        $items[$productId] = $quantity;

        session()->put(self::SESSION_KEY, $items);
    }

    public function remove(int $productId): void
    {
        $items = session()->get(self::SESSION_KEY, []);

        unset($items[$productId]);

        session()->put(self::SESSION_KEY, $items);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }
}
