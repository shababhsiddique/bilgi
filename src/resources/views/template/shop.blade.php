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
        <p>Use code <span class="font-semibold text-rose-500">BILGI4BD2026</span> and get 20% off!</p>
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

<!-- BANNER: KIDS TOY COLLECTION -->
<section class="bg-white pb-0">
    <div class="mx-auto max-w-6xl px-4">
        <div class="grid gap-6 rounded-[40px] bg-rose-50 px-8 py-10 md:grid-cols-2">
            <div class="flex flex-col justify-center gap-3">
                <p class="text-sm font-semibold text-rose-400">New Collection</p>
                <h2 class="text-2xl font-bold">Kids Toy</h2>
                <p class="text-base text-slate-600">
                    Flat <span class="font-semibold text-rose-500">50% Off</span> on fancy toys for your kids.
                </p>
                <button
                    class="mt-2 inline-flex w-max items-center rounded-full bg-rose-500 px-6 py-2 text-sm font-semibold text-white hover:bg-rose-600">
                    Shop Now
                </button>
            </div>
            <div class="flex items-center justify-center">
                <div class="h-40 w-52 rounded-[32px] bg-white shadow-md"></div>
            </div>
        </div>
    </div>
</section>


<!-- All PRODUCTS PART 2-->
<section class="bg-white py-8 pb-12">
    <div class="mx-auto max-w-6xl px-4">
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
            <p class="text-[12px]">© {{ date('Y') }} · WithBilgi. All Rights Reserved.</p>
            <div class="flex items-center gap-2 text-[11px]">
                <span class="rounded bg-white/10 px-2 py-1">bKash</span>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
