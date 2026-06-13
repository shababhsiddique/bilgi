@props([
    'show' => false,
    'products' => collect(),
    'selectedProductId' => null,
    'closeAction' => 'closeVariantModal',
    'confirmAction' => 'confirmVariant',
])

@if($show && $selectedProductId)
    @php
        $selectedProduct = $products->firstWhere('id', $selectedProductId);
        $variants = $selectedProduct?->variants ?? collect();
    @endphp

    <div
            class="fixed inset-0 z-40 flex items-end justify-center bg-black/50 sm:items-center sm:p-4"
            x-data
    >
        <div
                class="w-full max-w-lg rounded-t-2xl bg-white p-5 shadow-xl sm:rounded-2xl sm:p-6"
                @click.away="$wire.{{ $closeAction }}()"
        >
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-xl font-semibold">
                        Choose a variant
                    </h3>
                    @if($selectedProduct)
                        <p class="mt-1 text-sm text-slate-500">
                            {{ $selectedProduct->name }}
                        </p>
                    @endif
                </div>

                <button
                        type="button"
                        class="ml-3 text-slate-400 hover:text-slate-600"
                        wire:click="{{ $closeAction }}"
                >
                    <span class="sr-only">Close</span>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        class="h-5 w-5"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M6.75 6.75l10.5 10.5m0-10.5L6.75 17.25"
                        />
                    </svg>
                </button>
            </div>

            <div class="mt-4 max-h-80 space-y-2 overflow-y-auto">
                @forelse($variants as $variant)
                    <button
                            type="button"
                            wire:click="{{ $confirmAction }}({{ $variant->id }})"
                            class="flex w-full items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-left hover:border-emerald-500 hover:bg-emerald-50"
                    >
                        {{-- Variant image --}}
                        <div class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-xl bg-slate-100">
                            @if($variant->image)
                                <img
                                        src="{{ asset('storage/' . ltrim($variant->image, '/')) }}"
                                        alt="{{ $variant->name ?? $selectedProduct?->name }}"
                                        class="h-full w-full object-cover"
                                >
                            @else
                                <div class="flex h-full w-full items-center justify-center text-xs text-slate-400">
                                    No Image
                                </div>
                            @endif
                        </div>

                        {{-- Variant info --}}
                        <div class="flex flex-1 items-center justify-between">
                            <div>
                                <div class="text-sm font-medium text-slate-800">
                                    {{ $variant->name ?? 'Default' }}
                                </div>
                                <div class="mt-0.5 text-xs text-slate-500">
                                    SKU: {{ $variant->sku ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-semibold text-emerald-600">
                                    BDT {{ number_format($variant->sales_price,0) }}
                                </div>
                                <div class="mt-0.5 text-xs text-slate-500">
                                    Stock: {{ $variant->stock ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </button>
                @empty
                    <p class="text-sm text-slate-500">
                        No variants available for this product.
                    </p>
                @endforelse
            </div>

            <div class="mt-4 flex justify-end">
                <button
                        type="button"
                        class="rounded-full bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200"
                        wire:click="{{ $closeAction }}"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
@endif
