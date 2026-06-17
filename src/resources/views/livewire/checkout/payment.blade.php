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

    {{-- Manual mobile-wallet instructions (shown only for bKash / Nagad / ...) --}}
    @if ($isManualWalletSelected)
        <div class="rounded-md border border-pink-300 bg-pink-50 p-4 text-sm">
            <div class="flex items-center gap-2 font-semibold text-pink-700">
                <span>{{ $walletName }} payment</span>
                <span class="rounded bg-pink-600 px-1.5 py-0.5 text-[10px] uppercase tracking-wide text-white">
                    {{ $walletAccountType }}
                </span>
            </div>

            <p class="mt-2 text-slate-600">
                Open your {{ $walletName }} app and <strong>Send Money</strong> of
                <strong class="text-pink-700">{{ number_format($orderTotal, 0) }} ৳</strong>
                to
                <strong class="text-pink-700">{{ $walletNumber }}</strong>,
                then copy the <strong>Transaction ID</strong> (TrxID) and paste it below.
            </p>

            <p class="mt-2 text-xs text-slate-500">
                Your order will be placed right away. We will verify the payment manually
                before processing — usually within a few hours.
            </p>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="payment_trx_id" class="mb-1 block text-xs font-medium text-slate-600">
                        {{ $walletName }} Transaction ID (TrxID)
                    </label>
                    <input
                        type="text"
                        id="payment_trx_id"
                        name="payment_trx_id"
                        wire:model.defer="trxId"
                        required
                        placeholder="e.g. 9H7A1B2C3D"
                        class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 uppercase outline-none focus:border-pink-500"
                    >
                    @error('payment_trx_id')
                        <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_sender_number" class="mb-1 block text-xs font-medium text-slate-600">
                        Your {{ $walletName }} number (sender)
                    </label>
                    <input
                        type="text"
                        id="payment_sender_number"
                        name="payment_sender_number"
                        wire:model.defer="senderNumber"
                        required
                        placeholder="01XXXXXXXXX"
                        inputmode="numeric"
                        class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-pink-500"
                    >
                    @error('payment_sender_number')
                        <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    @endif
</div>
