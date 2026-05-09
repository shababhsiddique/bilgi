<?php

namespace App\Livewire\Common;

use App\Services\CartService;
use Livewire\Component;

class CartButton extends Component
{
    public int $itemCount = 0;
    public int $totalQuantity = 0;

    protected $listeners = [
        'cart-updated' => 'refreshCart',
    ];

    public function mount(): void
    {
        $this->refreshCart();
    }

    public function refreshCart(): void
    {
        $totals = CartService::forCurrent()->getTotals();

        $this->itemCount = $totals['item_count'];
        $this->totalQuantity = $totals['total_quantity'];
    }

    public function render()
    {
        return view('livewire.common.cart-button');
    }
}
