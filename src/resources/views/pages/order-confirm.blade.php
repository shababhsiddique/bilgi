{{-- resources/views/pages/order-confirm.blade.php --}}
@extends('layouts.app')

@section('content')
    @php($customer = Auth::guard('customers')->user())

    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Products', 'url' => route('shop')],
        ['label' => 'Order Confirmation'],
    ]"/>

    <div class="bg-sky-50/60 h-full py-10">
        <section class="container mx-auto px-4 max-w-3xl space-y-6">

            {{-- Success banner --}}
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-8 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100">
                    <svg class="h-9 w-9 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1 class="mt-4 text-2xl font-semibold text-gray-900">Thank you for your order!</h1>
                <p class="mt-2 text-sm text-gray-600">
                    Your order <span class="font-semibold text-gray-900">#{{ $order->order_number }}</span> has been placed successfully.
                    @if($customer)
                        A copy is available in your account.
                    @else
                        We’ll be in touch shortly to confirm the details.
                    @endif
                </p>

                <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('order.invoice', $order->id) }}" target="_blank" rel="noopener"
                       class="inline-flex items-center justify-center px-5 py-2.5 rounded-md text-sm font-medium text-white bg-rose-500 hover:bg-rose-600 transition-colors">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z"/>
                        </svg>
                        Print invoice
                    </a>
                    @if($customer)
                        <a href="{{ route('order.view', $order->order_number) }}"
                           class="inline-flex items-center justify-center px-5 py-2.5 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                            View order details
                        </a>
                    @endif
                    <a href="{{ route('shop') }}"
                       class="inline-flex items-center justify-center px-5 py-2.5 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                        Continue shopping
                    </a>
                </div>
            </div>

            {{-- Order status + meta --}}
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                    <h2 class="text-lg font-semibold text-gray-900">Order status</h2>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                            @if($order->status === 'completed') bg-green-100 text-green-800
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                            @elseif($order->status === 'refunded') bg-purple-100 text-purple-800
                            @elseif($order->status === 'failed') bg-red-100 text-red-800
                            @elseif($order->status === 'on_hold') bg-orange-100 text-orange-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                            @if($order->payment_status === 'paid') bg-green-100 text-green-800
                            @elseif($order->payment_status === 'pending' || $order->payment_status === 'processing') bg-yellow-100 text-yellow-800
                            @elseif($order->payment_status === 'unpaid') bg-red-100 text-red-800
                            @elseif($order->payment_status === 'refunded') bg-purple-100 text-purple-800
                            @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            Payment: {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Order date</p>
                        <p class="font-medium text-gray-900">
                            {{ $order->placed_at ? $order->placed_at->format('F d, Y g:i A') : $order->created_at->format('F d, Y g:i A') }}
                        </p>
                    </div>
                    @if($order->paymentMethod)
                        <div>
                            <p class="text-gray-500">Payment method</p>
                            <p class="font-medium text-gray-900">{{ $order->paymentMethod->name }}</p>
                        </div>
                    @endif
                    @if($order->payment_trx_id)
                        <div>
                            <p class="text-gray-500">Transaction ID</p>
                            <p class="font-medium text-gray-900">{{ $order->payment_trx_id }}</p>
                        </div>
                    @endif
                    @if($order->payment_sender_number)
                        <div>
                            <p class="text-gray-500">Paid from</p>
                            <p class="font-medium text-gray-900">{{ $order->payment_sender_number }}</p>
                        </div>
                    @endif
                </div>

                @if($order->paymentMethod && in_array($order->paymentMethod->code, config('payment.manual_wallets.codes', []), true))
                    <p class="mt-4 rounded-md bg-yellow-50 px-3 py-2 text-xs text-yellow-800">
                        Your {{ $order->paymentMethod->name }} payment is being verified. We'll start
                        processing your order once the payment is confirmed.
                    </p>
                @endif
            </div>

            {{-- Order items + totals --}}
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order summary</h2>
                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <div class="flex items-start justify-between gap-4 py-3">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $item->product_name }}</p>
                                @if($item->variant_name)
                                    <p class="text-xs text-gray-500">{{ $item->variant_name }}</p>
                                @endif
                                <p class="text-xs text-gray-500 mt-0.5">Qty {{ $item->quantity }} × ৳{{ number_format($item->unit_price, 2) }}</p>
                            </div>
                            <div class="text-sm font-medium text-gray-900 whitespace-nowrap">
                                ৳{{ number_format($item->line_total, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-200 mt-4 pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium text-gray-900">৳{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Discount</span>
                            <span class="font-medium text-emerald-600">-৳{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Shipping</span>
                        <span class="font-medium text-gray-900">৳{{ number_format($order->shipping_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
                        <span class="text-base font-semibold text-gray-900">Total</span>
                        <span class="text-base font-semibold text-gray-900">৳{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Delivery address --}}
            @if($order->shippingAddress)
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3">Delivery address</h2>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p class="font-medium text-gray-900">{{ $order->shippingAddress->name }}</p>
                        @if($order->shippingAddress->phone)
                            <p>{{ $order->shippingAddress->phone }}</p>
                        @endif
                        <p>{{ $order->shippingAddress->address }}</p>
                        <p>{{ collect([$order->shippingAddress->city, $order->shippingAddress->state, $order->shippingAddress->postcode])->filter()->implode(', ') }}</p>
                        @if($order->shippingAddress->country)
                            <p>{{ $order->shippingAddress->country }}</p>
                        @endif
                    </div>
                </div>
            @endif

        </section>
    </div>
@endsection
