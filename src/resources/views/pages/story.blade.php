{{-- resources/views/pages/story.blade.php --}}
@extends('layouts.app')

@section('title', 'Our Story — Why We Built Bilgi STEAM Toys | Bangladesh')
@section('meta_description', 'Why we started WithBilgi: to get kids in Bangladesh off their phones and back into creative, hands-on STEAM & STEM play. Learn about our mission and the educational toys we deliver across Bangladesh.')
@section('meta_keywords', 'about Bilgi, WithBilgi story, STEAM toys Bangladesh, educational toys mission, screen-free play Bangladesh')
@section('robots', config('seo.indexable') ? 'index, follow' : 'noindex, nofollow')
@section('canonical', route('story'))

@php
    $aboutJsonLd = [
        '@context'    => 'https://schema.org',
        '@type'       => 'AboutPage',
        'name'        => 'Our Story — Bilgi STEAM Toys',
        'url'         => route('story'),
        'inLanguage'  => 'en',
        'description' => 'The story behind WithBilgi — helping kids in Bangladesh learn through hands-on STEAM & STEM play.',
        'about'       => [
            '@type'      => 'Organization',
            'name'       => 'Bilgi STEAM Toys',
            'url'        => route('home'),
            'logo'       => asset('images/bilgi.png'),
            'areaServed' => ['@type' => 'Country', 'name' => 'Bangladesh'],
        ],
    ];
@endphp

@push('head')
    <script type="application/ld+json">
        {!! json_encode($aboutJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Our Story'],
    ]" />

    <!-- HERO -->
    <section class="bg-sky-50/70">
        <div class="mx-auto max-w-6xl px-4 py-12 text-center">
            <h1 class="text-3xl font-bold text-slate-800 sm:text-4xl">Our Story</h1>
            <p class="mx-auto mt-3 max-w-2xl text-slate-500">
                Why we started WithBilgi — to get kids off their phones and back into creative, active,
                hands-on play.
            </p>
        </div>
    </section>

    <!-- INTRO -->
    <section class="bg-white">
        <div class="mx-auto max-w-3xl px-4 py-12 space-y-5 text-slate-600 leading-relaxed">
            <p>
                WithBilgi started in 2026 with one simple worry: kids were spending more and more time
                glued to phones and screens, and less time actually playing. We wanted to change that. So
                we set out to find toys that pull children back into the real world — toys that spark
                creativity, get them moving, and bring them together.
            </p>
            <p>
                Every product we stock is chosen to do something a screen can't. We look for toys that
                boost imagination and creative thinking, encourage active, physical play, and get kids
                building, solving, and working together — instead of scrolling alone.
            </p>
            <p>
                We believe play is serious business. Every product on our shelves is picked by real
                people who ask one simple question: <em>does this get kids off their phones and back into
                playing?</em> If the answer is no, it doesn't make the cut.
            </p>
        </div>
    </section>

    <!-- VALUES -->
    <section class="bg-sky-50/70">
        <div class="mx-auto max-w-6xl px-4 py-12">
            <h2 class="text-center text-2xl font-bold text-slate-800">What We Stand For</h2>
            <div class="mt-8 grid gap-6 md:grid-cols-3">
                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <p class="text-lg font-semibold text-slate-800">Spark Creativity</p>
                    <p class="mt-2 text-sm text-slate-500">
                        We choose toys that fire up imagination and creative thinking — open-ended play that
                        lets kids invent their own fun.
                    </p>
                </div>
                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <p class="text-lg font-semibold text-slate-800">Get Moving</p>
                    <p class="mt-2 text-sm text-slate-500">
                        Active, physical play that gets kids up, hands-on, and away from the couch — because
                        the best play happens off the screen.
                    </p>
                </div>
                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <p class="text-lg font-semibold text-slate-800">Better Together</p>
                    <p class="mt-2 text-sm text-slate-500">
                        Toys made for teaming up — building, solving, and laughing together instead of
                        scrolling alone.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="bg-white">
        <div class="mx-auto max-w-3xl px-4 py-14 text-center">
            <h2 class="text-2xl font-bold text-slate-800">Come Play With Us</h2>
            <p class="mx-auto mt-3 max-w-xl text-slate-500">
                Thanks for being part of our story. Browse our shelves and find something that makes someone
                smile today.
            </p>
            <a href="{{ route('shop') }}"
               class="mt-6 inline-block rounded-full bg-rose-500 px-8 py-3 font-semibold text-white hover:bg-rose-600">
                Shop Now
            </a>
        </div>
    </section>
@endsection
