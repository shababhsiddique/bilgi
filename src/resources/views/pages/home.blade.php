{{-- resources/views/pages/home.blade.php --}}
@extends('layouts.app')

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
