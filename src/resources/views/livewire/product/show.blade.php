<div>
    <!-- PRODUCT DETAIL SECTION -->
    <section class="bg-sky-50/70">
        <div class="mx-auto max-w-6xl px-4 py-5">

            <div class="grid gap-8 mt-2 md:grid-cols-2">
                <!-- Product Images Gallery -->
                <div class="space-y-4">
                    <!-- Main product image -->
                    @if(count($galleryImages) > 0 && !empty($galleryImages[$selectedImageIndex]['url']))
                        <img
                            src="{{ $galleryImages[$selectedImageIndex]['url'] }}"
                            alt="{{ $galleryImages[$selectedImageIndex]['alt'] }}"
                            class="w-full aspect-square rounded-3xl object-cover shadow-sm"
                        >
                    @else
                        <div
                            class="w-full aspect-square rounded-3xl bg-gradient-to-br from-yellow-100 to-rose-100 shadow-inner flex items-center justify-center text-lg">
                            {{$product->name}}
                        </div>
                    @endif

                    <!-- Thumbnail images gallery -->
                    <div class="flex gap-3 justify-center flex-wrap">
                        @if(count($galleryImages) > 1)
                            @foreach($galleryImages as $index => $image)
                                <button
                                    wire:click="selectImage({{ $index }})"
                                    class="relative h-16 w-16 rounded-lg border-2 {{ $selectedImageIndex === $index ? 'border-sky-500 ring-2 ring-sky-200' : 'border-slate-200 hover:border-sky-300' }} shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group"
                                    title="Gallery Image {{ $index + 1 }}">
                                    @if(!empty($image['url']))
                                        <img
                                            src="{{ $image['url'] }}"
                                            alt="{{ $image['alt'] }}"
                                            class="h-full w-full object-cover"
                                        >
                                    @else
                                        <div
                                            class="h-full w-full bg-gradient-to-br from-yellow-100 to-rose-100 flex items-center justify-center text-xs text-slate-500">
                                            {{ substr($product->name, 0, 3) }}
                                        </div>
                                    @endif
                                </button>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Product Info -->
                <div class="space-y-6">
                    <!-- Product title -->
                    <div>
                        <h1 class="text-3xl text-slate-900">
                            <span
                                class="text-sm text-sky-500 font-semibold">{{ $product->brand ?? 'bilgi' }}</span><br/>
                            <span class="font-extrabold">{{ $product->name ?? 'Building Blocks Set' }}</span>
                            <!-- Product badges -->
                            @if($product->ribbon_text)
                                @php [$bg, $text] = $product->ribbon_classes; @endphp
                                <span
                                    class="rounded-full px-3 py-1 text-xs font-regular {{ $bg }} {{ $text }}">{{ strtoupper($product->ribbon_text) }}</span>
                            @endif
                        </h1>
                        <p class="mt-2 text-sm text-slate-500">SKU: {{ $selectedVariant->sku ?? 'TOY-001-BBK' }}</p>
                    </div>

                    <!-- Price -->
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <span
                                class="text-3xl font-bold text-rose-500">${{ number_format($selectedVariant->sales_price ?? 49.99, 2) }}</span>
                            @if($product->compare_price && $product->compare_price > $selectedVariant->sales_price)
                                <span
                                    class="text-lg text-slate-400 line-through">${{ number_format($product->compare_price, 2) }}</span>
                                <span class="rounded-full bg-rose-100 px-2 py-1 text-xs font-semibold text-rose-600">
                                    {{ round((($product->compare_price - $selectedVariant->sales_price) / $product->compare_price) * 100) }}% OFF
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-emerald-600 font-semibold">
                            @if($selectedVariant->stock && $selectedVariant->stock > 0)
                                ✓ In Stock - Ready to Ship ({{ $selectedVariant->stock }} available)
                            @else
                                ✗ Out of Stock
                            @endif
                        </p>
                    </div>

                    <!-- Product description -->
                    <div>
                        <p class="text-base text-slate-600">
                            {{ $product->description}}
                        </p>
                    </div>

                    <!-- Product options -->
                    <div class="space-y-4">
                        <!-- Variant selection -->
                        @if($availableVariants->count() > 1)
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Variants
                                    @if($selectedVariant->name)
                                        <span class="text-slate-500 font-normal">({{ $selectedVariant->name }})</span>
                                    @endif
                                </label>
                                <div class="flex gap-3 flex-wrap">
                                    @foreach($availableVariants as $variant)
                                        <button
                                            wire:click="selectVariant({{ $variant->id }})"
                                            class="relative h-16 w-16 rounded-lg border-2 {{ $selectedVariant->id === $variant->id ? 'border-sky-500 ring-2 ring-sky-200' : 'border-slate-200 hover:border-sky-300' }} shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group"
                                            title="{{ $variant->name }}"
                                        >
                                            @if($variant->image)
                                                <img
                                                    src="{{ asset("storage/{$variant->image}") }}"
                                                    alt="{{ $variant->name }}"
                                                    class="h-full w-full object-cover"
                                                >
                                            @else
                                                <!-- Fallback to product thumbnail or placeholder -->
                                                @if($product->thumbnail)
                                                    <img
                                                        src="{{ asset("storage/{$product->thumbnail}") }}"
                                                        alt="{{ $variant->name }}"
                                                        class="h-full w-full object-cover opacity-75"
                                                    >
                                                @else
                                                    <div
                                                        class="h-full w-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-xs text-slate-500">
                                                        {{ substr($variant->name ?? 'VAR', 0, 3) }}
                                                    </div>
                                                @endif
                                            @endif

                                            @if($selectedVariant->id === $variant->id)
                                                <div
                                                    class="absolute inset-0 bg-emerald-500/20 flex items-center justify-center">
                                                    <div
                                                        class="h-6 w-6 rounded-full bg-emerald-500 flex items-center justify-center">
                                                        <svg class="h-3 w-3 text-white" fill="currentColor"
                                                             viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                  clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Quantity -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Quantity</label>
                            <div class="flex items-center gap-3">
                                <button
                                    wire:click="decreaseQuantity"
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200">
                                    <span class="text-lg font-semibold">−</span>
                                </button>
                                <span
                                    class="flex h-10 w-16 items-center justify-center rounded-lg bg-white border text-center font-semibold">
                                    {{ $quantity }}
                                </span>
                                <button
                                    wire:click="increaseQuantity"
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200">
                                    <span class="text-lg font-semibold">+</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex gap-4">
                        <button
                            wire:click="buyNow"
                            class="flex-1 rounded-full bg-rose-500 px-8 py-3 text-lg font-semibold text-white shadow-md hover:bg-rose-600 transition-colors">
                            Buy Now
                        </button>
                        <button
                            wire:click="addToCart"
                            class="flex-1 rounded-full bg-amber-500 px-8 py-3 text-lg font-semibold text-white shadow-md hover:bg-amber-600 transition-colors">
                            Add to Cart
                        </button>
                    </div>

                    <!-- Product features -->
                    <div class="grid grid-cols-2 gap-4 pt-4">
                        <div class="flex items-center gap-2">
                            <div class="h-5 w-5 rounded-full bg-emerald-400"></div>
                            <span class="text-sm text-slate-600">Safe Materials</span>
                        </div>
                        @if($product->age_group)
                            <div class="flex items-center gap-2">
                                <div class="h-5 w-5 rounded-full bg-sky-400"></div>
                                <span class="text-sm text-slate-600">Ages {{$product->age_group}}</span>
                            </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <div class="h-5 w-5 rounded-full bg-rose-400"></div>
                            <span class="text-sm text-slate-600">Educational</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="h-5 w-5 rounded-full bg-amber-400"></div>
                            <span class="text-sm text-slate-600">Creative Play</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PRODUCT DETAILS TABS -->
    <section class="bg-white py-12">
        <div class="mx-auto max-w-6xl px-4">
            <!-- Tab navigation -->
            <div class="mb-8 border-b border-slate-200">
                <nav class="flex gap-8">
                    <button
                        wire:click="switchTab('description')"
                        class="pb-4 font-semibold {{ $activeTab === 'description' ? 'border-b-2 border-rose-500 text-rose-500' : 'text-slate-500 hover:text-slate-700' }}">
                        Description
                    </button>
                    <button
                        wire:click="switchTab('reviews')"
                        class="pb-4 font-semibold {{ $activeTab === 'reviews' ? 'border-b-2 border-rose-500 text-rose-500' : 'text-slate-500 hover:text-slate-700' }}">
                        Reviews (24)
                    </button>
                    <button
                        wire:click="switchTab('shipping')"
                        class="pb-4 font-semibold {{ $activeTab === 'shipping' ? 'border-b-2 border-rose-500 text-rose-500' : 'text-slate-500 hover:text-slate-700' }}">
                        Shipping
                    </button>
                </nav>
            </div>

            <!-- Tab content -->
            <div class="space-y-6">
                @if($activeTab === 'description')
                    <div>
                        <h3 class="text-xl font-bold mb-4">Product Description</h3>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            {{ $product->description}}
                        </p>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        {{-- Dynamic Product Attributes --}}
                        @if($selectedVariant && $selectedVariant->productAttributes->where('visible', true)->isNotEmpty())
                            @foreach($selectedVariant->productAttributes->where('visible', true) as $attribute)
                                <div class="bg-slate-50 p-4 rounded-lg">
                                    <strong>{{ $attribute->attribute_name }}:</strong> {{ $attribute->attribute_value }}
                                </div>
                            @endforeach
                        @endif
                    </div>


                    <!-- Long description -->
                    <div class="text-slate-600 leading-relaxed mb-4">
                        {!! $product->long_description !!}
                    </div>

                @elseif($activeTab === 'reviews')
                    <div>
                        <h3 class="text-xl font-bold mb-4">Customer Reviews</h3>
                        <p class="text-slate-600">Reviews section coming soon...</p>
                    </div>
                @elseif($activeTab === 'shipping')
                    <div>
                        <h3 class="text-xl font-bold mb-4">Shipping Information</h3>
                        <div class="space-y-4">
                            <div class="bg-slate-50 p-4 rounded-lg">
                                <strong>Inside Dhaka:</strong> 2-3 business days (BDT{{config('address.shipping_costs.Dhaka')}})
                            </div>
                            <div class="bg-slate-50 p-4 rounded-lg">
                                <strong>Outside Dhaka:</strong> 5-7 business days (BDT{{config('address.shipping_costs.Khulna')}})
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
