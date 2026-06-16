<article class="relative flex flex-col overflow-hidden rounded-2xl bg-white p-3 shadow-sm ring-1 ring-slate-100
           transition-all duration-500 sm:rounded-3xl sm:p-4 sm:hover:scale-105 sm:hover:shadow-xl">
    @if($product->ribbon_text)
        @php [$bg, $text] = $product->ribbon_classes; @endphp
        <span class="absolute -left-8 top-4 z-10 w-28 -rotate-45 py-1 text-center text-[10px] font-semibold uppercase tracking-wide shadow-md {{ $bg }} {{ $text }} sm:-left-7 sm:top-5">
            {{ strtoupper($product->ribbon_text) }}
        </span>
    @endif

    <a href="{{ route('product.show', $product->slug) }}">
        <div class="mb-3 flex w-full aspect-square items-center relative overflow-hidden justify-center rounded-xl bg-white sm:mb-4 sm:rounded-2xl">
            @if($product->thumbnail)
                <img
                    src="{{ asset("storage/$product->thumbnail") }}"
                    alt="{{ $product->name }}"
                    class="h-full w-full object-cover"
                >

            @else
                <span class="text-sm text-slate-400">{{ $product->name }}</span>
            @endif

            @unless($inStock)
                <span class="absolute inset-0 flex items-center justify-center bg-white/60">
                    <span class="rounded-full bg-slate-900/80 px-3 py-1 text-xs font-semibold text-white">
                        Out of Stock
                    </span>
                </span>
            @endunless
        </div>

        <div class="flex items-start justify-between gap-2">
            <h3 class="text-sm font-medium leading-snug line-clamp-2 sm:text-[15px]">
                {{ $product->name }} {{ $product->default->name ?? '' }}
            </h3>
            @if($product->age_group)
                <span class="mt-0.5 shrink-0 rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-500 sm:text-xs">
                    Ages {{ rtrim($product->age_group, '+') }}+
                </span>
            @endif
        </div>
    </a>

    <div class="mt-2 flex items-baseline gap-2">
        <span class="text-lg text-slate-800 leading-tight sm:text-2xl">
            @if($pricing['isRange'])From @endif BDT {{ number_format($pricing['current'], 0) }}
        </span>
        @if($pricing['current'] > 0)
            <span class="text-sm text-slate-400 line-through sm:text-base">
                BDT {{ number_format($pricing['compare'], 0) }}
            </span>
        @endif
    </div>

    @if($inStock)
        <div class="mt-3 flex items-stretch gap-2">
            <button
                wire:click="buy"
                type="button"
                class="flex-1 rounded-full bg-rose-500 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-600 cursor-pointer sm:text-base">
                Buy Now
            </button>
            <button
                wire:click.stop="add"
                type="button"
                aria-label="Add to Cart"
                class="flex aspect-square shrink-0 items-center justify-center rounded-full border-2 border-amber-400 bg-white text-amber-500 transition hover:bg-amber-50 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                </svg>
            </button>
        </div>
    @else
        <button
            type="button"
            disabled
            class="mt-3 w-full rounded-full bg-slate-100 py-2.5 text-sm font-semibold text-slate-400 cursor-not-allowed sm:text-base">
            Out of Stock
        </button>
    @endif
</article>
