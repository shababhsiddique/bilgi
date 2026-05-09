<!-- STEP 1: Order summary (cart items) -->
<div>
    <div class="mb-6 flex items-center gap-3">
        <h2 class="text-2xl font-semibold text-slate-600">
            Checkout
        </h2>
    </div>

    @if(empty($items))
        <p class="text-sm text-slate-500">
            Your cart is empty.
        </p>
    @else
        <div class="space-y-6">
            @foreach ($items as $item)
                <div class="flex gap-4">
                    <!-- Thumb -->
                    <div class="h-20 w-28 overflow-hidden rounded-md bg-slate-100">
                        <a target="_blank" href="{{route('product.show', $item['product_slug'])}}">
                            @if($item['thumbnail'])
                                <img
                                    src="{{ asset("storage/$item[thumbnail]") }}"
                                    alt="{{ $item['product_name'] }}"
                                    class="h-full w-full object-cover"
                                />
                            @else
                                <img
                                    src="https://via.placeholder.com/160x120"
                                    alt="{{ $item['product_name'] }}"
                                    class="h-full w-full object-cover"
                                />
                            @endif
                        </a>
                    </div>

                    <!-- Body -->
                    <div class="flex-1">
                        <!-- Row 1: title + price -->
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-600">
                                    <a target="_blank" href="{{route('product.show', $item['product_slug'])}}">
                                        {{ $item['product_name'] }}
                                    </a>
                                </h3>

                                @if(!empty($item['variant_name']))
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $item['variant_name'] }}
                                    </p>
                                @endif
                            </div>

                            <p class="text-sm font-semibold text-slate-600 whitespace-nowrap">
                                {{-- total_price is in smallest unit (e.g. cents) – divide by 100 --}}
                                {{ number_format($item['total_price'], 0) }}
                                <span class="text-xs align-top">৳</span>
                            </p>
                        </div>

                        <!-- Row 2: actions + qty -->
                        <div class="mt-2 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3 text-xs font-medium">
                                {{-- <button
                                    type="button"
                                    wire:click="remove({{ $item['id'] }})"
                                    class="cursor-pointer text-rose-500 hover:underline"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <polyline points="3 6 5 6 21 6" />
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                        <path d="M10 11v6" />
                                        <path d="M14 11v6" />
                                        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                    </svg>
                                </button>--}}
                            </div>

                            <div
                                class="inline-flex items-center rounded-full border border-slate-200 px-4 py-1.5">
                                <button
                                    type="button"
                                    wire:click="decrement({{ $item['id'] }})"
                                    class="cursor-pointer px-1 text-lg text-rose-500 hover:text-rose-600"
                                >
                                    −
                                </button>

                                <span class="px-3 text-slate-600">
                                    {{ $item['quantity'] }}
                                </span>

                                <button
                                    type="button"
                                    wire:click="increment({{ $item['id'] }})"
                                    class="cursor-pointer px-1 text-xl text-rose-500 hover:text-rose-600"
                                >
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Optional: clear cart button --}}
            <div class="pt-4 border-t border-slate-200 flex justify-between items-center text-sm">
                <p class="text-slate-600">
                    Subtotal:
                    <span class="font-semibold">
                        {{ number_format($subtotal / 100, 0) }} ৳
                    </span>
                </p>

                <button
                    type="button"
                    wire:click="clear"
                    class="cursor-pointer text-xs font-medium text-rose-500 hover:underline"
                >
                    Clear cart
                </button>
            </div>
        </div>
    @endif
</div>
