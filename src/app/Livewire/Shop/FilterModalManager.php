<?php

namespace App\Livewire\Shop;

use Livewire\Component;

class FilterModalManager extends Component
{
    public bool $showFilterModal = false;
    public ?int $minPrice = null;
    public ?int $maxPrice = null;

    protected $listeners = ['openFilterModal' => 'openFilterModal'];


    public function render()
    {
        return view('livewire.shop.filter-modal-manager');
    }

    public function openFilterModal(): void
    {
        $this->showFilterModal = true;
    }

    public function closeFilterModal(): void
    {
        $this->showFilterModal = false;
    }

    public function applyFilters(): void
    {
        // Validate price range
        if ($this->minPrice && $this->maxPrice && $this->minPrice > $this->maxPrice) {
            session()->flash('error', 'Minimum price cannot be greater than maximum price.');
            return;
        }

        // Emit event with filter data to parent component
        $this->dispatch('filters-applied', [
            'minPrice' => $this->minPrice,
            'maxPrice' => $this->maxPrice,
        ]);

        $this->closeFilterModal();
    }

    public function clearFilters(): void
    {
        $this->minPrice = null;
        $this->maxPrice = null;

        // Emit event to clear filters in parent component
        $this->dispatch('filters-cleared');

        $this->closeFilterModal();
    }
}
