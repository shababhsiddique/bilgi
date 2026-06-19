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

    <div class="bg-sky-50/70">
        <div class="mx-auto max-w-6xl px-4 pt-4 pb-1">
            <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">STEAM &amp; STEM Toys for Kids</h1>
            <p class="mt-1.5 max-w-2xl text-xs leading-relaxed text-slate-500 sm:text-sm">
                Educational toys for curious minds in Bangladesh — building blocks, science kits, robotics
                and hands-on learning toys, with fast delivery across Dhaka and beyond.
            </p>
        </div>
    </div>

    @livewire('shop.index')
@endsection
