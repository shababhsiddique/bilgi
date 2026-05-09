<div class="flex items-center gap-4 text-sm"
     x-data="{ animate: false }"
     @cart-updated.window="animate = true; setTimeout(() => animate = false, 400)">
    <a href="{{route('checkout')}}">
        <div class="hidden text-right sm:block">
            <p class="font-semibold text-slate-700">Shopping Cart</p>
            <p class="text-[12px] text-slate-500">
                {{ $totalQuantity }}
                {{ $totalQuantity === 1 ? 'Item' : 'Items' }}
            </p>
        </div>
    </a>
    <a href="{{route('checkout')}}"
       class="relative inline-flex h-10 w-10 items-center justify-center rounded-full bg-rose-500 text-white transition-all duration-300 ease-out"
       :class="{ 'scale-140 shadow-lg': animate }"
    >
        <!-- Cart icon -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
             stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
        </svg>
        <span
            class="absolute -top-1 -right-1 inline-flex h-4 w-4 items-center justify-center rounded-full bg-white text-[11px] font-semibold text-rose-500 transition-all duration-300"
            :class="{ 'scale-180 bg-rose-100': animate }"
        >{{ $totalQuantity }}
        </span>
    </a>
</div>
