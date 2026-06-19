@extends('layouts.app')

@section('title', 'Shop STEAM & STEM Toys for Kids | Bilgi Bangladesh')
@section('meta_description', 'Browse Bilgi\'s full range of educational STEAM & STEM toys in Bangladesh — building blocks, science kits, robotics and learning toys for kids. Order online with delivery across Dhaka and Bangladesh.')
@section('meta_keywords', 'buy STEAM toys Bangladesh, STEM toys online, educational toys shop, kids toys Dhaka, learning toys, science kits, building blocks')
@section('robots', config('seo.indexable') ? 'index, follow' : 'noindex, nofollow')
@section('canonical', route('shop'))

@php
    $breadcrumbJsonLd = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Products', 'item' => route('shop')],
        ],
    ];
    $collectionJsonLd = [
        '@context'    => 'https://schema.org',
        '@type'       => 'CollectionPage',
        'name'        => 'Shop STEAM & STEM Toys',
        'description' => 'Educational STEAM & STEM toys for kids in Bangladesh.',
        'url'         => route('shop'),
        'inLanguage'  => 'en',
    ];
@endphp

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
        ['label' => 'Products'],
    ]"/>

    <div class="mx-auto max-w-6xl px-4 pt-2">
        <h1 class="text-2xl font-extrabold text-slate-900 sm:text-3xl">STEAM &amp; STEM Toys for Kids</h1>
        <p class="mt-2 max-w-2xl text-sm text-slate-600 sm:text-base">
            Discover Bilgi's collection of educational toys in Bangladesh — building blocks, science kits,
            robotics and hands-on learning toys that make kids love to explore. Fast delivery across Dhaka
            and all of Bangladesh.
        </p>
    </div>

    @livewire('shop.index')
@endsection
