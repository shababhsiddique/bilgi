<?php

namespace App\Livewire\Common;

use App\Models\Product;
use App\Services\CartService;
use Livewire\Component;

class ProductCard extends Component
{
    public Product $product;
    public string $priceDisplay = 'default';


    public function mount(Product $product,string $priceDisplay = 'default'): void
    {
        $this->product = $product;
        $this->priceDisplay = $priceDisplay;
    }

    public function add(): void
    {
        $this->dispatch('product-add-clicked', productId: $this->product->id);

        /*
         *
        $variantId = $this->product->default?->id;

        CartService::forCurrent()->addItem(
            productId: $this->product->id,
            variantId: $variantId,
            quantity: 1,
        );

        $this->dispatch('cart-updated');*/
    }

    public function buy()
    {
        $this->dispatch('product-buy-clicked', productId: $this->product->id);
        /*
         * $variantId = $this->product->default?->id;

        CartService::forCurrent()->addItem(
            productId: $this->product->id,
            variantId: $variantId,
            quantity: 1,
        );

        $this->dispatch('cart-updated');

        return redirect()->route('checkout');
         * */
    }

    private function getPriceRange()
    {
        if (!$this->product->variants || $this->product->variants->count() <= 1) {
            return null;
        }

        $prices = $this->product->variants->pluck('sales_price')->filter();

        if ($prices->isEmpty()) {
            return null;
        }

        $min = $prices->min();
        $max = $prices->max();

        return $min === $max ? null : compact('min', 'max');
    }


    public function render()
    {
        return view('livewire.common.product-card',[
            'priceRange' => $this->getPriceRange()
        ]);
    }
}
