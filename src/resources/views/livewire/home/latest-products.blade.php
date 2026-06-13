<section class="bg-slate-50 py-8 sm:py-12">
    <div class="mx-auto max-w-6xl px-4">
        <div class="mb-6 text-center">
            <p class="text-xs font-semibold tracking-[0.25em] text-rose-400 sm:text-sm">LATEST PRODUCT</p>
            <h2 class="mt-2 text-xl font-bold sm:text-2xl">Latest Product</h2>
        </div>
        <div class="grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-4">
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

    {{-- variant selector modal --}}
    <x-variant-selector-modal
        :show="$showVariantModal"
        :products="$products"
        :selected-product-id="$selectedProductId"
        close-action="closeVariantModal"
        confirm-action="confirmVariant"
    />
</section>
