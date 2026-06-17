<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Transaction;
use App\Models\TransactionCategory;

class OrderObserver
{
    /**
     * When an order's payment becomes "paid", record a matching cash-in
     * (income) transaction in the accounting ledger — once per order.
     */
    public function saved(Order $order): void
    {
        if ($order->payment_status !== 'paid') {
            return;
        }

        // Only act when the payment just flipped to paid (or the order was
        // created already paid) — not on every unrelated save.
        if (! $order->wasChanged('payment_status') && ! $order->wasRecentlyCreated) {
            return;
        }

        $this->recordCashIn($order);
    }

    protected function recordCashIn(Order $order): void
    {
        // Idempotency: never create a second auto cash-in for the same order.
        $alreadyRecorded = Transaction::where('type', 'income')
            ->where('invoice', $order->order_number)
            ->exists();

        if ($alreadyRecorded) {
            return;
        }

        Transaction::create([
            'type'             => 'income',
            'amount'           => $order->total_amount,
            'category_id'      => $this->resolveCategory($order->channel)->id,
            'wallet'           => $this->resolveWallet($order),
            'invoice'          => $order->order_number,
            'transaction_id'   => $order->payment_trx_id ?: null,
            'transaction_date' => now(),
            'notes'            => "Auto-recorded from order #{$order->id} ({$order->order_number}) — payment marked paid.",
        ]);
    }

    /**
     * Map the sales channel to its income category (created on demand).
     */
    protected function resolveCategory(?string $channel): TransactionCategory
    {
        $name = match ($channel) {
            'facebook' => 'Facebook Order',
            'whatsapp' => 'Whatsapp Order',
            'daraz'    => 'Daraz Order',
            default    => 'Website Order',
        };

        return TransactionCategory::firstOrCreate(['name' => $name]);
    }

    /**
     * Map the order's payment method to a wallet bucket.
     */
    protected function resolveWallet(Order $order): string
    {
        $code = strtolower((string) ($order->paymentMethod->code ?? ''));

        return match (true) {
            str_contains($code, 'bkash') => 'bkash',
            str_contains($code, 'nagad') => 'nagad',
            default                      => 'cash',
        };
    }
}
