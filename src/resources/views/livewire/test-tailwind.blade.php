<div class="max-w-xl mx-auto mt-10 bg-white/80 shadow-md rounded-lg p-6 space-y-4">
    <h2 class="text-2xl font-bold text-slate-800">
        Livewire + Tailwind Test
    </h2>

    <p class="text-slate-600">
        This is a simple Livewire component. Type something below and watch it update instantly.
    </p>

    <div class="space-y-2">
        <label for="name" class="block text-sm font-medium text-slate-700">
            Your name
        </label>
        <input
            id="name"
            type="text"
            wire:model.live="name"
            class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            placeholder="Type your name here..."
        >
    </div>

    <div class="pt-2 border-t border-slate-200">
        <p class="text-slate-700">
            Hello,
            <span class="font-semibold text-indigo-600">
                {{ $name ?: 'stranger' }}
            </span>
        </p>
    </div>
</div>
