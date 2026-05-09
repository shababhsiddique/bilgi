<div class="space-y-8">
    <!-- Title -->
    <h2 class="text-xl font-semibold text-slate-600 flex items-center gap-2">
        Payment method
        <span>🤖</span>
    </h2>

    {{-- Hidden input for selected address ID (for form submission) --}}
    <input type="hidden" name="payment_method_id" value="{{ $selectedPaymentMethodId }}">

    <!-- Payment options -->
    <div class="space-y-3 text-sm">
        @foreach ($paymentMethods as $method)
            <label
                wire:click="select({{ $method->id }})"
                class="flex cursor-pointer items-center justify-between rounded-md border
                       {{ $selectedPaymentMethodId === $method->id ? 'border-rose-500' : 'border-slate-200' }}
                       bg-white px-4 py-3 hover:border-rose-500"
            >
                <div class="flex items-center gap-3">
                    <!-- fake radio -->
                    <span
                        class="inline-flex h-4 w-4 items-center justify-center rounded-full
                               {{ $selectedPaymentMethodId === $method->id ? 'border-rose-500' : 'border-slate-400' }}"
                    >
                        @if ($selectedPaymentMethodId === $method->id)
                            <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                        @endif
                    </span>

                    <span class="text-slate-600">
                        {{ $method->name ?? 'Payment method' }}
                        @if (!empty($method->description))
                            <span class="block text-xs text-slate-400">
                                {{ $method->description }}
                            </span>
                        @endif
                    </span>
                </div>

                {{-- Right-side icon / badge (optional, from DB) --}}
                @if (!empty($method->icon_emoji))
                    <span
                        class="inline-flex h-6 w-9 items-center justify-center rounded bg-sky-100 text-slate-600"
                    >
                        {{ $method->icon_emoji }}
                    </span>
                @endif
            </label>
        @endforeach
    </div>
</div>
