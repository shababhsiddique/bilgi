@extends('layouts.app')

@section('content')
    <section class="bg-sky-50/50"  id="checkout-page">

        <form action="{{ route('order.confirm.submit') }}" method="POST" id="checkout-form">
            @csrf

            <div class="mx-auto max-w-6xl px-6 py-10 grid gap-10 lg:grid-cols-[minmax(0,2.2fr)_minmax(320px,1fr)]">

                <!-- LEFT: all steps (cart + delivery + payment) -->
                <div class="space-y-10">

                    {{-- Section 1: Livewire Cart Component --}}
                    @livewire('checkout.cart')

                    <!-- Section 2: Delivery method & address -->
                    @livewire('checkout.delivery')

                    {{-- Section 3: Livewire payment method Component --}}
                    @livewire('checkout.payment')

                    <div class="space-y-2">
                        <label class="mb-1 block text-xs font-medium text-slate-600">
                            Order notes
                            <span class="text-slate-400">(optional)</span>
                        </label>
                        <textarea
                            rows="3"
                            id="orderNotes"
                            name="notes"
                            class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                            placeholder="Any special instructions for delivery..."
                        ></textarea>
                        @error('notes')
                        <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- RIGHT: unified summary card with submit button --}}
                @livewire('checkout.summary')

            </div>
        </form>
    </section>

@endsection
