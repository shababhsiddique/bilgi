<div>
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-900">Account Information</h2>
        <p class="text-sm text-gray-600 mt-1">Update your personal information and password</p>
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

    <form wire:submit.prevent="save" class="space-y-3">
        {{-- Full Name --}}
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-600">
                Full name
            </label>
            <input
                type="text"
                wire:model.defer="full_name"
                class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                required
            >
            @error('full_name')
                <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-600">
                Email
                <span class="text-slate-400">(optional)</span>
            </label>
            <input
                type="email"
                wire:model.defer="email"
                class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
            >
            @error('email')
                <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password Section --}}
        <div class="border-t border-slate-200 pt-4 mt-4">
            <h3 class="text-sm font-semibold text-slate-700 mb-3">Change Password</h3>
            <p class="text-[11px] text-slate-500 mb-3">Leave blank if you don't want to change your password</p>

            {{-- New Password --}}
            <div class="mb-3">
                <label class="mb-1 block text-xs font-medium text-slate-600">
                    New Password
                </label>
                <input
                    type="password"
                    wire:model.defer="password"
                    class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                    placeholder="Enter new password"
                >
                @error('password')
                    <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-600">
                    Confirm New Password
                </label>
                <input
                    type="password"
                    wire:model.defer="password_confirmation"
                    class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                    placeholder="Confirm new password"
                >
                @error('password_confirmation')
                    <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="mt-6 flex justify-end">
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="inline-flex items-center rounded-full bg-rose-500 px-4 py-1.5 text-xs font-semibold text-white hover:bg-rose-600"
            >
                <span wire:loading.remove wire:target="save">Save Changes</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </form>
</div>


