<section class="bg-sky-50/70">
    @php
        use App\Models\Content;$content = Content::key('hero_data');
        $heroData = json_decode($content->content_data);
    @endphp
    <div class="mx-auto grid max-w-6xl items-center gap-8 px-4 py-10 md:grid-cols-2 md:py-16">
        <!-- Left content -->
        <div class="space-y-4">
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

        <!-- Right hero image -->
        <div class="flex justify-end">
            <a
                href="{{ route('product.show', $content->product->slug) }}">
                <img src="{{ asset('storage/' . $content->product->thumbnail) }}"
                     alt="{{ $content->product->name }}"
                     class="object-cover rounded-3xl shadow-lg
                     hover:scale-105 hover:shadow-2xl hover:rotate-1 transition-all duration-500 ease-in-out
                     cursor-pointer">
            </a>
        </div>
    </div>
</section>
