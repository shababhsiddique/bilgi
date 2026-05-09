<?php

namespace App\Livewire\Checkout;

use App\Models\PaymentMethod;
use Livewire\Component;

class Payment extends Component
{
    public ?int $selectedPaymentMethodId = null;

    public function mount(): void
    {
        // Optionally preselect first active method
        $first = PaymentMethod::query()->where('is_active', true)->orderBy('sort_order')->first();
        $this->selectedPaymentMethodId = $first?->id;
    }

    public function select(int $paymentMethodId): void
    {
        $this->selectedPaymentMethodId = $paymentMethodId;

        session(['checkout.payment_method_id' => $paymentMethodId]);

        // Optionally emit event so other components can react
        $this->dispatch('paymentMethodSelected', id: $paymentMethodId);
    }

    public function render()
    {
        $paymentMethods = PaymentMethod::query()
            ->where('is_active', true)          // adjust column names as needed
            ->orderBy('sort_order')             // or 'name' if you don't have sort_order
            ->get();

        return view('livewire.checkout.payment', [
            'paymentMethods' => $paymentMethods,
        ]);
    }
}
