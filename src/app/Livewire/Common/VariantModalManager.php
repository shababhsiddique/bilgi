<?php

namespace App\Livewire\Common;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class VariantModalManager extends Component
{
    public bool $showVariantModal = false;
    public ?int $selectedProductId = null;
    public string $pendingAction = 'add'; // 'add' | 'buy'

    public function render()
    {
        $products = $this->selectedProductId
            ? Product::where('id', $this->selectedProductId)
                ->with(['defaultVariant', 'variants'])
                ->get()
            : collect();

        return view('livewire.common.variant-modal-manager', [
            'products' => $products,
        ]);
    }

    #[On('product-add-clicked')]
    public function handleProductAddClicked(int $productId): void
    {
        $this->openVariantModalForAdd($productId);
    }

    #[On('product-buy-clicked')]
    public function handleProductBuyClicked(int $productId): void
    {
        $this->openVariantModalForBuy($productId);
    }

    public function openVariantModalForAdd(int $productId): void
    {
        $product = Product::with('variants')->find($productId);

        if (! $product) {
            return;
        }

        $variants = $product->variants;

        // If the product has exactly one variant, skip the modal and add directly.
        if ($variants->count() === 1) {
            $singleVariant = $variants->first();
            $this->addToCart($singleVariant->id);
            return;
        }

        // Otherwise, open the modal as before.
        $this->selectedProductId = $productId;
        $this->pendingAction = 'add';
        $this->showVariantModal = true;
    }

    public function openVariantModalForBuy(int $productId): void
    {
        $product = Product::with('variants')->find($productId);

        if (! $product) {
            return;
        }

        $variants = $product->variants;

        // If the product has exactly one variant, skip the modal and buy directly.
        if ($variants->count() === 1) {
            $singleVariant = $variants->first();
            $this->buyNow($singleVariant->id);
            return;
        }

        // Otherwise, open the modal as before.
        $this->selectedProductId = $productId;
        $this->pendingAction = 'buy';
        $this->showVariantModal = true;
    }

    public function closeVariantModal(): void
    {
        $this->showVariantModal = false;
        $this->selectedProductId = null;
        $this->pendingAction = 'add';
    }

    public function confirmVariant(int $variantId)
    {
        if ($this->pendingAction === 'buy') {
            return $this->buyNow($variantId);
        }

        $this->addToCart($variantId);
        $this->closeVariantModal();
    }

    protected function addToCart(int $variantId): void
    {
        // Shared cart logic for all product lists
        $variant = ProductVariant::find($variantId);

        CartService::forCurrent()->addItem(
            productId: $variant->product_id,
            variantId: $variantId,
            quantity: 1,
        );

        $this->dispatch('cart-updated');
    }

    protected function buyNow(int $variantId)
    {
        $this->addToCart($variantId);
        $this->closeVariantModal();

        return redirect()->route('checkout');
    }
}
