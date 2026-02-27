<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Order;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function select(Order $order)
    {
        $this->ensureOrderOwner($order);

        if ($this->alreadyPaid($order)) {
            return redirect()->route('payment.success');
        }

        return view('payments.select', compact('order'));
    }

    public function show(Order $order)
    {
        $this->ensureOrderOwner($order);

        if ($this->alreadyPaid($order)) {
            return redirect()->route('payment.success');
        }

        return view('payments.form', compact('order'));
    }

    public function process(PaymentRequest $request, Order $order)
    {
        $this->ensureOrderOwner($order);

        if ($this->alreadyPaid($order)) {
            return redirect()->route('payment.success');
        }

        $order->update([
            'payment_status' => Order::PAYMENT_PAID,
            'status' => Order::STATUS_PAID,
        ]);

        session(['payment_success_order_id' => $order->id]);

        return redirect()->route('payment.success')->with('status', 'Оплата успішно підтверджена.');
    }

    public function selectCod(Order $order)
    {
        $this->ensureOrderOwner($order);

        if ($this->alreadyPaid($order)) {
            return redirect()->route('payment.success');
        }

        $order->update([
            'status' => Order::STATUS_PROCESSING,
            'payment_status' => Order::PAYMENT_PENDING,
        ]);

        return redirect()->route('orders')->with('status', 'Ваше замовлення буде оброблене післяплатою.');
    }

    public function success()
    {
        $orderId = session('payment_success_order_id');

        $order = Order::where('id', $orderId)
            ->where('user_id', auth()->id())
            ->where('status', Order::STATUS_PAID)
            ->first();

        if (! $order) {
            return redirect()->route('orders')->with('error', 'Замовлення не знайдено або вже оплачено.');
        }

        return view('payments.success', compact('order'));
    }

    private function ensureOrderOwner(Order $order): void
    {
        abort_unless($order->user_id === auth()->id(), 403);
    }

    private function alreadyPaid(Order $order): bool
    {
        return $order->payment_status === Order::PAYMENT_PAID || $order->status === Order::STATUS_PAID;
    }
}
