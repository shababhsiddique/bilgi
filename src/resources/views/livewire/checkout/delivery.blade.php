<div class="space-y-8">

    @guest('customers')
        <div class="space-y-3">
            {{-- Row: Phone At Top For Guest Users --}}
            <div>
                <h2 class="text-xl font-semibold mb-1 text-slate-600">
                    Phone Number
                </h2>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-sm text-slate-500">+88</span>
                    <input
                        type="text"
                        name="phone"
                        wire:model.defer="phone"
                        required
                        class="w-full rounded-md border border-slate-200 bg-white pl-12 pr-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                        placeholder="Enter phone number"
                    >
                </div>
            </div>
        </div>
    @endguest

    <div class="space-y-3">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-slate-600">
                Delivery address
            </h2>

            @if($formMode !== 'create' && $formMode !== 'edit')
                <button
                    type="button"
                    wire:click="startCreate"
                    class="inline-flex items-center gap-1 rounded-full border border-rose-500 px-3 py-1.5 text-xs font-semibold text-rose-500 hover:bg-rose-50"
                >
                    <span class="text-base leading-none">+</span>
                    <span>Add Address</span>
                </button>
            @endif
        </div>

        {{-- Hidden input for selected address ID (for form submission) --}}
        <input type="hidden" name="selected_address_id" value="{{ $selectedAddressId }}">

        {{-- Address list --}}
        @if($addresses && $addresses->count())
            <div class="space-y-2">
                @foreach($addresses as $address)
                    <div
                        wire:click="selectAddress({{ $address->id }})"
                        class="rounded-md border px-4 py-3 text-sm shadow-[0_0_0_1px_rgba(248,113,113,0.08)] cursor-pointer
                            @if($selectedAddressId === $address->id)
                                border-rose-400 bg-white
                            @else
                                border-slate-200 bg-white hover:border-rose-300
                            @endif"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="font-semibold text-slate-600">
                                        {{ $address->name }}
                                    </p>

                                    @if($address->address_name)
                                        <span
                                            class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-600">
                                            {{ $address->address_name }}
                                        </span>
                                    @endif

                                    @if($address->is_default)
                                        <span
                                            class="rounded-full bg-rose-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-rose-600">
                                            Default
                                        </span>
                                    @endif
                                </div>

                                <p class="mt-1 text-xs text-slate-600">
                                    {{ $address->address }}
                                    @if($address->city)
                                        , {{ $address->city }}
                                    @endif
                                    @if($address->state)
                                        , {{ $address->state }}
                                    @endif
                                    @if($address->postcode)
                                        , {{ $address->postcode }}
                                    @endif
                                    @if($address->country)
                                        , {{ $address->country }}
                                    @endif
                                </p>

                                @if($address->phone)
                                    <p class="mt-1 text-[11px] text-slate-500">
                                        Phone: {{ $address->phone }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex flex-col items-end gap-2">
                                <div class="flex items-center gap-1 text-[11px] text-slate-400">
                                    @if($selectedAddressId === $address->id)
                                        <span
                                            class="rounded-full bg-sky-100 px-4 py-2 text-[11px] text-sky-700 md:inline-flex">
                                            Selected
                                        </span>
                                    @else
                                        <span
                                            class="rounded-full bg-slate-100 px-4 py-2 text-[11px] text-slate-700 md:inline-flex">
                                            Tap to select
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            @if($formMode !== 'create' && $formMode !== 'edit')
                <p class="text-xs text-slate-500">
                    You don't have any saved addresses yet. Add one to continue.
                </p>
            @endif
        @endif

        {{-- Create / Edit form (inline) --}}
        @if($formMode === 'create' || $formMode === 'edit')
            <div class="mt-2 rounded-md text-sm"
                 x-data="{
                     selectedState: @entangle('state'),
                     selectedCity: @entangle('city'),
                     districts: @js(config('address.districts')),
                     init() {
                         this.$watch('selectedState', () => {
                             this.selectedCity = '';
                         });
                     },
                     get availableDistricts() {
                         return this.selectedState ? (this.districts[this.selectedState] || {}) : {};
                     }
                 }">
                <div class="space-y-3">

                    @auth('customers')
                        {{-- Row: Label --}}
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                Label
                            </label>
                            <input
                                type="text"
                                name="address_name"
                                wire:model.defer="address_name"
                                required
                                class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                                placeholder="Home, Office, etc."
                            >
                            @error('address_name')
                            <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    @endauth

                    {{-- Row: Full name --}}
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-600">
                            Full name
                        </label>
                        <input
                            type="text"
                            name="name"
                            wire:model.defer="name"
                            required
                            class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                        >
                        @error('name')
                        <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    @auth('customers')
                        {{-- Row: Phone --}}
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                Phone
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-sm text-slate-500">+88</span>
                                <input
                                    type="text"
                                    name="phone"
                                    wire:model.defer="phone"
                                    required
                                    class="w-full rounded-md border border-slate-200 bg-white pl-12 pr-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                                    placeholder="Enter phone number"
                                >
                            </div>
                            @error('phone')
                            <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    @endauth

                    {{-- Row: Address --}}
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-600">
                            Address
                        </label>
                        <textarea
                            name="address"
                            wire:model.defer="address"
                            rows="2"
                            required
                            class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                        ></textarea>
                        @error('address')
                        <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Row: State + City --}}
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                Division
                            </label>
                            <select
                                name="state"
                                x-model="selectedState"
                                required
                                class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                            >
                                <option value="">Select Division</option>
                                @foreach(config('address.divisions') as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('state')
                            <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                City / District
                            </label>
                            <select
                                name="city"
                                x-model="selectedCity"
                                required
                                class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                                :disabled="!selectedState || Object.keys(availableDistricts).length === 0"
                            >
                                <option value="" x-text="!selectedState ? 'Select Division first' : 'Select District'"></option>
                                <template x-for="[key, value] in Object.entries(availableDistricts)" :key="key">
                                    <option :value="key" x-text="value"></option>
                                </template>
                            </select>
                            @error('city')
                            <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Row: Postcode + Country --}}
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                Postcode
                                <span class="text-slate-400">(optional)</span>
                            </label>
                            <input
                                type="text"
                                name="postcode"
                                wire:model.defer="postcode"
                                required
                                class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                            >
                            @error('postcode')
                            <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                Country
                            </label>
                            <select
                                name="country"
                                wire:model.defer="country"
                                required
                                class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                            >
                                <option value="Bangladesh">Bangladesh</option>
                            </select>
                            @error('country')
                            <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @auth('customers')
                        {{-- Default checkbox --}}
                        <div class="flex items-center">
                            <label class="inline-flex items-center text-[11px] text-slate-600">
                                <input
                                    type="checkbox"
                                    name="is_default"
                                    wire:model.defer="is_default"
                                    class="h-3.5 w-3.5 rounded border-slate-300 text-rose-500 focus:ring-rose-400"
                                >
                                <span class="ml-2">Set as default address</span>
                            </label>
                        </div>
                    @endauth
                </div>

                {{-- Form actions --}}
                <div class="mt-3 flex justify-end gap-2">
                    @if($addresses && $addresses->count())
                        <button
                            type="button"
                            wire:click="cancelForm"
                            class="inline-flex items-center rounded-full bg-white text-slate-500 hover:text-slate-600 px-4 py-1.5 text-xs font-semibold"
                        >
                            Cancel
                        </button>
                    @endif
                    @auth('customers')
                        @if($formMode === 'create')
                            <button
                                type="button"
                                wire:click="saveAddress"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center rounded-full bg-rose-500 px-4 py-1.5 text-xs font-semibold text-white hover:bg-rose-600"
                            >
                                <span wire:loading.remove wire:target="saveAddress">Save address</span>
                                <span wire:loading wire:target="saveAddress">Saving...</span>
                            </button>
                        @endif
                    @endauth
                </div>
            </div>
        @endif
    </div>
</div>
