@extends('layouts.app')

@php
    // Bangladesh-focused SEO, driven by the category's own fields with fallbacks.
    $seoTitle = $category->meta_title
        ?: ($category->name . ' — STEAM & STEM Toys in Bangladesh | Bilgi');

    $seoDescription = \Illuminate\Support\Str::limit(
        trim(strip_tags($category->meta_description
            ?: ($category->description
                ?: 'Shop ' . $category->name . ' — educational STEAM & STEM toys for kids in Bangladesh. Order online with fast delivery across Dhaka and all of Bangladesh.'))),
        160
    );

    $seoKeywords  = $category->name . ' Bangladesh, ' . $category->name . ' toys, STEAM toys, STEM toys, educational toys Dhaka, Bilgi';
    $seoCanonical = route('category.show', $category->slug);

    $breadcrumbJsonLd = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Products', 'item' => route('shop')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $category->name, 'item' => $seoCanonical],
        ],
    ];

    $collectionJsonLd = [
        '@context'    => 'https://schema.org',
        '@type'       => 'CollectionPage',
        'name'        => $category->name,
        'description' => $seoDescription,
        'url'         => $seoCanonical,
        'inLanguage'  => 'en',
        'about'       => ['@type' => 'Thing', 'name' => $category->name],
        'areaServed'  => ['@type' => 'Country', 'name' => 'Bangladesh'],
    ];
@endphp

@section('title', $seoTitle)
@section('meta_description', $seoDescription)
@section('meta_keywords', $seoKeywords)
@section('robots', config('seo.indexable') ? 'index, follow' : 'noindex, nofollow')
@section('canonical', $seoCanonical)

@push('head')
    <script type="application/ld+json">
        {!! json_encode($breadcrumbJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode($collectionJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Products', 'url' => route('shop')],
        ['label' => $category->name]
    ]"/>

    <x-shop-hero
        eyebrow="STEAM & STEM"
        :title="$category->name"
        :description="$category->description ?: 'Educational ' . $category->name . ' toys for kids — delivered across Bangladesh.'"
    />

    @livewire('shop.index', ['categorySlug' => $category->slug])
@endsection
