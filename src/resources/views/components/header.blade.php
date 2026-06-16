<!-- TOP BAR -->
<div class="w-full bg-sky-50 text-sm text-slate-600">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-2">
        <p>Use code <span class="font-semibold text-red-500">LEARN&FUN2026</span> and get 10% off!</p>
        <div class="flex items-center gap-4">
            @guest
                {{-- Guest: show Login only --}}
                <a
                    href="{{ route('login') }}"
                    class="hover:text-slate-900"
                >
                    {{ __('Login') }}
                </a>
            @endguest

            @auth
                {{-- Authenticated: show Account and Logout --}}
                <a
                    href="{{ route('account') }}"
                    class="hover:text-slate-900"
                >
                    {{ __('Account') }}
                </a>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="inline"
                >
                    @csrf
                    <button
                        type="submit"
                        class="hover:text-slate-900"
                    >
                        {{ __('Logout') }}
                    </button>
                </form>
            @endauth
        </div>

    </div>
</div>

<!-- HEADER / NAVBAR -->
<header class="border-b border-sky-200 bg-white shadow-lg">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-4 pt-4">
        <!-- Logo -->
        <div class="flex items-center gap-2">
            <a href="{{route('home')}}">
                <img src="{{asset('images/bilgi-white-65-109.png')}}" alt="Logo" class="w-auto"/>
            </a>
        </div>

        <!-- Search -->
        <div class="hidden flex-1 items-center justify-center px-8 md:flex">
            <form action="{{ route('shop') }}" method="GET" class="w-full max-w-md">
                <div class="flex w-full items-center rounded-full border border-slate-200 bg-slate-50 px-4 py-2">
                    <input
                        type="text"
                        name="search"
                        placeholder="Search toys..."
                        value="{{ request('search') }}"
                        class="w-full bg-transparent text-sm outline-none placeholder:text-slate-400"
                    />
                    <button type="submit" class="ml-2 text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Contact / Cart -->
        @livewire('common.cart-button')

    </div>

    <!-- Mobile search (hidden on md+) -->
    <div class="px-4 pt-3 md:hidden">
        <form action="{{ route('shop') }}" method="GET">
            <div class="flex w-full items-center rounded-full border border-slate-200 bg-slate-50 px-4 py-2.5">
                <input
                    type="text"
                    name="search"
                    placeholder="Search toys..."
                    value="{{ request('search') }}"
                    class="w-full bg-transparent text-base outline-none placeholder:text-slate-400"
                    inputmode="search"
                />
                <button type="submit" class="ml-2 text-slate-400 hover:text-slate-600" aria-label="Search">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <!-- NAV LINKS -->
    <nav class="bg-white text-[14px] font-semibold text-slate-600">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4">
            <div class="flex gap-6 py-3 sm:gap-12">
                <a href="{{route('home')}}"
                   class="{{ request()->routeIs('home') ? 'text-red-500' : 'hover:text-red-500' }}">
                    Home
                </a>
                <a href="{{route('shop')}}"
                   class="{{ request()->routeIs('shop') ? 'text-emerald-500' : 'hover:text-emerald-500' }}">
                    Products
                </a>
            </div>

            <a href="{{route('account')."?section=orders"}}" class="rounded-full bg-sky-100 px-4 py-2 text-[11px] text-blue-400 md:inline-flex">
                Track Order
            </a>
        </div>
    </nav>
</header>
