{{-- resources/views/pages/account.blade.php --}}
@extends('layouts.app')

@section('content')
    @php($customer = $customer ?? Auth::guard('customers')->user())

    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Account'],
    ]"/>

    <div class="bg-sky-50/60 h-full py-10">
        <section class="container mx-auto px-4 space-y-6">
            @if($customer && is_null($customer->phone_verified_at))
                <div class="rounded-lg border border-amber-200 bg-amber-50 px-6 py-5 shadow-sm">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-amber-700 uppercase tracking-wide">Action required</p>
                            <h2 class="text-lg font-semibold text-gray-900 mt-1">Verify your phone number</h2>
                            <p class="text-sm text-gray-600 mt-1">
                                We sent a one-time password to <span class="font-medium">{{ $customer->phone }}</span>. Enter it to unlock your account.
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a
                                href="{{ route('account.otp.show') }}"
                                class="inline-flex justify-center rounded-md border border-transparent bg-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                            >
                                Verify now
                            </a>
                            <form method="POST" action="{{ route('account.otp.resend') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="inline-flex justify-center rounded-md border border-emerald-200 bg-white px-5 py-2 text-sm font-semibold text-emerald-700 shadow-sm hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                                >
                                    Resend code
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if($customer)
                {{-- Page Header --}}
                <div class="mb-8">
                    <h1 class="text-3xl font-semibold text-gray-900">
                        Hello, {{ $customer->full_name }}
                    </h1>
                    @if(!empty($customer->email))
                        <p class="text-gray-600 mt-2">{{ $customer->email }}</p>
                    @endif
                </div>

                {{-- Account Layout: Sidebar + Content --}}
                <div class="flex flex-col lg:flex-row gap-8">
                    {{-- Left Sidebar Menu --}}
                    <aside class="lg:w-64 flex-shrink-0">
                        <nav class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                            <ul class="divide-y divide-gray-200">
                                <li>
                                    <a
                                        href="{{ route('account', ['section' => 'orders']) }}"
                                        class="block px-6 py-4 text-sm font-medium transition-colors {{ request('section', 'orders') === 'orders' ? 'bg-emerald-50 text-emerald-700 border-l-4 border-emerald-600' : 'text-gray-900 hover:bg-gray-50' }}"
                                    >
                                        My orders
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="{{ route('account', ['section' => 'addressbook']) }}"
                                        class="block px-6 py-4 text-sm font-medium transition-colors {{ request('section') === 'addressbook' ? 'bg-emerald-50 text-emerald-700 border-l-4 border-emerald-600' : 'text-gray-900 hover:bg-gray-50' }}"
                                    >
                                        Address book
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="{{ route('account', ['section' => 'account-info']) }}"
                                        class="block px-6 py-4 text-sm font-medium transition-colors {{ request('section') === 'account-info' ? 'bg-emerald-50 text-emerald-700 border-l-4 border-emerald-600' : 'text-gray-900 hover:bg-gray-50' }}"
                                    >
                                        Account Info
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </aside>

                    {{-- Right Content Area --}}
                    <div class="flex-1 min-w-0">
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                            @if(request('section', 'orders') === 'orders')
                                @livewire('account.my-orders')
                            @elseif(request('section') === 'addressbook')
                                @livewire('account.addressbook')
                            @elseif(request('section') === 'account-info')
                                @livewire('account.account-info')
                            @else
                                @livewire('account.my-orders')
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection
