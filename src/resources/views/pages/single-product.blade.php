@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Products', 'url' => route('shop')],
        ['label' => $slug]
    ]"/>

    @livewire('product.show', ['slug' => $slug])

    @if($related->isNotEmpty())
        <section class="bg-sky-50/70 py-8 sm:py-12">
            <div class="mx-auto max-w-6xl px-4">
                <div class="mb-6 text-center sm:mb-8">
                    <h2 class="text-xl font-bold mb-2 sm:text-2xl">You Might Also Like</h2>
                    <p class="text-sm text-slate-600 sm:text-base">Discover more amazing toys for your little ones</p>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:gap-6 md:grid-cols-4">
                    @foreach($related as $relatedProduct)
                        <livewire:common.product-card
                            :product="$relatedProduct"
                            :wire:key="'related-product-'.$relatedProduct->id"
                        />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
