@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Products', 'url' => route('shop')],
        ['label' => $slug]
    ]"/>

    @livewire('product.show', ['slug' => $slug])

    <section class="bg-sky-50/70 py-12">
        <div class="mx-auto max-w-6xl px-4">
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-bold mb-2">You Might Also Like</h2>
                <p class="text-slate-600">Discover more amazing toys for your little ones</p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-4">
                <!-- Related product cards -->
                <div class="rounded-3xl bg-white p-6 shadow-md hover:shadow-lg transition-shadow">
                    <div class="h-32 w-full rounded-2xl bg-rose-100 mb-4"></div>
                    <h3 class="font-semibold mb-1">Puzzle Set</h3>
                    <p class="text-rose-500 font-bold">$29.99</p>
                </div>
                <div class="rounded-3xl bg-white p-6 shadow-md hover:shadow-lg transition-shadow">
                    <div class="h-32 w-full rounded-2xl bg-sky-100 mb-4"></div>
                    <h3 class="font-semibold mb-1">Learning Blocks</h3>
                    <p class="text-rose-500 font-bold">$39.99</p>
                </div>
                <div class="rounded-3xl bg-white p-6 shadow-md hover:shadow-lg transition-shadow">
                    <div class="h-32 w-full rounded-2xl bg-amber-100 mb-4"></div>
                    <h3 class="font-semibold mb-1">Shape Sorter</h3>
                    <p class="text-rose-500 font-bold">$24.99</p>
                </div>
                <div class="rounded-3xl bg-white p-6 shadow-md hover:shadow-lg transition-shadow">
                    <div class="h-32 w-full rounded-2xl bg-emerald-100 mb-4"></div>
                    <h3 class="font-semibold mb-1">Stacking Rings</h3>
                    <p class="text-rose-500 font-bold">$19.99</p>
                </div>
            </div>
        </div>
    </section>
@endsection
