<?php

namespace App\Livewire\Checkout;

use App\Models\PaymentMethod;
use Livewire\Component;

class Payment extends Component
{
    public ?int $selectedPaymentMethodId = null;

    /** Order total kept in sync with the Summary component (for bKash instructions). */
    public float $orderTotal = 0;

    /** Manual mobile-wallet transfer fields submitted with the checkout form. */
    public ?string $trxId = null;
    public ?string $senderNumber = null;

    protected $listeners = [
        // Summary recalculates and broadcasts the live total.
        'checkout-total-updated' => 'updateOrderTotal',
    ];

    public function mount(): void
    {
        // Optionally preselect first active method
        $first = PaymentMethod::query()->where('is_active', true)->orderBy('sort_order')->first();
        $this->selectedPaymentMethodId = $first?->id;

        // Restore any old input after a failed validation redirect.
        $this->trxId = old('payment_trx_id', $this->trxId);
        $this->senderNumber = old('payment_sender_number', $this->senderNumber);
    }

    public function select(int $paymentMethodId): void
    {
        $this->selectedPaymentMethodId = $paymentMethodId;

        session(['checkout.payment_method_id' => $paymentMethodId]);

        // Optionally emit event so other components can react
        $this->dispatch('paymentMethodSelected', id: $paymentMethodId);
    }

    public function updateOrderTotal(float $total): void
    {
        $this->orderTotal = $total;
    }

    public function render()
    {
        $paymentMethods = PaymentMethod::query()
            ->where('is_active', true)          // adjust column names as needed
            ->orderBy('sort_order')             // or 'name' if you don't have sort_order
            ->get();

        $selectedMethod = $paymentMethods->firstWhere('id', $this->selectedPaymentMethodId);
        $manualWalletCodes = config('payment.manual_wallets.codes', []);

        return view('livewire.checkout.payment', [
            'paymentMethods' => $paymentMethods,
            'isManualWalletSelected' => $selectedMethod
                && in_array($selectedMethod->code, $manualWalletCodes, true),
            'walletName' => $selectedMethod?->name,
            'walletNumber' => config('payment.manual_wallets.receive_number'),
            'walletAccountType' => config('payment.manual_wallets.account_type'),
        ]);
    }
}
