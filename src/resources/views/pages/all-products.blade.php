@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Products'],
    ]"/>
    @livewire('shop.index')
@endsection
