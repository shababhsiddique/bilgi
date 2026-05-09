<article class="relative rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-100
           hover:scale-105 hover:shadow-xl transition-all duration-500">
    @if($product->ribbon_text)
        @php [$bg, $text] = $product->ribbon_classes; @endphp
        <span class="absolute left-4 top-4 rounded-full px-3 py-1 text-[12px] font-semibold {{ $bg }} {{ $text }}">
            {{ strtoupper($product->ribbon_text) }}
        </span>
    @endif

    <a href="{{ route('product.show', $product->slug) }}">
        <div class="mb-4 flex w-full aspect-square items-center relative overflow-hidden justify-center rounded-2xl bg-slate-50">
            @if($product->thumbnail)
                <img
                    src="{{ asset("storage/$product->thumbnail") }}"
                    alt="{{ $product->name }}"
                    class="h-full w-full object-cover rounded-2xl"
                >

            @else
                <span class="text-sm text-slate-400">{{ $product->name }}</span>
            @endif
        </div>

        <h3 class="text-[15px] font-medium leading-snug line-clamp-2">
            {{ $product->name }} {{ $product->default->name ?? '' }}
        </h3>
    </a>

    <div class="mt-2 flex items-center justify-between">
        <span class="text-2xl text-slate-700 leading-tight">
             @if($priceDisplay === 'range' && $priceRange)
                From BDT {{ number_format($priceRange['min'], 0) }}
            @else
                BDT {{ number_format($product->default->sales_price ?? 0, 0) }}
            @endif
        </span>

        <button
            wire:click.stop="add"
            type="button"
            class="cursor-pointer rounded-full bg-amber-500 p-2 hover:bg-amber-600 transition flex items-center justify-center"
            aria-label="Add to Cart"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"
                 class="h-6 w-6" fill="none" stroke="white" stroke-width="1.7">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"
                />
                <circle cx="20" cy="8" r="4" fill="white" stroke="white" stroke-width="1.4"/>
                <path d="M20 6.5v3M18.5 8h3" stroke="#64748B" stroke-linecap="round" stroke-width="1.6"/>
            </svg>
        </button>
    </div>

    <button
        wire:click="buy"
        class="mt-3 w-full rounded-full bg-rose-500 py-2 text-lg font-semibold text-white hover:bg-rose-600 transition cursor-pointer">
        Buy Now
    </button>
</article>
