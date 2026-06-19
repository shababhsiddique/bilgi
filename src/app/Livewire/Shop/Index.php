<?php

namespace App\Livewire\Shop;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class Index extends Component
{
    public $selectedCategory = null;
    public $sortBy = 'name-az';
    public ?int $filterMinPrice = null;
    public ?int $filterMaxPrice = null;
    public $searchQuery = '';
    public $perPage = 12;
    public $loading = false;

    protected $listeners = [
        'filters-applied' => 'handleFiltersApplied',
        'filters-cleared' => 'handleFiltersCleared',
        'load-more' => 'loadMore',
    ];

    public function mount(?string $categorySlug = null)
    {
        // Initialize search query from URL parameter
        $this->searchQuery = request()->query('search', '');

        // When rendered from a category landing page, pre-select that category
        // so the grid is scoped to it.
        if ($categorySlug) {
            $this->selectedCategory = Category::where('slug', $categorySlug)
                ->where('visible', true)
                ->value('id');
        }
    }

    public function loadMore()
    {
        if (!$this->loading) {
            $this->loading = true;
            $this->perPage += 12;
            $this->loading = false;
        }
    }

    public function resetPagination()
    {
        $this->perPage = 12;
    }

    public function handleFiltersApplied($filters): void
    {
        $this->filterMinPrice = $filters['minPrice'] ?? null;
        $this->filterMaxPrice = $filters['maxPrice'] ?? null;
        $this->resetPagination();
    }

    public function handleFiltersCleared(): void
    {
        $this->filterMinPrice = null;
        $this->filterMaxPrice = null;
        $this->resetPagination();
    }


    public function selectCategory($categoryId = null)
    {
        // Toggle logic: if the same category is clicked, deselect it
        if ($this->selectedCategory == $categoryId) {
            $this->selectedCategory = null;
        } else {
            $this->selectedCategory = $categoryId;
        }
        $this->resetPagination();
    }

    public function updatedSortBy()
    {
        // This method is automatically called when sortBy property changes
        // It will trigger a re-render of the component
        $this->resetPagination();
    }

    public function updatedSearchQuery()
    {
        $this->resetPagination();
    }

    private function applySorting($productsQuery)
    {
        switch ($this->sortBy) {
            case 'price-low-high':
                return $productsQuery->leftJoin('product_variants', function($join) {
                    $join->on('products.id', '=', 'product_variants.product_id')
                         ->where('product_variants.is_default', true);
                })->orderBy('product_variants.sales_price', 'asc')
                ->select('products.*'); // Ensure we only select product fields to avoid conflicts

            case 'price-high-low':
                return $productsQuery->leftJoin('product_variants', function($join) {
                    $join->on('products.id', '=', 'product_variants.product_id')
                         ->where('product_variants.is_default', true);
                })->orderBy('product_variants.sales_price', 'desc')
                ->select('products.*'); // Ensure we only select product fields to avoid conflicts

            case 'name-za':
                return $productsQuery->orderBy('products.name', 'desc');

            case 'newest':
                return $productsQuery->orderBy('products.created_at', 'desc');

            case 'name-az':
            default:
                return $productsQuery->orderBy('products.name', 'asc');
        }
    }

    private function applyPriceFilters($productsQuery)
    {
        // If no price filters are set, return the query unchanged
        if (!$this->filterMinPrice && !$this->filterMaxPrice) {
            return $productsQuery;
        }

        // Join with product_variants to filter by default variant price
        $productsQuery->whereHas('variants', function ($query) {
            if ($this->filterMinPrice) {
                $query->where('sales_price', '>=', $this->filterMinPrice);
            }

            if ($this->filterMaxPrice) {
                $query->where('sales_price', '<=', $this->filterMaxPrice);
            }
        });

        return $productsQuery;
    }

    private function applySearchFilter($productsQuery)
    {
        if (empty(trim($this->searchQuery))) {
            return $productsQuery;
        }

        $searchTerm = trim($this->searchQuery);

        return $productsQuery->where(function ($query) use ($searchTerm) {
            $query->where('products.name', 'like', "%{$searchTerm}%")
                  ->orWhere('products.description', 'like', "%{$searchTerm}%")
                  ->orWhere('products.long_description', 'like', "%{$searchTerm}%")
                  ->orWhere('products.brand', 'like', "%{$searchTerm}%");
        });
    }


    public function render()
    {
        // Fetch visible categories for the filter pills
        $categories = Category::where('visible', true)
            ->orderBy('name')
            ->get();

        // Fetch visible products with their default variant for pricing
        $productsQuery = Product::where('products.visible', true);

        // Filter by category if selected
        if ($this->selectedCategory) {
            $productsQuery->whereHas('categories', function ($query) {
                $query->where('categories.id', $this->selectedCategory);
            });
        }

        // Apply search filter
        $productsQuery = $this->applySearchFilter($productsQuery);

        // Apply price filters
        $productsQuery = $this->applyPriceFilters($productsQuery);

        // Apply sorting
        $productsQuery = $this->applySorting($productsQuery);

        // Count total products before applying limit
        $totalProducts = $productsQuery->count();

        // Always eager load the relationships after sorting
        // Use limit instead of paginate for infinite scroll
        $products = $productsQuery->with(['defaultVariant', 'medias'])->limit($this->perPage)->get();
        $hasMore = $products->count() < $totalProducts;

        return view('livewire.shop.index', [
            'products' => $products,
            'categories' => $categories,
            'hasMore' => $hasMore,
            'totalProducts' => $totalProducts,
        ]);
    }
}
