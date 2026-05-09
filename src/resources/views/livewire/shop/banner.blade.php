<div class=" max-w-6xl">
    @php
        use App\Models\Content;$content = Content::key('shop_banner_data');
        $bannerData = json_decode($content->content_data);
    @endphp
    <div class="grid gap-6 rounded-[40px] bg-rose-50 px-8 py-10 md:grid-cols-2">
        <div class="flex flex-col justify-center gap-3">
            <p class="text-sm font-semibold text-rose-400">{{$bannerData->subtitle}}</p>
            <h2 class="text-2xl font-bold">{{$bannerData->title}}</h2>
            <p class="text-base text-slate-600">
                {!! $bannerData->text !!}
            </p>
            <a
                href="{{ route('product.show', $content->product->slug) }}"
                class="mt-2 inline-flex w-max items-center rounded-full px-6 py-2 text-sm text-white font-semibold
                bg-rose-500 shadow-md
                hover:bg-rose-600 hover:scale-110
                hover:shadow-xl
                transition-all duration-300
                ease-in-out
                ">
                Shop Now
            </a>
        </div>
        <div class="flex items-center justify-end">
            <img src="{{ asset('storage/' . $content->product->thumbnail) }}"
                 alt="{{ $content->product->name }}"
                 class="object-cover h-40 w-52 rounded-[32px]">
        </div>
    </div>
</div>
