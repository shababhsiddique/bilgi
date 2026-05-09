{{-- resources/views/pages/home.blade.php --}}
@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Products', 'url' => route('shop')],
        ['label' => 'Order Confirmation']
    ]"/>


    <div class="bg-sky-50/60 h-full py-10">
        <section class="container mx-auto px-4 space-y-6">
            Order Payment will start here now
        </section>
    </div>
@endsection
