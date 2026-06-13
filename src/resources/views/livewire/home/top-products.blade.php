<section class="bg-white py-8 sm:py-12">
    <div class="mx-auto max-w-6xl px-4">
        <div class="mb-6 text-center">
            <h2 class="mt-2 text-xl font-bold sm:text-2xl">Top Products</h2>

            <!-- Filter pills
            <div class="mt-4 inline-flex gap-2 rounded-full bg-slate-100 p-1 text-sm">
                <button
                    wire:click="setFilter('featured')"
                    class="rounded-full px-4 py-1 shadow-sm
                        @if($filter === 'featured')
                font-semibold bg-white text-rose-500


            @else
                text-slate-500 hover:text-rose-500


            @endif">
                    Featured
                </button>
                <button
                    wire:click="setFilter('popular')"
                    class="rounded-full px-4 py-1
                        @if($filter === 'popular')
                font-semibold bg-white text-rose-500 shadow-sm


            @else
                text-slate-500 hover:text-rose-500


            @endif">
                    Popular
                </button>
                <button
                    wire:click="setFilter('new')"
                    class="rounded-full px-4 py-1
                        @if($filter === 'new')
                font-semibold bg-white text-rose-500 shadow-sm


            @else
                text-slate-500 hover:text-rose-500


            @endif">
                    New
                </button>
            </div>-->
        </div>

        <!-- Product cards (currently static; later replace with loop over $products) -->
        <div class="grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-3">
            @forelse($products as $product)
                <livewire:common.product-card
                    :product="$product"
                    :wire:key="'top-product-'.$product->id"
                />
            @empty
                <p class="col-span-4 text-center text-sm text-slate-500">
                    No products found.
                </p>
            @endforelse
        </div>
    </div>

    {{-- Variant selection modal (reusable component) --}}
    <x-variant-selector-modal
        :show="$showVariantModal"
        :products="$products"
        :selected-product-id="$selectedProductId"
        close-action="closeVariantModal"
        confirm-action="confirmVariant"
    />
</section>
