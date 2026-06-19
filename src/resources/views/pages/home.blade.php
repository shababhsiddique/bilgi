{{-- resources/views/pages/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Bilgi STEAM Toys — Educational STEM Toys in Bangladesh')
@section('meta_description', 'Bilgi is Bangladesh\'s home for fun, educational STEAM & STEM toys. Shop building blocks, science kits, robotics and learning toys for kids with fast delivery across Dhaka and all of Bangladesh.')
@section('meta_keywords', 'STEAM toys Bangladesh, STEM toys Bangladesh, educational toys, kids learning toys, science kits, building blocks, toys Dhaka, Bilgi')
@section('robots', config('seo.indexable') ? 'index, follow' : 'noindex, nofollow')
@section('canonical', route('home'))

@php
    $organizationJsonLd = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Organization',
        'name'        => 'Bilgi STEAM Toys',
        'url'         => route('home'),
        'logo'        => asset('images/bilgi.png'),
        'description' => 'Educational STEAM & STEM toys for kids in Bangladesh.',
        'areaServed'  => ['@type' => 'Country', 'name' => 'Bangladesh'],
        'address'     => ['@type' => 'PostalAddress', 'addressCountry' => 'BD'],
    ];

    // Contact point only when a public phone number is configured.
    if (config('seo.contact_phone')) {
        $organizationJsonLd['contactPoint'] = [
            '@type'             => 'ContactPoint',
            'telephone'         => config('seo.contact_phone'),
            'contactType'       => 'customer service',
            'areaServed'        => 'BD',
            'availableLanguage' => ['Bengali', 'English'],
        ];
    }

    // Organization `sameAs` shares one source of truth with the footer:
    // config/social.php. Keep these in sync by reading the same config.
    $sameAs = array_values(array_filter([
        config('social.facebook'),
        config('social.instagram'),
        config('social.whatsapp') ? 'https://wa.me/' . config('social.whatsapp') : null,
    ]));
    if (! empty($sameAs)) {
        $organizationJsonLd['sameAs'] = $sameAs;
    }

    $websiteJsonLd = [
        '@context'        => 'https://schema.org',
        '@type'           => 'WebSite',
        'name'            => 'Bilgi STEAM Toys',
        'url'             => route('home'),
        'inLanguage'      => 'en',
        // Enables the Google sitelinks search box pointing at the shop search.
        'potentialAction' => [
            '@type'       => 'SearchAction',
            'target'      => [
                '@type'       => 'EntryPoint',
                'urlTemplate' => route('shop') . '?search={search_term_string}',
            ],
            'query-input' => 'required name=search_term_string',
        ],
    ];
@endphp

@push('head')
    <script type="application/ld+json">
        {!! json_encode($organizationJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode($websiteJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <!-- HERO SECTION -->
    @include('components.home-hero')

    <!-- TOP PRODUCTS -->
    @livewire('home.top-products')

    <!-- BANNER: KIDS TOY COLLECTION -->
    <section class="bg-white pb-12 mx-auto max-w-6xl px-4">
        @livewire('shop.banner')
    </section>

    <!-- LATEST PRODUCTS -->
    @livewire('home.latest-products')

    <!-- PROMO GRID -->
    {{-- <section class="bg-white py-12">
        <div class="mx-auto grid max-w-6xl gap-6 px-4 md:grid-cols-3">
            <div class="rounded-3xl bg-rose-50 p-6">
                <p class="text-base font-semibold">Science</p>
                <p class="mt-1 text-sm text-slate-500">Up to 30% off</p>
            </div>
            <div class="rounded-3xl bg-sky-50 p-6">
                <p class="text-base font-semibold">Flat 15% OFF</p>
                <p class="mt-1 text-sm text-slate-500">On Kids’ Wear</p>
            </div>
            <div class="rounded-3xl bg-amber-50 p-6">
                <p class="text-base font-semibold">New Arrival</p>
                <p class="mt-1 text-sm text-slate-500">Up to 50% off</p>
            </div>
        </div>
    </section>--}}
@endsection
