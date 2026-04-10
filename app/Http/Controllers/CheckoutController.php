<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(private CartService $cartService)
    {
    }

    public const SHIPPING_RATES = [
        'Нова Пошта' => 150,
        'Укрпошта' => 110,
        'Самовивіз' => 0,
    ];

    public function index()
    {
        $items = $this->cartService->items();

        if (! $items->count()) {
            return redirect()->route('cart')->with('error', __('messages.checkout.empty_cart'));
        }

        return view('checkout', [
            'items' => $items,
            'total' => $this->cartService->total(),
            'shippingMethods' => array_keys(self::SHIPPING_RATES),
        ]);
    }

    public function store(CheckoutRequest $request)
    {
        $items = $this->cartService->items();

        if (! $items->count()) {
            return redirect()->route('cart')->with('error', __('messages.checkout.empty_cart'));
        }

        $shippingCost = self::SHIPPING_RATES[$request->shipping_method] ?? 0;
        $subtotal = $this->cartService->total();

        $order = Order::create([
            'order_number' => 'TD' . strtoupper(Str::random(8)),
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'shipping_method' => $request->shipping_method,
            'shipping_city' => $request->city,
            'shipping_street' => $request->street,
            'shipping_house' => $request->house,
            'shipping_apartment' => $request->apartment,
            'postal_code' => $request->postal_code,
            'shipping_cost' => $shippingCost,
            'total' => $subtotal + $shippingCost,
            'notes' => $request->notes,
        ]);

        foreach ($items as $item) {
            $product = $item['product'];

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'price' => $product->price,
                'discount' => $product->discount,
                'quantity' => $item['quantity'],
            ]);

            $product->decrement('stock', $item['quantity']);
        }

        $this->cartService->clear();

        session()->put('last_order_id', $order->id);

        return redirect()->route('payment.select', $order);
    }
}
