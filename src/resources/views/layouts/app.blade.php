<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Bilgi STEAM Toys</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <!-- Favicons -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <meta name="robots" content="noindex, nofollow">
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
