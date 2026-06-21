@props(['title', 'description' => null, 'eyebrow' => null])

{{-- Shared hero for shop-style listing pages (all products + category landing).
     Sits in the same sky band as the breadcrumb above and the product grid below. --}}
<div class="bg-sky-50/70">
    <div class="mx-auto max-w-6xl px-4 pt-4 pb-6 text-center">
        @if($eyebrow)
            <p class="text-xs font-semibold uppercase tracking-wider text-rose-500">{{ $eyebrow }}</p>
        @endif
        <h1 class="mt-1 text-xl font-bold text-slate-900 sm:text-2xl">{{ $title }}</h1>
        @if($description)
            <p class="mx-auto mt-1.5 max-w-2xl text-xs leading-relaxed text-slate-500 sm:text-sm">
                {{ $description }}
            </p>
        @endif
    </div>
</div>
