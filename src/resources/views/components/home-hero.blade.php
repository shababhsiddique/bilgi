<section class="bg-sky-50/70">
    @php
        use App\Models\Content;$content = Content::key('hero_data');
        $heroData = json_decode($content->content_data);
    @endphp
    <div class="mx-auto grid max-w-6xl items-center gap-6 px-4 py-8 md:grid-cols-2 md:gap-8 md:py-16">
        <!-- Right hero image (shown first on mobile, second on desktop) -->
        <div class="order-1 flex justify-center md:order-2 md:justify-end">
            <a
                href="{{ route('product.show', $content->product->slug) }}">
                <img src="{{ asset('storage/' . $content->product->thumbnail) }}"
                     alt="{{ $content->product->name }}"
                     class="w-full max-w-xs object-cover rounded-3xl shadow-lg md:max-w-none
                     md:hover:scale-105 md:hover:shadow-2xl md:hover:rotate-1 transition-all duration-500 ease-in-out
                     cursor-pointer">
            </a>
        </div>

        <!-- Left content -->
        <div class="order-2 space-y-4 text-center md:order-1 md:text-left">
            <p class="text-sm font-semibold text-rose-500">
                {!! $heroData->discount !!}
            </p>
            <h1 class="text-3xl font-extrabold leading-tight sm:text-4xl">
                {{$heroData->subtitle}}<br/>
                <span class="text-rose-500">{{$heroData->title}}</span>
            </h1>
            <p class="text-base text-slate-600">
                {{$heroData->text}}
            </p>
            <a
                href="{{ route('product.show', $content->product->slug) }}"
                class="mt-2 inline-flex items-center rounded-full bg-rose-500 px-6 py-2.5 text-lg font-semibold text-white shadow-md hover:bg-rose-600 hover:scale-110 hover:shadow-xl transition-all duration-300 ease-in-out">
                {{$heroData->button_text}}
            </a>
        </div>
    </div>
</section>
