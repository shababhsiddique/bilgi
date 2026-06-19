<div class="bg-sky-50/70">

    <!-- Add this at the top of your component -->
    @livewire('shop.filter-modal-manager')

    <!-- ALL PRODUCTS -->
    <section class="pt-2 pb-0">
        <div class="mx-auto max-w-6xl px-4">
            @if($searchQuery)
                <div class="mb-4 text-center">
                    <p class="text-sm text-slate-600">
                        Search results for: <span class="font-semibold text-slate-900">"{{ $searchQuery }}"</span>
                        <a href="{{ route('shop') }}" class="ml-2 text-rose-500 hover:text-rose-600">Clear search</a>
                    </p>
                    <p class="mt-1 text-xs text-slate-500">{{ $totalProducts }} product(s) found</p>
                </div>
            @endif

            <div class="mb-3">
                <!-- Filter pills: horizontal scroll on mobile, centered wrap on desktop -->
                <div class="-mx-4 flex items-center gap-2 overflow-x-auto px-4 pb-1 text-sm text-slate-500 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden sm:mx-0 sm:flex-wrap sm:justify-center sm:gap-4 sm:overflow-visible sm:px-0">
                    <!-- Dynamic category pills -->
                    @foreach($categories as $category)
                        {{-- Real link so crawlers + modifier/middle clicks reach the category landing page;
                             a plain left-click is intercepted to filter in-place via Livewire (snappier, no reload). --}}
                        <a
                            href="{{ route('category.show', $category->slug) }}"
                            @click="if (!$event.metaKey && !$event.ctrlKey && !$event.shiftKey && !$event.altKey && $event.button === 0) { $event.preventDefault(); $wire.selectCategory({{ $category->id }}) }"
                            class="shrink-0 cursor-pointer whitespace-nowrap rounded-full border px-5 py-2 transition-colors hover:border-slate-300 sm:px-6
                            {{ $selectedCategory == $category->id ? 'border-slate-300 bg-slate-50 text-slate-700' : 'bg-white border-slate-200' }}"
                        >
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Sort and Filter Row -->
            <div class="mb-3 flex items-center justify-between gap-2">
                <!-- Left side: Sort By Dropdown -->
                <div class="flex min-w-0 items-center gap-2">
                    <label for="sortBy" class="hidden text-sm font-medium text-slate-700 sm:inline">Sort by:</label>
                    <select
                        id="sortBy"
                        wire:model.live="sortBy"
                        class="min-w-0 rounded-full border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700"
                    >
                        <option value="price-low-high">Price: Low to High</option>
                        <option value="price-high-low">Price: High to Low</option>
                        <option value="name-az">Name: A to Z</option>
                        <option value="name-za">Name: Z to A</option>
                        <option value="newest">Newest</option>
                    </select>
                </div>

                <!-- Right side: Filter Button -->
                <button
                    wire:click="$dispatch('openFilterModal')"
                    class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 focus:border-sky-500 focus:outline-none"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m0 0a1.5 1.5 0 0 0 3 0M10.5 18h9.75m-9.75 0a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 18H7.5m0 0a1.5 1.5 0 0 0 3 0"/>
                    </svg>
                    Filter
                    @if($filterMinPrice || $filterMaxPrice)
                        <span class="ml-1 rounded-full bg-emerald-100 px-2 py-0.5 text-xs text-emerald-700">
                            Active
                        </span>
                    @endif
                </button>
            </div>

            <!-- Product cards - First row (4 products on large screens) -->
            <div
                id="products-grid"
                class="grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-4 mb-8"
                wire:loading.class="opacity-50"
            >
                @foreach($products->take(4) as $product)
                    @livewire('common.product-card', [
                        'product' => $product,
                        'priceDisplay' => ($filterMinPrice || $filterMaxPrice) ? 'range' : 'default'
                    ], key('product-' . $product->id . '-' . ($selectedCategory ?? 'all') . '-' . $sortBy . '-' . ($filterMinPrice ?? 0) . '-' . ($filterMaxPrice ?? 0) . '-' . $perPage))
                @endforeach
            </div>

            <!-- BANNER: KIDS TOY COLLECTION -->
            <section class="pb-5 pt-6 mb-8">
                @livewire('shop.banner')
            </section>

            <!-- Product cards - Rest of the products -->
            @if($products->count() > 4)
                <div
                    class="grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-4 mb-8"
                    wire:loading.class="opacity-50"
                >
                    @foreach($products->skip(4) as $product)
                        @livewire('common.product-card', [
                            'product' => $product,
                            'priceDisplay' => ($filterMinPrice || $filterMaxPrice) ? 'range' : 'default'
                        ], key('product-' . $product->id . '-' . ($selectedCategory ?? 'all') . '-' . $sortBy . '-' . ($filterMinPrice ?? 0) . '-' . ($filterMaxPrice ?? 0) . '-' . $perPage))
                    @endforeach
                </div>
            @endif

            <!-- Infinite scroll trigger and loading indicator -->
            @if($hasMore)
                <div
                    id="infinite-scroll-trigger"
                    class="flex justify-center items-center py-8"
                    wire:loading.class="opacity-50"
                >
                    <div class="text-center">
                        <div wire:loading wire:target="loadMore" class="inline-block">
                            <svg class="animate-spin h-8 w-8 text-rose-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <p class="mt-2 text-sm text-slate-500" wire:loading.remove wire:target="loadMore">
                            Scroll for more products
                        </p>
                    </div>
                </div>
            @elseif($products->count() > 0)
                <div class="text-center py-8">
                    <p class="text-sm text-slate-500">All products loaded ({{ $products->count() }} of {{ $totalProducts }})</p>
                </div>
            @endif
        </div>
    </section>
</div>

<script>
    (function() {
        let observer = null;
        let isLoading = false;
        const componentId = @js($this->getId());

        function initInfiniteScroll() {
            // Clean up existing observer
            if (observer) {
                observer.disconnect();
                observer = null;
            }

            const trigger = document.getElementById('infinite-scroll-trigger');
            if (!trigger) return;

            // Wait for Livewire to be available
            if (typeof Livewire === 'undefined') {
                setTimeout(initInfiniteScroll, 100);
                return;
            }

            // Create intersection observer
            observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !isLoading) {
                        isLoading = true;
                        try {
                            const livewireComponent = Livewire.find(componentId);
                            if (livewireComponent) {
                                livewireComponent.call('loadMore').then(() => {
                                    isLoading = false;
                                    // Re-initialize observer after loading to catch new trigger position
                                    setTimeout(() => {
                                        initInfiniteScroll();
                                    }, 100);
                                }).catch(() => {
                                    isLoading = false;
                                });
                            } else {
                                isLoading = false;
                            }
                        } catch (error) {
                            console.error('Error loading more products:', error);
                            isLoading = false;
                        }
                    }
                });
            }, {
                rootMargin: '200px' // Start loading 200px before the trigger is visible
            });

            observer.observe(trigger);
        }

        // Wait for Livewire to be initialized
        function waitForLivewire() {
            if (typeof Livewire !== 'undefined' && typeof Livewire.find === 'function') {
                initInfiniteScroll();
            } else {
                setTimeout(waitForLivewire, 50);
            }
        }

        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', waitForLivewire);
        } else {
            waitForLivewire();
        }

        // Re-initialize after Livewire updates
        document.addEventListener('livewire:init', () => {
            setTimeout(initInfiniteScroll, 100);
        });

        // Listen for component updates
        document.addEventListener('livewire:update', () => {
            setTimeout(initInfiniteScroll, 100);
        });
    })();
</script>
