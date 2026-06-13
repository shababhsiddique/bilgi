<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>bilgi, Education Toys</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
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
