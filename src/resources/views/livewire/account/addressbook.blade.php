<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">Address Book</h2>
            <p class="text-sm text-gray-600 mt-1">Manage your shipping addresses</p>
        </div>
        <button
            wire:click="openModal"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors"
        >
            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Address
        </button>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="mb-4 rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($addresses->count() > 0)
        <div class="overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Address Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Phone
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Address
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                City/State
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($addresses as $address)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $address->address_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $address->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $address->phone ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">
                                        {{ $address->address }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $address->city }}{{ $address->state ? ', ' . $address->state : '' }}
                                    </div>
                                    @if($address->postcode)
                                        <div class="text-sm text-gray-500">
                                            {{ $address->postcode }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($address->is_default)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                            Default
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            —
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <button
                                            wire:click="openModal({{ $address->id }})"
                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors"
                                        >
                                            Edit
                                        </button>
                                        @if(!$address->is_default)
                                            <button
                                                wire:click="setDefault({{ $address->id }})"
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors"
                                            >
                                                Set Default
                                            </button>
                                        @endif
                                        @if($totalAddressCount > 1)
                                            <button
                                                wire:click="delete({{ $address->id }})"
                                                wire:confirm="Are you sure you want to delete this address?"
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                                            >
                                                Delete
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $addresses->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No addresses saved</h3>
            <p class="mt-1 text-sm text-gray-500">You haven't added any shipping addresses yet.</p>
        </div>
    @endif

    {{-- Address Modal --}}
    @if($showModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            wire:click="closeModal"
        >
            <div
                class="w-full max-w-2xl rounded-lg bg-white shadow-xl"
                wire:click.stop
            >
                <div class="px-6 py-4 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-slate-600">
                            {{ $editingAddressId ? 'Edit Address' : 'Add New Address' }}
                        </h3>
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="text-slate-400 hover:text-slate-600"
                        >
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <form wire:submit.prevent="save" class="px-6 py-4"
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
                        {{-- Address Name --}}
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                Label
                            </label>
                            <input
                                type="text"
                                id="address_name"
                                wire:model.defer="address_name"
                                class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                                placeholder="Home, Office, etc."
                            >
                            @error('address_name')
                                <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Full Name --}}
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                Full name
                            </label>
                            <input
                                type="text"
                                id="name"
                                wire:model.defer="name"
                                class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                            >
                            @error('name')
                                <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                Phone
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-sm text-slate-500">+88</span>
                                <input
                                    type="text"
                                    id="phone"
                                    wire:model.defer="phone"
                                    class="w-full rounded-md border border-slate-200 bg-white pl-12 pr-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                                    placeholder="Enter phone number"
                                >
                            </div>
                            @error('phone')
                                <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Address --}}
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                Address
                            </label>
                            <textarea
                                id="address"
                                wire:model.defer="address"
                                rows="2"
                                class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                            ></textarea>
                            @error('address')
                                <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Division + City/District --}}
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-600">
                                    Division
                                </label>
                                <select
                                    id="state"
                                    x-model="selectedState"
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
                                    id="city"
                                    x-model="selectedCity"
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

                        {{-- Postcode + Country --}}
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-600">
                                    Postcode
                                    <span class="text-slate-400">(optional)</span>
                                </label>
                                <input
                                    type="text"
                                    id="postcode"
                                    wire:model.defer="postcode"
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
                                    id="country"
                                    wire:model.defer="country"
                                    class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                                >
                                    <option value="Bangladesh">Bangladesh</option>
                                </select>
                                @error('country')
                                    <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Is Default --}}
                        <div class="flex items-center">
                            <label class="inline-flex items-center text-[11px] text-slate-600">
                                <input
                                    type="checkbox"
                                    id="is_default"
                                    wire:model.defer="is_default"
                                    class="h-3.5 w-3.5 rounded border-slate-300 text-rose-500 focus:ring-rose-400"
                                >
                                <span class="ml-2">Set as default address</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-3 flex justify-end gap-2">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="inline-flex items-center rounded-full bg-white text-slate-500 hover:text-slate-600 px-4 py-1.5 text-xs font-semibold"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center rounded-full bg-rose-500 px-4 py-1.5 text-xs font-semibold text-white hover:bg-rose-600"
                        >
                            <span wire:loading.remove wire:target="save">{{ $editingAddressId ? 'Update Address' : 'Add Address' }}</span>
                            <span wire:loading wire:target="save">{{ $editingAddressId ? 'Updating...' : 'Adding...' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
