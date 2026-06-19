<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    @php
        // SEO defaults — focused on Bangladesh. Individual pages override these
        // via @section('title'), @section('meta_description'), etc.
        $defaultTitle       = 'Bilgi STEAM Toys — Educational STEM Toys in Bangladesh';
        $defaultDescription = 'Bilgi brings fun, educational STEAM & STEM toys for kids in Bangladesh. Shop building blocks, science kits and learning toys with fast delivery across Dhaka and all of Bangladesh.';
        $defaultKeywords    = 'STEAM toys Bangladesh, STEM toys, educational toys, kids toys, learning toys, building blocks, science kits, toys Dhaka, Bilgi';
        $defaultImage       = asset('images/bilgi.png');
    @endphp

    <title>@yield('title', $defaultTitle)</title>
    <meta name="description" content="@yield('meta_description', $defaultDescription)">
    <meta name="keywords" content="@yield('meta_keywords', $defaultKeywords)">
    {{-- Default robots is noindex so transactional/admin pages stay out of search;
         public shop pages override this with $publicRobots (gated until launch). --}}
    <meta name="robots" content="@yield('robots', 'noindex, nofollow')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    {{-- Single-locale site aimed at Bangladesh — reinforce regional targeting. --}}
    <link rel="alternate" hreflang="en-bd" href="@yield('canonical', url()->current())">
    <link rel="alternate" hreflang="x-default" href="@yield('canonical', url()->current())">

    <!-- Geo targeting: Bangladesh -->
    <meta name="geo.region" content="BD">
    <meta name="geo.placename" content="Bangladesh">
    <meta name="geo.country" content="Bangladesh">

    <!-- Open Graph -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="Bilgi STEAM Toys">
    <meta property="og:title" content="@yield('title', $defaultTitle)">
    <meta property="og:description" content="@yield('meta_description', $defaultDescription)">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:image" content="@yield('og_image', $defaultImage)">
    <meta property="og:locale" content="en_BD">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', $defaultTitle)">
    <meta name="twitter:description" content="@yield('meta_description', $defaultDescription)">
    <meta name="twitter:image" content="@yield('og_image', $defaultImage)">

    <!-- Favicons -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('head')

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/common.js'])
    @livewireStyles
</head>
<body class="bg-white text-slate-900 antialiased text-sm">
    <!-- Header -->
    <x-header/>
    <!-- PAGE CONTENT -->
    <main class="min-h-screen">
        @include('components.alerts')
        @yield('content')

        {{-- global variant modal manager listening for product events --}}
        <livewire:common.variant-modal-manager />

    </main>
    <!-- FOOTER -->
    <x-footer/>
    @livewireScripts
    @stack('scripts')
</body>
</html>
