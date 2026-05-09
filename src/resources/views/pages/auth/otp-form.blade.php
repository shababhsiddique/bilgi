
@extends('layouts.app')

@php
    $otpLength = (int) config('auth.one_time_password_length', 6);
    $otpCustomer = $customer ?? optional($order)->customer;
    $otpPhone = optional($otpCustomer)->phone;
    $otpContext = isset($order) && $order ? 'order' : 'account';
    $otpSubmitRoute = route('account.otp.verify');
    $otpResendRoute = route('account.otp.resend');
    $otpRedirect = $otpRedirect ?? [
        'route' => request()->input('redirect_route', 'account'),
        'params' => json_decode(request()->input('redirect_params', '[]'), true) ?? [],
    ];
    $otpRedirectRoute = $otpRedirect['route'] ?? 'account';
    $otpRedirectParams = $otpRedirect['params'] ?? [];
@endphp

@section('content')
    <section class="bg-gray-50 min-h-screen flex justify-center pt-12">
        <div class="w-full max-w-xl mx-auto px-4 py-10">
            <div class="bg-white shadow-md rounded-lg p-6 md:p-8 space-y-6">
                <div class="text-center space-y-1">
                    <p class="text-xs font-semibold uppercase tracking-widest text-emerald-600">
                        Phone verification
                    </p>
                    <h2 class="text-2xl font-semibold text-gray-900">
                        Verify Your Phone Number
                    </h2>
                    <p class="text-sm text-gray-500">
                        We sent a {{ $otpLength }}-digit code to <span class="font-semibold text-gray-700">{{ substr($otpPhone, 0, 3) . str_repeat('*', strlen($otpPhone) - 7) . substr($otpPhone, -4) }}</span>
                    </p>
                    @if(isset($order) && $order)
                        <p class="text-xs text-gray-400">
                            Order #{{ $order->order_number }}
                        </p>
                    @else
                        <p class="text-xs text-gray-400">
                            Use the code to verify your account phone number.
                        </p>
                    @endif
                </div>

                @if(session('success'))
                    <div class="rounded-md bg-green-50 px-4 py-3 text-sm text-green-700 border border-green-200">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="rounded-md bg-red-50 px-4 py-3 text-sm text-red-700 border border-red-200">
                        <ul class="list-disc list-inside space-y-1 text-left">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ $otpSubmitRoute }}" class="space-y-5">
                    @csrf
                    @if($otpCustomer)
                        <input type="hidden" name="customer_id" value="{{ $otpCustomer->id }}">
                    @endif
                    @if(isset($order) && $order)
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                    @endif
                    <input type="hidden" name="redirect_route" value="{{ $otpRedirectRoute }}">
                    <input type="hidden" name="redirect_params" value='@json($otpRedirectParams)'>
                    <div>
                        <label for="otp" class="block text-sm font-medium text-gray-700 mb-1">
                            Enter {{ $otpLength }}-digit code
                        </label>
                        <input
                            type="text"
                            id="otp"
                            name="otp"
                            maxlength="{{ $otpLength }}"
                            placeholder="{{ str_repeat('0', $otpLength) }}"
                            value="{{ old('otp') }}"
                            required
                            data-length="{{ $otpLength }}"
                            class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-center text-2xl tracking-[0.5em] text-gray-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('otp') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                        >
                        @error('otp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        id="otp-submit"
                        class="w-full inline-flex justify-center items-center rounded-md border border-transparent bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:cursor-not-allowed disabled:bg-gray-200 disabled:text-gray-400 disabled:border-gray-200"
                        disabled
                    >
                        Verify Code
                    </button>
                </form>

                <div class="text-center">
                    <form method="POST" action="{{ $otpResendRoute }}" class="inline-flex flex-col gap-2">
                        @csrf
                        @if($otpCustomer)
                            <input type="hidden" name="customer_id" value="{{ $otpCustomer->id }}">
                        @endif
                        @if(isset($order) && $order)
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                        @endif
                        <input type="hidden" name="redirect_route" value="{{ $otpRedirectRoute }}">
                        <input type="hidden" name="redirect_params" value='@json($otpRedirectParams)'>
                        <span class="text-xs text-gray-500">Didn't receive the code?</span>
                        <button
                            type="submit"
                            class="text-sm font-medium text-emerald-600 hover:text-emerald-700"
                        >
                            Resend verification code
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Auto-focus OTP input, allow only digits, and enable submit when complete
        document.addEventListener('DOMContentLoaded', function() {
            const otpInput = document.getElementById('otp');
            const submitButton = document.getElementById('otp-submit');
            const requiredLength = parseInt(otpInput.dataset.length, 10) || 6;
            otpInput.focus();

            otpInput.addEventListener('input', function(e) {
                // Only allow numbers
                this.value = this.value.replace(/[^0-9]/g, '');
                submitButton.disabled = this.value.length !== requiredLength;
            });
        });
    </script>
@endsection
