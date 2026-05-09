{{-- resources/views/pages/order-view.blade.php --}}
@extends('layouts.app')

@section('content')
    @php($customer = $customer ?? Auth::guard('customers')->user())

    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Account', 'url' => route('account')],
        ['label' => 'Order Details'],
    ]"/>

    <div class="bg-sky-50/60 h-full py-10">
        <section class="container mx-auto px-4 space-y-6">
            {{-- Order Header --}}
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">Order Details</h1>
                        <p class="text-sm text-gray-600 mt-1">Order #{{ $order->order_number }}</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
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
                            @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->payment_status === 'unpaid') bg-red-100 text-red-800
                            @elseif($order->payment_status === 'refunded') bg-purple-100 text-purple-800
                            @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            Payment: {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Order Date</p>
                        <p class="font-medium text-gray-900">
                            {{ $order->placed_at ? $order->placed_at->format('F d, Y g:i A') : $order->created_at->format('F d, Y g:i A') }}
                        </p>
                    </div>
                    @if($order->paymentMethod)
                        <div>
                            <p class="text-gray-500">Payment Method</p>
                            <p class="font-medium text-gray-900">{{ $order->paymentMethod->name }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Order Items --}}
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h2>
                @if($order->items->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Product
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Variant
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quantity
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Unit Price
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $item->product_name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                {{ $item->variant_name ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $item->quantity }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                ৳{{ number_format($item->unit_price, 2) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                ৳{{ number_format($item->line_total, 2) }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No items found in this order.</p>
                @endif
            </div>

            {{-- Order Summary --}}
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium text-gray-900">৳{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Discount</span>
                            <span class="font-medium text-green-600">-৳{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Shipping</span>
                        <span class="font-medium text-gray-900">৳{{ number_format($order->shipping_amount, 2) }}</span>
                    </div>
                    <div class="border-t border-gray-200 pt-2 mt-2">
                        <div class="flex justify-between">
                            <span class="text-base font-semibold text-gray-900">Total</span>
                            <span class="text-base font-semibold text-gray-900">৳{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            @if($order->shippingAddress)
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Shipping Address</h2>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p class="font-medium text-gray-900">{{ $order->shippingAddress->name }}</p>
                        @if($order->shippingAddress->phone)
                            <p>{{ $order->shippingAddress->phone }}</p>
                        @endif
                        <p>{{ $order->shippingAddress->address }}</p>
                        <p>
                            @if($order->shippingAddress->city)
                                {{ $order->shippingAddress->city }},
                            @endif
                            @if($order->shippingAddress->state)
                                {{ $order->shippingAddress->state }}
                            @endif
                            @if($order->shippingAddress->postcode)
                                {{ $order->shippingAddress->postcode }}
                            @endif
                        </p>
                        @if($order->shippingAddress->country)
                            <p>{{ $order->shippingAddress->country }}</p>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Billing Address --}}
            @if($order->billingAddress && $order->billing_address_id !== $order->shipping_address_id)
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Billing Address</h2>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p class="font-medium text-gray-900">{{ $order->billingAddress->name }}</p>
                        @if($order->billingAddress->phone)
                            <p>{{ $order->billingAddress->phone }}</p>
                        @endif
                        <p>{{ $order->billingAddress->address }}</p>
                        <p>
                            @if($order->billingAddress->city)
                                {{ $order->billingAddress->city }},
                            @endif
                            @if($order->billingAddress->state)
                                {{ $order->billingAddress->state }}
                            @endif
                            @if($order->billingAddress->postcode)
                                {{ $order->billingAddress->postcode }}
                            @endif
                        </p>
                        @if($order->billingAddress->country)
                            <p>{{ $order->billingAddress->country }}</p>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 justify-start">
                <form method="POST" action="{{ route('order.reorder', $order->order_number) }}">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors"
                    >
                        Re-order
                    </button>
                </form>
                <a
                    href="{{ route('account') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                >
                    Back to Account
                </a>
            </div>
        </section>
    </div>
@endsection
