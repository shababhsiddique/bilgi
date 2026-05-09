<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Tailwind + Livewire Test</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Vite / your compiled assets that include Tailwind --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="min-h-screen bg-slate-100 antialiased">
<div class="min-h-screen flex flex-col items-center justify-center px-4">
    <h1 class="mb-6 text-3xl font-extrabold tracking-tight text-slate-900">
        Welcome – Tailwind & Livewire Test
    </h1>

    @livewire('test-tailwind')

    <p class="mt-8 text-xs text-slate-500">
        If you see Tailwind styling and the Livewire input reacts instantly,
        everything is working correctly.
    </p>
</div>

@livewireScripts
</body>
</html>
