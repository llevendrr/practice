<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->orderByDesc('created_at')->paginate(12);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,processing,shipped,done,canceled'],
            'payment_status' => ['required', 'in:pending,paid'],
        ]);

        $order->update($data);

        return back()->with('status', __('messages.admin.order.statuses_updated'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('admin.orders.form', compact('users', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => ['required', 'regex:/^\d{9,15}$/'],
            'shipping_method' => 'required|in:Нова Пошта,Укрпошта,Самовивіз',
            'shipping_cost' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid',
            'status' => 'required|in:new,processing,shipped,done,canceled',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'nullable|integer|exists:products,id',
            'products.*.quantity' => 'nullable|integer|min:0',
        ]);

        $order = Order::create([
            'order_number' => 'TD' . strtoupper(Str::random(6)),
            'user_id' => $data['user_id'] ?? null,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'shipping_method' => $data['shipping_method'],
            'shipping_cost' => $data['shipping_cost'],
            'total' => 0,
            'status' => $data['status'],
            'payment_status' => $data['payment_status'],
            'notes' => $data['notes'] ?? null,
        ]);

        $subtotal = 0;

        foreach ($data['products'] as $item) {
            $product = Product::find($item['product_id']);

            if (! $product) {
                continue;
            }

            $quantity = min($product->stock, $item['quantity']);

            if ($quantity < 1) {
                continue;
            }

            $subtotal += ($product->price - $product->discount) * $quantity;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'price' => $product->price,
                'discount' => $product->discount,
                'quantity' => $quantity,
            ]);

            $product->decrement('stock', $quantity);
        }

        $order->update([
            'total' => $subtotal + $data['shipping_cost'],
        ]);

        return redirect()->route('admin.orders.index')->with('status', __('messages.orders.created'));
    }
}
