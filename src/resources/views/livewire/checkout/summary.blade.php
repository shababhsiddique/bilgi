<div class="flex justify-end">
    <div class="w-full max-w-sm rounded-xl border border-slate-200 bg-white px-6 py-6 shadow-sm">

        <!-- Items list -->
        <div class="space-y-4 text-sm">
            <!-- We'll loop cart items here -->
            @forelse($items as $item)
                <div class="flex items-center justify-between gap-3 {{ !$loop->last ? 'border-b border-slate-200 pb-3' : '' }}">
                    <div class="flex items-center gap-3">
                        <p class="text-xs text-slate-600">
                            <strong>{{ $item['name'] }}</strong>
                            <br>
                            {{ $item['variant'] }} x {{ $item['qty'] }}
                        </p>
                    </div>
                    <p class="text-sm font-semibold text-slate-600 whitespace-nowrap">
                        {{ number_format($item['total'], 0) }} <span class="text-xs align-top">৳</span>
                    </p>
                </div>
            @empty
                <p class="text-xs text-slate-400">Your cart is empty.</p>
            @endforelse
        </div>

        <!-- Totals -->
        <dl class="mt-4 space-y-2 text-sm">
            <div class="flex justify-between">
                <dt class="text-slate-600">Delivery</dt>
                <dd class="text-slate-600">
                    {{ number_format($deliveryFee, 0) }} ৳
                </dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-600">Subtotal</dt>
                <dd class="text-slate-600">
                    {{ number_format($subtotal, 0) }} ৳
                </dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-600">Taxes</dt>
                <dd class="text-slate-600">
                    {{ number_format($tax, 2) }} ৳
                </dd>
            </div>
            @if($discountAmount > 0)
                <div class="flex justify-between">
                    <dt class="text-slate-600">Discount</dt>
                    <dd class="text-emerald-600">
                        -{{ number_format($discountAmount, 2) }} ৳
                    </dd>
                </div>
            @endif
            <div class="mt-3 flex items-center justify-between border-t border-slate-200 pt-3">
                <dt class="text-sm font-semibold text-slate-600">Total</dt>
                <dd class="text-sm font-semibold text-slate-600">
                    {{ number_format($total, 2) }} ৳
                </dd>
            </div>
        </dl>

        <!-- Discount -->
        <div class="mt-4">
            <div class="flex">
                <input
                    type="text"
                    placeholder="Discount code..."
                    class="w-full rounded-l-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                    wire:model.defer="discountCode"
                    name="discount_code"
                />
                <button
                    type="button"
                    class="rounded-r-md border border-l-0 border-slate-200 bg-slate-100 px-4 text-sm font-medium text-slate-600 hover:bg-slate-200"
                    wire:click="applyDiscount"
                >
                    Apply
                </button>
            </div>
            @if($discountAmount > 0)
                <p class="mt-2 text-xs text-emerald-600">
                    Discount applied: -{{ number_format($discountAmount, 2) }} ৳
                </p>
            @endif
            @error('discountCode')
            <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
            @enderror
            @if($discountError)
                <p class="mt-2 text-xs text-rose-500">{{ $discountError }}</p>
            @endif
        </div>

        <!-- Place Order Button (Regular Submit) -->
        <button
            type="submit"
            form="checkout-form"
            class="mt-5 w-full rounded-full bg-rose-500 py-2.5 text-sm font-semibold text-white hover:bg-rose-600 disabled:opacity-60 disabled:cursor-not-allowed"
            {{ count($items) === 0 ? 'disabled' : '' }}
        >
            Place Order →
        </button>

        <!-- Back link -->
        <div class="mt-6 flex items-center gap-3 text-xs text-slate-400">
            <div class="h-px flex-1 bg-slate-200"></div>
            <span>or</span>
            <div class="h-px flex-1 bg-slate-200"></div>
        </div>

        <a
            href="{{ route('shop') }}"
            class="mt-3 text-sm font-medium text-rose-500 hover:underline">
            ← Back to shop
        </a>
    </div>
</div>
