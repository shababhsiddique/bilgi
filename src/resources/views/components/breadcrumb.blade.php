@props(['items' => []])

<div class="bg-sky-50/70 pt-5 pb-0">
    <div class="mx-auto max-w-6xl px-4">
        <nav class="text-sm" {{ $attributes }}>
            <ol class="flex items-center space-x-2 text-slate-500">
                @foreach($items as $index => $item)
                    <li class="flex items-center space-x-2">
                        @if($index > 0)
                            <span>/</span>
                        @endif

                        @if(isset($item['url']) && !$loop->last)
                            <a href="{{ $item['url'] }}" class="hover:text-rose-500">
                                {{ $item['label'] }}
                            </a>
                        @else
                            <span class="text-slate-700 font-semibold">
                        {{ $item['label'] }}
                    </span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    </div>
</div>

