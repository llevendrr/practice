<?php

namespace App\Http\Controllers;

use App\Http\Requests\CardPaymentRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function select(Order $order): RedirectResponse|Response
    {
        $this->ensureOrderOwner($order);

        if ($this->alreadyPaid($order)) {
            return redirect()->route('payment.success', $order);
        }

        return $this->htmlResponse('payments.select', compact('order'));
    }

    public function cardForm(Order $order): RedirectResponse|Response
    {
        $this->ensureOrderOwner($order);

        if ($this->alreadyPaid($order)) {
            return redirect()->route('payment.success', $order);
        }

        return $this->htmlResponse('payments.form', compact('order'));
    }

    public function processCard(CardPaymentRequest $request, Order $order): RedirectResponse
    {
        $this->ensureOrderOwner($order);

        if ($this->alreadyPaid($order)) {
            return redirect()->route('payment.success', $order);
        }

        try {
            $reference = sprintf('SIM-%s', Str::upper(Str::random(12)));

            $order->update([
                'payment_status' => Order::PAYMENT_PAID,
                'status' => Order::STATUS_PAID,
                'payment_method' => Order::PAYMENT_METHOD_CARD,
                'payment_reference' => $reference,
                'paid_at' => now(),
            ]);

            Log::info('Card payment simulation succeeded', [
                'order_id' => $order->id,
                'payment_reference' => $reference,
                'user_id' => $order->user_id,
            ]);

            return redirect()->route('payment.success', $order)->with('status', 'Оплату успішно виконано.');
        } catch (\Throwable $exception) {
            Log::error('Card payment failed', [
                'order_id' => $order->id,
                'error' => $exception->getMessage(),
                'user_id' => $order->user_id,
            ]);

            return redirect()
                ->route('payment.fail', $order)
                ->with('payment_error', 'Сервер не зміг обробити платіж. Спробуйте ще раз.');
        }
    }

    public function selectCod(Order $order): RedirectResponse
    {
        $this->ensureOrderOwner($order);

        if ($this->alreadyPaid($order)) {
            return redirect()->route('payment.success', $order);
        }

        $order->update([
            'status' => Order::STATUS_PROCESSING,
            'payment_status' => Order::PAYMENT_PENDING,
        ]);

        return redirect()->route('orders')->with('status', 'Ваше замовлення буде оброблене післяплатою.');
    }

    public function success(Order $order): RedirectResponse|Response
    {
        $this->ensureOrderOwner($order);

        if (! $this->alreadyPaid($order)) {
            return redirect()->route('payment.select', $order)->with('error', 'Статус замовлення ще не оновлено.');
        }

        return $this->htmlResponse('payments.success', compact('order'));
    }

    public function fail(Order $order): RedirectResponse|Response
    {
        $this->ensureOrderOwner($order);

        if ($this->alreadyPaid($order)) {
            return redirect()->route('payment.success', $order);
        }

        $paymentError = session('payment_error') ?? 'Платіж відхилено. Спробуйте ще раз.';

        return $this->htmlResponse('payments.fail', compact('order', 'paymentError'));
    }

    private function ensureOrderOwner(Order $order): void
    {
        abort_unless($order->user_id === auth()->id(), 403);
    }

    private function alreadyPaid(Order $order): bool
    {
        return $order->payment_status === Order::PAYMENT_PAID || $order->status === Order::STATUS_PAID;
    }

    private function htmlResponse(string $view, array $data = []): Response
    {
        return response()
            ->view($view, $data)
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }
}
