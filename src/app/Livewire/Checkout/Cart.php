<?php

namespace App\Livewire\Checkout;

use App\Models\CartItem;
use App\Services\CartService;
use Livewire\Component;

class Cart extends Component
{
    public $items = [];
    public $subtotal = 0;
    public $itemCount = 0;
    public $totalQuantity = 0;

    protected $listeners = [
        'cart-updated' => 'refreshCart',
    ];

    public function mount(): void
    {
        $this->refreshCart();
    }

    public function refreshCart(): void
    {
        $service = CartService::forCurrent();

        $items = $service->getItems();

        $this->items = $items->map(fn (CartItem $item) => [
            'id'                => $item->id,
            'product_name'      => $item->product->name ?? '',
            'product_slug'      => $item->product->slug ?? '',
            'variant_name'      => $item->productVariant->name ?? '',
            'quantity'          => $item->quantity,
            'unit_price'        => $item->unit_price,
            'total_price'       => $item->total_price,
            'thumbnail'         => $item->productVariant->image ?? null,
        ])->toArray();

        $totals = $service->getTotals();

        $this->subtotal       = $totals['subtotal'];
        $this->itemCount      = $totals['item_count'];
        $this->totalQuantity  = $totals['total_quantity'];
    }

    public function increment($cartItemId): void
    {
        $service = CartService::forCurrent();

        $item = $service->updateQuantity(
            $cartItemId,
            $this->getCurrentQuantity($cartItemId) + 1,
        );

        $this->refreshCart();
        $this->dispatch('cart-updated');
    }

    public function decrement($cartItemId): void
    {
        $service = CartService::forCurrent();

        $newQty = $this->getCurrentQuantity($cartItemId) - 1;
        $service->updateQuantity($cartItemId, $newQty);

        $this->refreshCart();
        $this->dispatch('cart-updated');
    }

    public function remove($cartItemId): void
    {
        CartService::forCurrent()->removeItem($cartItemId);

        $this->refreshCart();
        $this->dispatch('cart-updated');
    }

    public function clear(): void
    {
        CartService::forCurrent()->clear();

        $this->refreshCart();
        $this->dispatch('cart-updated');
    }

    protected function getCurrentQuantity($cartItemId): int
    {
        foreach ($this->items as $item) {
            if ($item['id'] == $cartItemId) {
                return (int) $item['quantity'];
            }
        }

        return 0;
    }

    public function render()
    {
        return view('livewire.checkout.cart');
    }
}
