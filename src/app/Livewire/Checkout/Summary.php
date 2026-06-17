<?php

namespace App\Livewire\Checkout;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Summary extends Component
{
    public array $items = [];

    public float $subtotal = 0;
    public float $deliveryFee = 100;
    public float $tax = 0;
    public float $discountAmount = 0;
    public float $total = 0;

    public string $discountCode = '';
    public ?string $discountError = null;

    public string $notes = '';


    protected $listeners = [
        // These can be dispatched from other checkout steps
        'cart-updated' => 'refreshSummary',
        'checkout-notes-updated' => 'updateNotes',
        'delivery-updated' => 'updateDeliveryFee',
        'recalculate-summary' => 'refreshSummary',
    ];

    public function mount(): void
    {
        $this->refreshSummary();
    }

    /**
     * Recalculate items + all price fields
     */
    public function refreshSummary(): void
    {
        $this->loadItemsFromCart();
        $this->calculateSubtotal();
        $this->calculateTax();
        $this->calculateTotal();
    }

    protected function loadItemsFromCart(): void
    {
        // Adjust this to how you store cart ownership (user / customer / session)
        $query = CartItem::query()
            ->with(['product', 'productVariant']);

        if (auth()->check()) {
            $query->where('customer_id', auth()->id());
        } else {
            $query->where('session_id', session('cart_session_id'));
        }

        $cartItems = $query->get();

        $this->items = $cartItems->map(function (CartItem $item) {
            $price = $item->unit_price;
            $total = $item->total_price;

            return [
                'id' => $item->id,
                'name' => $item->product?->name ?? 'Unnamed product',
                'variant' => $item->productVariant?->name ?? 'Default',
                'qty' => $item->quantity,
                'price' => $price,
                'total' => $total,
            ];
        })->toArray();
    }

    protected function calculateSubtotal(): void
    {
        $this->subtotal = collect($this->items)->sum('total');
    }

    protected function calculateTax(): void
    {
        // Replace with your real tax calculation
        $this->tax = 0;
    }

    protected function calculateTotal(): void
    {
        $this->total = max(
            0,
            $this->subtotal
            + $this->deliveryFee
            + $this->tax
            - $this->discountAmount
        );

        // Keep the Payment component (bKash instructions) in sync with the total.
        $this->dispatch('checkout-total-updated', total: $this->total);
    }

    public function render()
    {
        return view('livewire.checkout.summary');
    }

    /**
     * Called when delivery method / address changes.
     * Other Livewire components can emit: $this->dispatch('delivery-updated', amount)
     */
    public function updateDeliveryFee(float $amount): void
    {
        $this->deliveryFee = $amount;
        $this->calculateTotal();
    }

    /**
     * Apply discount code logic
     */
    public function applyDiscount(): void
    {
        $this->discountError = null;
        $this->discountAmount = 0;

        $this->validate([
            'discountCode' => ['nullable', 'string', 'max:50'],
        ]);

        $code = trim($this->discountCode);

        if ($code === '') {
            return;
        }

        // Simple demo rules – replace with real coupon logic
        if (strtoupper($code) === 'LEARN&FUN2026') {
            $this->discountAmount = round($this->subtotal * 0.10, 2);
        } else {
            $this->discountError = 'Invalid discount code.';
        }

        $this->calculateTotal();
    }

}
