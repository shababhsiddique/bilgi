<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>ToyWay – Kids Toy Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    {{-- Vite / your compiled assets that include Tailwind --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="bg-white text-slate-900 antialiased text-sm">
<!-- TOP BAR -->
<div class="w-full bg-sky-50 text-sm text-slate-600">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-2">
        <p>Use code <span class="font-semibold text-rose-500">STEMTOYS4BD2026</span> and get 20% off!</p>
        <div class="flex items-center gap-4">
            <a href="#" class="hover:text-slate-900">Login</a>
            <a href="#" class="hover:text-slate-900">Register</a>
        </div>
    </div>
</div>

<!-- HEADER / NAVBAR -->
<header class="border-b border-rose-500 bg-white">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
        <!-- Logo -->
        <div class="flex items-center gap-2">
            <img src="{{asset('images/logo-full.png')}}" alt="Logo" class="h-15 w-auto"/>
        </div>

        <!-- Search -->
        <div class="hidden flex-1 items-center justify-center px-8 md:flex">
            <div class="flex w-full max-w-md items-center rounded-full border border-slate-200 bg-slate-50 px-4 py-2">
                <input
                    type="text"
                    placeholder="Search toys..."
                    class="w-full bg-transparent text-sm outline-none placeholder:text-slate-400"
                />
            </div>
        </div>

        <!-- Contact / Cart -->
        <div class="flex items-center gap-4 text-sm">
            <div class="hidden text-right sm:block">
                <p class="font-semibold text-slate-700">Shopping Cart</p>
                <p class="text-[12px] text-slate-500">1 Items</p>
            </div>
            <button
                class="relative inline-flex h-10 w-10 items-center justify-center rounded-full bg-rose-500 text-white">
                <!-- Cart icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                </svg>
                <span
                    class="absolute -top-1 -right-1 inline-flex h-4 w-4 items-center justify-center rounded-full bg-white text-[11px] font-semibold text-rose-500">1</span>
            </button>
        </div>

    </div>

    <!-- NAV LINKS -->
    <nav class="bg-white text-[14px] font-semibold text-slate-600">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4">
            <div class="flex gap-12 py-3">
                <a href="#" class="hover:text-rose-500">Home</a>
                <a href="#" class="hover:text-rose-500">Our Story</a>
                <a href="#" class="text-rose-500">Products</a>
                <a href="#" class="hover:text-rose-500">Testimonials</a>
                <a href="#" class="hover:text-rose-500">Blog</a>
                <a href="#" class="hover:text-rose-500">Contact</a>
            </div>
            <a href="#" class="rounded-full bg-sky-100 px-4 py-2 text-[11px] text-sky-700 md:inline-flex">
                Track Order
            </a>
        </div>
    </nav>
</header>

<!-- ALL PRODUCTS -->
<section class="bg-white py-8">
    <div class="mx-auto max-w-6xl px-4">
        <div class="mb-6 text-center">
            <!-- Filter pills -->
            <div class="flex flex-wrap items-center justify-center gap-8 text-sm text-slate-500">
                <div class="rounded-full border border-slate-200 px-6 py-2">LEGO</div>
                <div class="rounded-full border border-slate-200 px-6 py-2">Little Tikes</div>
                <div class="rounded-full border border-slate-200 px-6 py-2">Playmobil</div>
                <div class="rounded-full border border-slate-200 px-6 py-2">K'NEX</div>
                <div class="rounded-full border border-slate-200 px-6 py-2">Pop Toys</div>
            </div>
        </div>

        <!-- Product cards -->
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Card -->
            <article class="relative rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
            <span
                class="absolute left-4 top-4 rounded-full bg-sky-100 px-2 py-0.5 text-[11px] font-semibold text-sky-600">
              SALE
            </span>
                <div class="mb-4 flex h-44 items-center justify-center rounded-2xl bg-sky-50">
                    <span class="text-sm text-slate-400">Toy Image</span>
                </div>
                <h3 class="text-base font-semibold">Bulldozer</h3>
                <div class="mt-1 flex items-center justify-between text-sm text-slate-500">
                    <span>$18.00</span>
                    <span class="text-[11px] text-amber-500">★★★★★</span>
                </div>
            </article>

            <article class="relative rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
            <span
                class="absolute left-4 top-4 rounded-full bg-rose-100 px-2 py-0.5 text-[11px] font-semibold text-rose-500">
              NEW
            </span>
                <div class="mb-4 flex h-44 items-center justify-center rounded-2xl bg-rose-50">
                    <span class="text-sm text-slate-400">Toy Image</span>
                </div>
                <h3 class="text-base font-semibold">Toy Car</h3>
                <div class="mt-1 flex items-center justify-between text-sm text-slate-500">
                    <span>$15.00</span>
                    <span class="text-[11px] text-amber-500">★★★★☆</span>
                </div>
            </article>

            <article class="relative rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
            <span
                class="absolute left-4 top-4 rounded-full bg-sky-100 px-2 py-0.5 text-[11px] font-semibold text-sky-600">
              SALE
            </span>
                <div class="mb-4 flex h-44 items-center justify-center rounded-2xl bg-sky-50">
                    <span class="text-sm text-slate-400">Toy Image</span>
                </div>
                <h3 class="text-base font-semibold">Toys &amp; Games</h3>
                <div class="mt-1 flex items-center justify-between text-sm text-slate-500">
                    <span>$19.00</span>
                    <span class="text-[11px] text-amber-500">★★★★★</span>
                </div>
            </article>

            <article class="relative rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
            <span
                class="absolute left-4 top-4 rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-semibold text-emerald-600">
              HOT
            </span>
                <div class="mb-4 flex h-44 items-center justify-center rounded-2xl bg-emerald-50">
                    <span class="text-sm text-slate-400">Toy Image</span>
                </div>
                <h3 class="text-base font-semibold">Educational Toys</h3>
                <div class="mt-1 flex items-center justify-between text-sm text-slate-500">
                    <span>$16.50</span>
                    <span class="text-[11px] text-amber-500">★★★★☆</span>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="bg-white">
    <div class="mx-auto max-w-6xl px-6 py-10 grid gap-10 lg:grid-cols-[minmax(0,2.2fr)_minmax(320px,1fr)]">

        <!-- LEFT: all steps (cart + delivery + payment) -->
        <div class="space-y-10">

            <!-- STEP 1: Order summary (cart items) -->
            <div>
                <div class="mb-6 flex items-center gap-3">
                    <h2 class="text-xl font-semibold text-slate-600">
                        Order summary
                    </h2>
                    <button class="flex items-center gap-1 text-xs font-medium text-slate-600 hover:text-rose-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3v5h5M21 21v-5h-5"/>
                            <path d="M5 19A9 9 0 0 0 19 5"/>
                        </svg>
                        <span>Quick reorder</span>
                    </button>
                </div>

                <div class="space-y-6">
                    <!-- ITEM 1 -->
                    <div class="flex gap-4">
                        <!-- Thumb -->
                        <div class="h-20 w-28 overflow-hidden rounded-md bg-slate-100">
                            <img
                                src="https://via.placeholder.com/160x120"
                                alt="Magnetic Construction Blocks 75 Pieces"
                                class="h-full w-full object-cover"
                            />
                        </div>

                        <!-- Body -->
                        <div class="flex-1">
                            <!-- Row 1: title + price -->
                            <div class="flex items-start justify-between gap-4">
                                <h3 class="text-sm font-semibold text-slate-600">
                                    Magnetic Construction Blocks 75 Pieces
                                </h3>
                                <p class="text-sm font-semibold text-slate-600 whitespace-nowrap">
                                    7,000.00 <span class="text-xs align-top">৳</span>
                                </p>
                            </div>

                            <!-- Row 2: actions + qty -->
                            <div class="mt-2 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3 text-xs font-medium">
                                    <button class="text-rose-500 hover:underline">
                                        Remove
                                    </button>
                                    <span class="text-slate-400">|</span>
                                    <button class="text-rose-500 hover:underline">
                                        Save for Later
                                    </button>
                                </div>

                                <div
                                    class="inline-flex items-center rounded-full border border-slate-200 px-4 py-1.5 text-sm">
                                    <button class="px-1 text-rose-500 hover:text-rose-600">−</button>
                                    <span class="px-3 text-slate-600">2</span>
                                    <button class="px-1 text-rose-500 hover:text-rose-600">+</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ITEM 2 -->
                    <div class="flex gap-4">
                        <!-- Thumb -->
                        <div class="h-20 w-28 overflow-hidden rounded-md bg-slate-100">
                            <img
                                src="https://via.placeholder.com/160x120"
                                alt="Magnetic Construction Blocks 63 Pieces"
                                class="h-full w-full object-cover"
                            />
                        </div>

                        <!-- Body -->
                        <div class="flex-1">
                            <!-- Row 1: title + price -->
                            <div class="flex items-start justify-between gap-4">
                                <h3 class="text-sm font-semibold text-slate-600">
                                    Magnetic Construction Blocks 63 Pieces
                                </h3>
                                <p class="text-sm font-semibold text-slate-600 whitespace-nowrap">
                                    3,000.00 <span class="text-xs align-top">৳</span>
                                </p>
                            </div>

                            <!-- Row 2: actions + qty -->
                            <div class="mt-2 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3 text-xs font-medium">
                                    <button class="text-rose-500 hover:underline">
                                        Remove
                                    </button>
                                    <span class="text-slate-400">|</span>
                                    <button class="text-rose-500 hover:underline">
                                        Save for Later
                                    </button>
                                </div>

                                <div
                                    class="inline-flex items-center rounded-full border border-slate-200 px-4 py-1.5 text-sm">
                                    <button class="px-1 text-rose-500 hover:text-rose-600">−</button>
                                    <span class="px-3 text-slate-600">1</span>
                                    <button class="px-1 text-rose-500 hover:text-rose-600">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: Delivery method & address -->
            <div class="space-y-8">
                <!-- Delivery method -->
                <div>
                    <h2 class="text-xl font-semibold text-slate-600 mb-3">
                        Delivery method
                    </h2>

                    <button
                        class="flex w-full items-center justify-between rounded-md border border-slate-200 bg-white px-4 py-3 text-sm hover:border-rose-500">
                        <div class="flex items-center gap-3">
                            <!-- fake radio -->
                            <span
                                class="inline-flex h-4 w-4 items-center justify-center rounded-full border border-rose-500">
                              <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                            </span>
                            <span class="text-slate-600">Standard delivery</span>
                        </div>
                        <span class="text-slate-600">100.00 <span class="text-xs align-top">৳</span></span>
                    </button>
                </div>

                <!-- Delivery address -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-slate-600">
                            Delivery address
                        </h2>
                        <button
                            class="inline-flex items-center gap-1 rounded-full border border-rose-500 px-3 py-1.5 text-xs font-semibold text-rose-500 hover:bg-rose-50">
                            <span class="text-base leading-none">+</span>
                            <span>Add Address</span>
                        </button>
                    </div>

                    <!-- Selected address card -->
                    <div
                        class="rounded-md border border-rose-400 bg-white px-4 py-3 text-sm shadow-[0_0_0_1px_rgba(248,113,113,0.15)]">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-slate-600">Shabab Haider Siddique</p>
                                <p class="mt-1 text-xs text-slate-600">
                                    sadf, asdf 1231, Bangladesh
                                </p>
                            </div>

                            <div class="flex flex-col items-end gap-2">
                              <span
                                  class="inline-flex items-center rounded-full border border-sky-400 bg-sky-50 px-3 py-1 text-[11px] font-semibold text-sky-700">
                                Main Address
                              </span>
                                <button class="text-xs text-rose-500 hover:text-rose-600">
                                    ✏️
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 3: Payment method -->
            <div class="space-y-8">
                <!-- Title -->
                <h2 class="text-xl font-semibold text-slate-600 flex items-center gap-2">
                    Payment method
                    <span>🤖</span>
                </h2>

                <!-- Payment options -->
                <div class="space-y-3 text-sm">

                    <!-- bKash -->
                    <label
                        class="flex cursor-pointer items-center justify-between rounded-md border border-slate-200 bg-white px-4 py-3 hover:border-rose-500">
                        <div class="flex items-center gap-3">
                            <span
                                class="inline-flex h-4 w-4 items-center justify-center rounded-full border border-rose-500">
                              <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                            </span>
                            <span class="text-slate-600">bKash (Manual Payment)</span>
                        </div>

                        <!-- bKash logo -->
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="-6.6741 -11.07275 57.8422 66.4365"
                            class="h-8 w-auto"
                        >
                            <g fill="none">
                                <path fill="#DF146E"
                                      d="M42.31 44.291H2.182C.981 44.291 0 43.308 0 42.107V2.186C0 .982.981 0 2.182 0H42.31c1.203 0 2.184.982 2.184 2.186v39.921c0 1.201-.981 2.184-2.184 2.184"/>
                                <path fill="#FFF"
                                      d="M31.894 24.251l-14.107-2.246 1.909 8.329zm.572-.682L21.374 8.16l-3.623 13.106zm-15.402-2.482L5.441 6.239l15.221 1.819zm-5.639-6.154l-6.449-6.08h1.695zm24.504 1.15L33.2 23.486l-4.426-6.118zM21.417 30.232l10.71-4.3.454-1.365zm-8.933 7.821l4.589-16.102 2.326 10.479zm24.099-21.914l-1.128 3.056 4.059-.07z"/>
                            </g>
                        </svg>
                    </label>

                    <!-- COD -->
                    <label
                        class="flex cursor-pointer items-center justify-between rounded-md border border-slate-200 bg-white px-4 py-3 hover:border-rose-500">
                        <div class="flex items-center gap-3">
                            <span
                                class="inline-flex h-4 w-4 items-center justify-center rounded-full border border-slate-400"></span>
                            <span class="text-slate-600">Cash on Delivery</span>
                        </div>

                        <span
                            class="inline-flex h-6 w-9 items-center justify-center rounded bg-sky-100 text-slate-600">
                          💵
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-- RIGHT: unified summary card -->
        <div class="flex justify-end">
            <div class="w-full max-w-sm rounded-xl border border-slate-200 bg-white px-6 py-6 shadow-sm">

                <!-- Items list -->
                <div class="space-y-4 text-sm">
                    <!-- Item 1 -->
                    <div class="flex items-center justify-between gap-3 border-b border-slate-200 pb-3">
                        <div class="flex items-center gap-3">
                            <div class="relative h-12 w-16 overflow-hidden rounded-md bg-slate-100">
                                <img src="https://via.placeholder.com/120x90"
                                     alt="Magnetic Construction Blocks 75 Pieces"
                                     class="h-full w-full object-cover"/>
                                <!-- qty pill -->
                                <span
                                    class="absolute -top-2 -left-2 inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-900 text-[11px] font-semibold text-white">
                                  2
                                </span>
                            </div>
                            <p class="text-xs text-slate-600">
                                Magnetic Construction<br/>Blocks 75 Pieces
                            </p>
                        </div>
                        <p class="text-sm font-semibold text-slate-600 whitespace-nowrap">
                            7,000.00 <span class="text-xs align-top">৳</span>
                        </p>
                    </div>

                    <!-- Item 2 -->
                    <div class="flex items-center justify-between gap-3 border-b border-slate-200 pb-3">
                        <div class="flex items-center gap-3">
                            <div class="relative h-12 w-16 overflow-hidden rounded-md bg-slate-100">
                                <img src="https://via.placeholder.com/120x90"
                                     alt="Magnetic Construction Blocks 63 Pieces"
                                     class="h-full w-full object-cover"/>
                                <!-- qty pill -->
                                <span
                                    class="absolute -top-2 -left-2 inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-900 text-[11px] font-semibold text-white">
                                  1
                                </span>
                            </div>
                            <p class="text-xs text-slate-600">
                                Magnetic Construction<br/>Blocks 63 Pieces
                            </p>
                        </div>
                        <p class="text-sm font-semibold text-slate-600 whitespace-nowrap">
                            3,000.00 <span class="text-xs align-top">৳</span>
                        </p>
                    </div>
                </div>

                <!-- Totals -->
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-600">Delivery</dt>
                        <dd class="text-slate-600">100.00 ৳</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-600">Subtotal</dt>
                        <dd class="text-slate-600">10,100.00 ৳</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-600">Taxes</dt>
                        <dd class="text-slate-600">0.00 ৳</dd>
                    </div>
                    <div class="mt-3 flex items-center justify-between border-t border-slate-200 pt-3">
                        <dt class="text-sm font-semibold text-slate-600">Total</dt>
                        <dd class="text-sm font-semibold text-slate-600">10,100.00 ৳</dd>
                    </div>
                </dl>

                <!-- Discount -->
                <div class="mt-4 flex">
                    <input
                        type="text"
                        placeholder="Discount code..."
                        class="w-full rounded-l-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-rose-500"
                    />
                    <button
                        class="rounded-r-md border border-l-0 border-slate-200 bg-slate-100 px-4 text-sm font-medium text-slate-600 hover:bg-slate-200">
                        Apply
                    </button>
                </div>

                <!-- Pay Now -->
                <button
                    class="mt-5 w-full rounded-full bg-rose-500 py-2.5 text-sm font-semibold text-white hover:bg-rose-600">
                    Pay now →
                </button>

                <!-- Back link -->
                <div class="mt-6 flex items-center gap-3 text-xs text-slate-400">
                    <div class="h-px flex-1 bg-slate-200"></div>
                    <span>or</span>
                    <div class="h-px flex-1 bg-slate-200"></div>
                </div>

                <button class="mt-3 text-sm font-medium text-rose-500 hover:underline">
                    ← Back to cart
                </button>
            </div>
        </div>
    </div>
</section>


<!-- FOOTER -->
<footer class="bg-slate-900 text-sm text-slate-300">
    <div class="mx-auto grid max-w-6xl gap-8 px-4 py-10 md:grid-cols-4">
        <div>
            <h3 class="text-base font-semibold text-white">Quick Links</h3>
            <ul class="mt-3 space-y-1">
                <li><a href="#" class="hover:text-white">Home</a></li>
                <li><a href="#" class="hover:text-white">Our Story</a></li>
                <li><a href="#" class="hover:text-white">Products</a></li>
                <li><a href="#" class="hover:text-white">Testimonials</a></li>
                <li><a href="#" class="hover:text-white">Contact Us</a></li>
            </ul>
        </div>
        <div>
            <h3 class="text-base font-semibold text-white">Our Company</h3>
            <ul class="mt-3 space-y-1">
                <li><a href="#" class="hover:text-white">Guarantee</a></li>
                <li><a href="#" class="hover:text-white">Legal Notice</a></li>
                <li><a href="#" class="hover:text-white">Terms &amp; Conditions</a></li>
                <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
            </ul>
        </div>
        <div>
            <h3 class="text-base font-semibold text-white">Your Account</h3>
            <ul class="mt-3 space-y-1">
                <li><a href="#" class="hover:text-white">My Orders</a></li>
                <li><a href="#" class="hover:text-white">Wishlist</a></li>
                <li><a href="#" class="hover:text-white">Address</a></li>
                <li><a href="#" class="hover:text-white">Support</a></li>
            </ul>
        </div>
        <div>
            <h3 class="text-base font-semibold text-white">Contact Details</h3>
            <ul class="mt-3 space-y-1">
                <li>99, Lorem Ipsum Ave, Toy City</li>
                <li>Phone: +1 123 456 789</li>
                <li>Email: support@toyway.com</li>
            </ul>
        </div>
    </div>

    <div class="border-t border-slate-800">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
            <p class="text-[12px]">© 2025 · ToyWay Shop. All Rights Reserved.</p>
            <div class="flex items-center gap-2 text-[11px]">
                <span class="rounded bg-white/10 px-2 py-1">VISA</span>
                <span class="rounded bg-white/10 px-2 py-1">MasterCard</span>
                <span class="rounded bg-white/10 px-2 py-1">PayPal</span>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
