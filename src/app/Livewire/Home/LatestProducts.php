<?php

namespace App\Livewire\Home;

use App\Models\Product;
use Livewire\Component;

class LatestProducts extends Component
{
    public bool $showVariantModal = false;
    public ?int $selectedProductId = null;
    public string $pendingAction = 'add'; // 'add' | 'buy'

    public function render()
    {
        $products = Product::query()
            ->where('visible', true)
            ->orderByDesc('created_at')
            ->with(['defaultVariant', 'variants'])
            ->limit(4)
            ->get();

        return view('livewire.home.latest-products', [
            'products' => $products,
        ]);
    }
}
