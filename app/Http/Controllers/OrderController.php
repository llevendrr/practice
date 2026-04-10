<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()
            ->orders()
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('account.orders', compact('orders'));
    }

    public function cancel(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if (! in_array($order->status, [Order::STATUS_NEW, Order::STATUS_PROCESSING], true)) {
            return back()->with('error', __('messages.orders.cancel_forbidden'));
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        return back()->with('status', __('messages.orders.cancelled'));
    }
}
