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
            class="fixed inset-0 z-40 flex items-end justify-center sm:items-center sm:p-4"
            x-data="{
                open: false,
                close() { this.open = false; setTimeout(() => $wire.{{ $closeAction }}(), 250); },
                pick(id) { this.open = false; setTimeout(() => $wire.{{ $confirmAction }}(id), 250); },
            }"
            x-init="$nextTick(() => open = true)"
            x-on:keydown.escape.window="close()"
    >
        {{-- Backdrop --}}
        <div
                class="absolute inset-0 bg-black/50"
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="close()"
        ></div>

        {{-- Sheet --}}
        <div
                class="relative w-full max-w-lg rounded-t-2xl bg-white p-5 pb-[max(1.25rem,env(safe-area-inset-bottom))] shadow-xl sm:rounded-2xl sm:p-6"
                x-show="open"
                x-transition:enter="transform transition ease-[cubic-bezier(0.32,0.72,0,1)] duration-[450ms]"
                x-transition:enter-start="translate-y-full opacity-0 sm:translate-y-3 sm:scale-95"
                x-transition:enter-end="translate-y-0 opacity-100 sm:scale-100"
                x-transition:leave="transform transition ease-[cubic-bezier(0.32,0.72,0,1)] duration-[250ms]"
                x-transition:leave-start="translate-y-0 opacity-100 sm:scale-100"
                x-transition:leave-end="translate-y-full opacity-0 sm:translate-y-3 sm:scale-95"
        >
            {{-- Grabber handle (mobile only) --}}
            <div class="mx-auto mb-4 h-1.5 w-10 rounded-full bg-slate-300 sm:hidden"></div>

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
                        @click="close()"
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
                            @click="pick({{ $variant->id }})"
                            class="flex w-full items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-left transition hover:border-emerald-500 hover:bg-emerald-50 active:scale-[0.98]"
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
                        @click="close()"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
@endif
