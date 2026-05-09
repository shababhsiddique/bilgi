@props([
    'show' => false,
    'minPrice' => null,
    'maxPrice' => null,
    'closeAction' => 'closeFilterModal',
    'applyAction' => 'applyFilters',
    'clearAction' => 'clearFilters',
])

@if($show)
    <div
        class="fixed inset-0 z-40 flex items-center justify-center bg-black/50"
        x-data
    >
        <div
            class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl"
            @click.away="$wire.{{ $closeAction }}()"
        >
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-xl font-semibold">
                        Filter Products
                    </h3>
                    <p class="mt-1 text-sm text-slate-500">
                        Set your price range to find products
                    </p>
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

            <div class="mt-6 space-y-4">
                <!-- Price Range Section -->
                <div>
                    <h4 class="mb-3 text-sm font-medium text-slate-700">Price Range (BDT)</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <!-- Minimum Price -->
                        <div>
                            <label for="minPrice" class="block text-xs font-medium text-slate-600 mb-1">
                                Minimum
                            </label>
                            <input
                                type="number"
                                id="minPrice"
                                wire:model="minPrice"
                                placeholder="0"
                                min="0"
                                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                            >
                        </div>

                        <!-- Maximum Price -->
                        <div>
                            <label for="maxPrice" class="block text-xs font-medium text-slate-600 mb-1">
                                Maximum
                            </label>
                            <input
                                type="number"
                                id="maxPrice"
                                wire:model="maxPrice"
                                placeholder="10000"
                                min="0"
                                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                            >
                        </div>
                    </div>
                </div>

                <!-- Current Filter Display -->
                @if($minPrice || $maxPrice)
                    <div class="rounded-lg bg-slate-50 p-3">
                        <p class="text-xs font-medium text-slate-600 mb-1">Current Filter:</p>
                        <p class="text-sm text-slate-700">
                            BDT {{ $minPrice ? number_format($minPrice) : '0' }} -
                            BDT {{ $maxPrice ? number_format($maxPrice) : '∞' }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex items-center justify-between">
                <button
                    type="button"
                    class="rounded-full bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200"
                    wire:click="{{ $clearAction }}"
                >
                    Clear Filters
                </button>

                <div class="flex gap-2">
                    <button
                        type="button"
                        class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                        wire:click="{{ $closeAction }}"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700"
                        wire:click="{{ $applyAction }}"
                    >
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
