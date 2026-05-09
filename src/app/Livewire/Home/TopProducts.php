<?php

namespace App\Livewire\Home;

use App\Models\Product;
use Livewire\Component;

class TopProducts extends Component
{
    public string $filter = 'featured';

    // New properties for variant modal
    public bool $showVariantModal = false;
    public ?int $selectedProductId = null;
    public string $pendingAction = 'add'; // 'add' | 'buy'

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    public function render()
    {
        $products = Product::query()
            ->where('visible', true)
            ->orderByDesc('sort_order')
            ->with(['defaultVariant', 'variants'])
            ->limit(3)
            ->get();

        return view('livewire.home.top-products', [
            'products' => $products,
        ]);
    }
}
