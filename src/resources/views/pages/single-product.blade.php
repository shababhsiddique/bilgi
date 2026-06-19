@extends('layouts.app')

@php
    // Build SEO values from the product's own meta fields, with sensible fallbacks.
    $lowestVariant = $product->lowest_price_variant;
    $seoPrice      = $lowestVariant?->sales_price ?? $product->sales_price ?? 0;
    $seoInStock    = (int) ($lowestVariant?->stock ?? $product->stock ?? 0) > 0;

    $seoTitle = $product->meta_title ?: ($product->name . ' | Bilgi STEAM Toys Bangladesh');

    $seoDescription = \Illuminate\Support\Str::limit(
        trim(strip_tags($product->meta_description ?: ($product->description ?? ''))),
        160
    );
    if ($seoDescription === '') {
        $seoDescription = 'Buy ' . $product->name . ' — an educational STEAM toy from Bilgi, delivered across Bangladesh.';
    }

    $seoKeywords = $product->meta_keywords
        ?: ($product->name . ', STEAM toys Bangladesh, STEM toys, educational toys, kids toys');

    $seoImage     = $product->og_image_url;
    $seoCanonical = route('product.show', $product->slug);

    // Offers stay valid until the end of the current year (Google requires a date).
    $priceValidUntil = now()->endOfYear()->toDateString();

    $productJsonLd = [
        '@context'      => 'https://schema.org',
        '@type'         => 'Product',
        '@id'           => $seoCanonical . '#product',
        'name'          => $product->name,
        'description'   => $seoDescription,
        'image'         => $seoImage,
        'sku'           => $product->sku,
        'brand'         => ['@type' => 'Brand', 'name' => $product->brand ?: 'Bilgi'],
        'offers'        => [
            '@type'           => 'Offer',
            'url'             => $seoCanonical,
            'priceCurrency'   => 'BDT',
            'price'           => number_format((float) $seoPrice, 2, '.', ''),
            'priceValidUntil' => $priceValidUntil,
            'itemCondition'   => 'https://schema.org/NewCondition',
            'availability'    => $seoInStock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'areaServed'      => ['@type' => 'Country', 'name' => 'Bangladesh'],
            'seller'          => ['@type' => 'Organization', 'name' => 'Bilgi STEAM Toys'],
        ],
    ];

    // gtin/mpn help Google match the product; only include when present.
    if (! empty($product->barcode)) {
        $productJsonLd['gtin'] = $product->barcode;
    }
    if (! empty($product->sku)) {
        $productJsonLd['mpn'] = $product->sku;
    }

    $breadcrumbJsonLd = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Products', 'item' => route('shop')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $product->name, 'item' => $seoCanonical],
        ],
    ];
@endphp

@section('title', $seoTitle)
@section('meta_description', $seoDescription)
@section('meta_keywords', $seoKeywords)
@section('robots', config('seo.indexable') ? 'index, follow' : 'noindex, nofollow')
@section('canonical', $seoCanonical)
@section('og_type', 'product')
@section('og_image', $seoImage)

@push('head')
    <meta property="product:price:amount" content="{{ number_format((float) $seoPrice, 2, '.', '') }}">
    <meta property="product:price:currency" content="BDT">
    <meta property="product:availability" content="{{ $seoInStock ? 'in stock' : 'out of stock' }}">
    <script type="application/ld+json">
        {!! json_encode($productJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode($breadcrumbJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Products', 'url' => route('shop')],
        ['label' => $product->name]
    ]"/>

    @livewire('product.show', ['slug' => $slug])

    @if($related->isNotEmpty())
        <section class="bg-sky-50/70 py-8 sm:py-12">
            <div class="mx-auto max-w-6xl px-4">
                <div class="mb-6 text-center sm:mb-8">
                    <h2 class="text-xl font-bold mb-2 sm:text-2xl">You Might Also Like</h2>
                    <p class="text-sm text-slate-600 sm:text-base">Discover more amazing toys for your little ones</p>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:gap-6 md:grid-cols-4">
                    @foreach($related as $relatedProduct)
                        <livewire:common.product-card
                            :product="$relatedProduct"
                            :wire:key="'related-product-'.$relatedProduct->id"
                        />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
