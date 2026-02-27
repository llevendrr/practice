<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = auth()->user()
            ->orders()
            ->with('items.product')
            ->orderByDesc('created_at')
            ->paginate(6);

        return view('account.orders', compact('orders'));
    }

    public function cancel(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if (! $order->cancelable()) {
            return back()->with('error', 'Статус замовлення не дозволяє скасування.');
        }

        $order->update(['status' => Order::STATUS_CANCELED]);

        return back()->with('status', 'Замовлення скасовано.');
    }
}
