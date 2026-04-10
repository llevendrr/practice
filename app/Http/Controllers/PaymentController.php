<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\CardPaymentRequest;

class PaymentController extends Controller
{
    public function select(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        return view('payments.select', compact('order'));
    }

    public function cardForm(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        return view('payments.form', compact('order'));
    }

    public function processCard(CardPaymentRequest $request, Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if ((float) $order->total <= 0) {
            $order->update([
                'payment_status' => Order::PAYMENT_FAILED,
                'payment_method' => 'card',
                'payment_reference' => null,
                'paid_at' => null,
            ]);

            return redirect()->route('payment.fail', $order);
        }

        $lastDigit = (int) substr(preg_replace('/\D+/', '', $request->card_number) ?: '0', -1);

        if ($lastDigit % 2 === 0) {
            $order->update([
                'payment_status' => Order::PAYMENT_PAID,
                'payment_method' => 'card',
                'payment_reference' => strtoupper('CARD-' . bin2hex(random_bytes(4))),
                'paid_at' => now(),
                'status' => Order::STATUS_PROCESSING,
            ]);

            return redirect()->route('payment.success', $order)->with('status', __('messages.payment.success'));
        }

        $order->update([
            'payment_status' => Order::PAYMENT_FAILED,
            'payment_method' => 'card',
            'payment_reference' => strtoupper('CARD-' . bin2hex(random_bytes(4))),
            'paid_at' => null,
        ]);

        return redirect()->route('payment.fail', $order);
    }

    public function selectCod(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->update([
            'payment_status' => Order::PAYMENT_PENDING,
            'payment_method' => 'cod',
            'payment_reference' => null,
            'paid_at' => null,
            'status' => Order::STATUS_PROCESSING,
        ]);

        return redirect()->route('orders')->with('status', __('messages.payment.cod_selected'));
    }

    public function success(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if ($order->payment_status !== Order::PAYMENT_PAID) {
            return redirect()->route('payment.select', $order)->with('error', __('messages.payment.status_not_updated'));
        }

        return view('payments.success', compact('order'));
    }

    public function fail(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        return view('payments.fail', compact('order'));
    }
}
