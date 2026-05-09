@guest('customers')
    <div class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-1">
            <div>
                <label class="block text-sm font-medium text-gray-700" for="quickRegPhone">
                    Phone
                </label>
                <input
                    id="quickRegPhone"
                    type="tel"
                    wire:model.defer="quickRegPhone"
                    class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                >
                @error('quickRegPhone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700" for="quickRegPassword">
                    Password
                </label>
                <input
                    id="quickRegPassword"
                    type="password"
                    wire:model.defer="quickRegPassword"
                    class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                >
                @error('quickRegPassword')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <p class="text-sm text-gray-600">
            Your account will be created automatically when you place the order.
        </p>
    </div>
@endguest
