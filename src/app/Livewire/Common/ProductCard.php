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

    /**
     * Whether the product can be purchased.
     * Products that don't track inventory are always available.
     */
    private function inStock(): bool
    {
        $product = $this->product;

        if (!$product->track_inventory) {
            return true;
        }

        if ($product->variants && $product->variants->count() > 0) {
            return (int) $product->variants->sum('stock') > 0;
        }

        return (int) ($product->stock ?? 0) > 0;
    }

    /**
     * Build the price shown on the card.
     * `compare` is a placeholder "was" price (current + 10%) shown as a
     * strikethrough until real compare-at columns exist in the schema.
     */
    private function getCardPricing(?array $priceRange): array
    {
        if ($this->priceDisplay === 'range' && $priceRange) {
            $current = (float) $priceRange['min'];
            $isRange = true;
        } else {
            $current = (float) ($this->product->default->sales_price ?? 0);
            $isRange = false;
        }

        return [
            'current' => $current,
            'compare' => round($current * 1.10),
            'isRange' => $isRange,
        ];
    }


    public function render()
    {
        $priceRange = $this->getPriceRange();

        return view('livewire.common.product-card', [
            'priceRange' => $priceRange,
            'pricing'    => $this->getCardPricing($priceRange),
            'inStock'    => $this->inStock(),
        ]);
    }
}
