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
                <p class="text-[12px] text-slate-500">0 Items</p>
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
                    class="absolute -top-1 -right-1 inline-flex h-4 w-4 items-center justify-center rounded-full bg-white text-[11px] font-semibold text-rose-500">2</span>
            </button>
        </div>

    </div>

    <!-- NAV LINKS -->
    <nav class="bg-white text-[14px] font-semibold text-slate-600">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4">
            <div class="flex gap-12 py-3">
                <a href="#" class="text-rose-500">Home</a>
                <a href="#" class="hover:text-rose-500">Our Story</a>
                <a href="#" class="hover:text-rose-500">Products</a>
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

<!-- HERO SECTION -->
<section class="bg-sky-50/70">
    <div class="mx-auto grid max-w-6xl items-center gap-8 px-4 py-10 md:grid-cols-2 md:py-16">
        <!-- Left content -->
        <div class="space-y-4">
            <p class="text-sm font-semibold text-rose-500">
                Get Up to <span class="text-base">25% Discount</span>
            </p>
            <h1 class="text-3xl font-extrabold leading-tight sm:text-4xl">
                All New Best Latest<br/>
                <span class="text-rose-500">Toy Collection</span>
            </h1>
            <p class="text-base text-slate-600">
                Discover colorful, safe and joyful toys for your little ones. Perfect for gifts, birthdays and everyday
                fun.
            </p>
            <button
                class="mt-2 inline-flex items-center rounded-full bg-rose-500 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-rose-600">
                Shop Now
            </button>
        </div>

        <!-- Right hero image placeholder -->
        <div class="flex justify-end">
            <div class="relative w-full max-w-md rounded-[40px] bg-sky-100 px-6 pb-10 pt-8 shadow-lg">
                <div class="absolute -top-6 -left-6 h-16 w-24 rounded-3xl bg-white/70 shadow"></div>
                <div class="absolute -top-4 right-4 h-14 w-14 rounded-full bg-white shadow-inner"></div>

                <div class="mt-8 flex items-end justify-center gap-4">
                    <div class="h-40 w-32 rounded-3xl bg-yellow-200"></div>
                    <div class="h-44 w-32 rounded-3xl bg-rose-200"></div>
                    <div class="h-36 w-24 rounded-3xl bg-sky-200"></div>
                </div>
                <p class="mt-4 text-center text-sm font-semibold text-slate-500">Hero Toy Banner</p>
            </div>
        </div>
    </div>
</section>

<!-- TOP PRODUCTS -->
<section class="bg-white py-12">
    <div class="mx-auto max-w-6xl px-4">
        <div class="mb-6 text-center">
            <h2 class="mt-2 text-2xl font-bold">Top Product</h2>

            <!-- Filter pills -->
            <div class="mt-4 inline-flex gap-2 rounded-full bg-slate-100 p-1 text-sm">
                <button class="rounded-full bg-white px-4 py-1 font-semibold text-rose-500 shadow-sm">Featured</button>
                <button class="rounded-full px-4 py-1 text-slate-500 hover:text-rose-500">Popular</button>
                <button class="rounded-full px-4 py-1 text-slate-500 hover:text-rose-500">New</button>
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
                <div class="mb-4 flex h-32 items-center justify-center rounded-2xl bg-sky-50">
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
                <div class="mb-4 flex h-32 items-center justify-center rounded-2xl bg-rose-50">
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
                <div class="mb-4 flex h-32 items-center justify-center rounded-2xl bg-sky-50">
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
                <div class="mb-4 flex h-32 items-center justify-center rounded-2xl bg-emerald-50">
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
<section class="bg-white pb-12">
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

<!-- LATEST PRODUCTS -->
<section class="bg-slate-50 py-12">
    <div class="mx-auto max-w-6xl px-4">
        <div class="mb-6 text-center">
            <p class="text-sm font-semibold tracking-[0.25em] text-rose-400">LATEST PRODUCT</p>
            <h2 class="mt-2 text-2xl font-bold">Latest Product</h2>
        </div>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- reuse same card structure (shortened) -->
            <article class="rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
                <div class="mb-4 flex h-32 items-center justify-center rounded-2xl bg-sky-50">
                    <span class="text-sm text-slate-400">Toy Image</span>
                </div>
                <h3 class="text-base font-semibold">Bulldozer</h3>
                <p class="mt-1 text-sm text-slate-500">$18.00</p>
            </article>
            <article class="rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
                <div class="mb-4 flex h-32 items-center justify-center rounded-2xl bg-rose-50">
                    <span class="text-sm text-slate-400">Toy Image</span>
                </div>
                <h3 class="text-base font-semibold">Toy Car</h3>
                <p class="mt-1 text-sm text-slate-500">$15.00</p>
            </article>
            <article class="rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
                <div class="mb-4 flex h-32 items-center justify-center rounded-2xl bg-emerald-50">
                    <span class="text-sm text-slate-400">Toy Image</span>
                </div>
                <h3 class="text-base font-semibold">Toys &amp; Games</h3>
                <p class="mt-1 text-sm text-slate-500">$19.00</p>
            </article>
            <article class="rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
                <div class="mb-4 flex h-32 items-center justify-center rounded-2xl bg-sky-50">
                    <span class="text-sm text-slate-400">Toy Image</span>
                </div>
                <h3 class="text-base font-semibold">Educational Toys</h3>
                <p class="mt-1 text-sm text-slate-500">$16.50</p>
            </article>
        </div>
    </div>
</section>

<!-- PROMO GRID -->
<section class="bg-white py-12">
    <div class="mx-auto grid max-w-6xl gap-6 px-4 md:grid-cols-3">
        <div class="rounded-3xl bg-rose-50 p-6">
            <p class="text-base font-semibold">Ribbed Baby Booties</p>
            <p class="mt-1 text-sm text-slate-500">Up to 30% off</p>
        </div>
        <div class="rounded-3xl bg-sky-50 p-6">
            <p class="text-base font-semibold">Flat 15% OFF</p>
            <p class="mt-1 text-sm text-slate-500">On Kids’ Wear</p>
        </div>
        <div class="rounded-3xl bg-amber-50 p-6">
            <p class="text-base font-semibold">New Arrival</p>
            <p class="mt-1 text-sm text-slate-500">Up to 50% off</p>
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section class="bg-slate-50 py-12">
    <div class="mx-auto max-w-4xl px-4 text-center">
        <p class="text-sm font-semibold tracking-[0.25em] text-rose-400">TESTIMONIALS</p>
        <h2 class="mt-2 text-2xl font-bold">What Parents Say</h2>
        <div class="mt-8 rounded-[40px] bg-white px-8 py-10 shadow-sm ring-1 ring-slate-100">
            <div class="mx-auto mb-4 h-16 w-16 rounded-full bg-slate-200"></div>
            <p class="text-base text-slate-600">
                “ToyWay has the cutest toys and amazing quality. My kids love every order we receive!”
            </p>
            <p class="mt-4 text-base font-semibold text-slate-800">Mrs. Anya Stark</p>
            <p class="text-[12px] text-slate-400">Happy Mom</p>
        </div>
    </div>
</section>

<!-- SPECIAL PRODUCTS (ROW LIST) -->
<section class="bg-white py-12">
    <div class="mx-auto max-w-6xl px-4">
        <div class="mb-6 text-center">
            <p class="text-sm font-semibold tracking-[0.25em] text-rose-400">SPECIAL PRODUCT</p>
            <h2 class="mt-2 text-2xl font-bold">Special Product</h2>
        </div>
        <div class="overflow-hidden rounded-3xl bg-slate-50 p-4 shadow-sm ring-1 ring-slate-100">
            <div class="grid gap-4 text-sm sm:grid-cols-3 lg:grid-cols-6">
                <div class="flex items-center gap-2">
                    <div class="h-12 w-12 rounded-2xl bg-sky-100"></div>
                    <div>
                        <p class="font-semibold text-slate-800">Bulldozer</p>
                        <p class="text-[12px] text-slate-500">$18.00</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="h-12 w-12 rounded-2xl bg-rose-100"></div>
                    <div>
                        <p class="font-semibold text-slate-800">Toy Car</p>
                        <p class="text-[12px] text-slate-500">$15.00</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="h-12 w-12 rounded-2xl bg-emerald-100"></div>
                    <div>
                        <p class="font-semibold text-slate-800">Toys &amp; Games</p>
                        <p class="text-[12px] text-slate-500">$19.00</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="h-12 w-12 rounded-2xl bg-sky-100"></div>
                    <div>
                        <p class="font-semibold text-slate-800">Educational Toys</p>
                        <p class="text-[12px] text-slate-500">$16.50</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="h-12 w-12 rounded-2xl bg-rose-100"></div>
                    <div>
                        <p class="font-semibold text-slate-800">Bulldozer</p>
                        <p class="text-[12px] text-slate-500">$18.00</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="h-12 w-12 rounded-2xl bg-amber-100"></div>
                    <div>
                        <p class="font-semibold text-slate-800">Toy Car</p>
                        <p class="text-[12px] text-slate-500">$15.00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- NEWSLETTER BAR -->
<section class="bg-rose-200 py-10">
    <div
        class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-4 text-center md:flex-row md:text-left">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Sign Up &amp; Subscribe For Newsletter</h2>
            <p class="mt-1 text-sm text-slate-700">
                Shopping for your kids? Get the best deals, new arrivals and special offers straight to your inbox.
            </p>
        </div>
        <form class="flex w-full max-w-md flex-col gap-2 sm:flex-row">
            <input
                type="email"
                placeholder="Enter your email"
                class="w-full rounded-full border border-transparent bg-white px-4 py-2 text-sm outline-none"
            />
            <button class="rounded-full bg-slate-900 px-6 py-2 text-sm font-semibold text-white hover:bg-black">
                Subscribe
            </button>
        </form>
    </div>
</section>

<!-- LATEST BLOG POSTS -->
<section class="bg-white py-12">
    <div class="mx-auto max-w-6xl px-4">
        <div class="mb-6 text-center">
            <p class="text-sm font-semibold tracking-[0.25em] text-rose-400">LATEST BLOG POSTS</p>
            <h2 class="mt-2 text-2xl font-bold">Latest Blog Posts</h2>
        </div>
        <div class="grid gap-6 md:grid-cols-3">
            <article class="rounded-3xl bg-slate-50 shadow-sm ring-1 ring-slate-100">
                <div class="h-32 rounded-t-3xl bg-slate-200"></div>
                <div class="p-4 text-sm">
                    <p class="text-[12px] text-rose-400">Nov 06, 2024</p>
                    <h3 class="mt-1 text-base font-semibold">How to pick safe toys for toddlers</h3>
                    <p class="mt-1 text-slate-500">
                        Learn simple tips to choose age-appropriate toys that support healthy development...
                    </p>
                    <a href="#" class="mt-2 inline-block text-[12px] font-semibold text-rose-500">Read More</a>
                </div>
            </article>
            <article class="rounded-3xl bg-slate-50 shadow-sm ring-1 ring-slate-100">
                <div class="h-32 rounded-t-3xl bg-slate-200"></div>
                <div class="p-4 text-sm">
                    <p class="text-[12px] text-rose-400">Nov 08, 2024</p>
                    <h3 class="mt-1 text-base font-semibold">Top 10 educational toys for age 5+</h3>
                    <p class="mt-1 text-slate-500">
                        Our favorite toys that make learning math, science and creativity super fun...
                    </p>
                    <a href="#" class="mt-2 inline-block text-[12px] font-semibold text-rose-500">Read More</a>
                </div>
            </article>
            <article class="rounded-3xl bg-slate-50 shadow-sm ring-1 ring-slate-100">
                <div class="h-32 rounded-t-3xl bg-slate-200"></div>
                <div class="p-4 text-sm">
                    <p class="text-[12px] text-rose-400">Nov 10, 2024</p>
                    <h3 class="mt-1 text-base font-semibold">Gift ideas for birthdays &amp; holidays</h3>
                    <p class="mt-1 text-slate-500">
                        A quick list of toys that kids always get excited about when unboxing...
                    </p>
                    <a href="#" class="mt-2 inline-block text-[12px] font-semibold text-rose-500">Read More</a>
                </div>
            </article>
        </div>
    </div>
</section>

<!-- BRANDS -->
<section class="bg-slate-50 py-10">
    <div class="mx-auto max-w-6xl px-4 text-center">
        <h2 class="mb-6 text-base font-semibold text-slate-700">Toy Brands</h2>
        <div class="flex flex-wrap items-center justify-center gap-8 text-sm text-slate-500">
            <div class="rounded-full border border-slate-200 px-6 py-2">LEGO</div>
            <div class="rounded-full border border-slate-200 px-6 py-2">Little Tikes</div>
            <div class="rounded-full border border-slate-200 px-6 py-2">Playmobil</div>
            <div class="rounded-full border border-slate-200 px-6 py-2">K'NEX</div>
            <div class="rounded-full border border-slate-200 px-6 py-2">Pop Toys</div>
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
