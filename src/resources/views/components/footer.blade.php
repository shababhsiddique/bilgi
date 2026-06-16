<footer class="bg-slate-900 text-sm text-slate-300">
    <div class="mx-auto grid max-w-6xl grid-cols-2 gap-8 px-4 py-10 md:grid-cols-4">
        <div>
            <h3 class="text-base font-semibold text-white">Quick Links</h3>
            <ul class="mt-3 space-y-1">
                <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                <li><a href="{{ route('shop') }}" class="hover:text-white">Shop</a></li>
                <li><a href="{{ route('checkout') }}" class="hover:text-white">Cart</a></li>
                <li><a href="{{ route('story') }}" class="hover:text-white">Our Story</a></li>
                <li><a href="mailto:withbilgi@gmail.com" class="hover:text-white">Contact Us</a></li>
            </ul>
        </div>
        <div>
            <h3 class="text-base font-semibold text-white">Your Account</h3>
            <ul class="mt-3 space-y-1">
                @auth
                    <li><a href="{{ route('account') }}" class="hover:text-white">My Account</a></li>
                    <li><a href="{{ route('account') }}" class="hover:text-white">My Orders</a></li>
                @else
                    <li><a href="{{ route('login') }}" class="hover:text-white">Login</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white">Register</a></li>
                @endauth
                <li><a href="{{ route('checkout') }}" class="hover:text-white">Cart</a></li>
            </ul>
        </div>
        <div>
            <h3 class="text-base font-semibold text-white">Our Company</h3>
            <ul class="mt-3 space-y-1">
                <li><a href="{{ route('story') }}" class="hover:text-white">Our Story</a></li>
                <li><a href="mailto:withbilgi@gmail.com" class="hover:text-white">Contact Us</a></li>
            </ul>
        </div>
        <div>
            <h3 class="text-base font-semibold text-white">Contact Details</h3>
            <ul class="mt-3 space-y-1">
                <li>
                    <a href="https://wa.me/{{ config('social.whatsapp') }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 hover:text-white">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2Zm0 18.13h-.01a8.2 8.2 0 0 1-4.18-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.36c0-4.54 3.7-8.23 8.24-8.23 2.2 0 4.27.86 5.83 2.41a8.18 8.18 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24Zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.16.25-.64.81-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.01-.38.11-.51.11-.11.25-.29.37-.43.12-.14.16-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43-.14-.01-.31-.01-.48-.01a.92.92 0 0 0-.66.31c-.23.25-.87.85-.87 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.14-1.18-.06-.1-.22-.16-.47-.28Z"/>
                        </svg>
                        WhatsApp: +880 1577-656983
                    </a>
                </li>
                <li><a href="mailto:withbilgi@gmail.com" class="hover:text-white">Email: withbilgi@gmail.com</a></li>
            </ul>
        </div>
    </div>

    <div class="border-t border-slate-800">
        <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-4 py-4 sm:flex-row sm:gap-0">
            <p class="text-[12px]">© {{ date('Y') }} · WithBilgi. All Rights Reserved.</p>

            {{-- Social links --}}
            @php
                $facebook = config('social.facebook');
                $instagram = config('social.instagram');
                $whatsapp = config('social.whatsapp');
            @endphp
            @if ($facebook || $instagram || $whatsapp)
                <div class="flex items-center gap-3">
                    @if ($facebook)
                        <a href="{{ $facebook }}" target="_blank" rel="noopener" aria-label="Facebook"
                           class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-slate-300 transition-colors hover:bg-white hover:text-slate-900">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.9h2.54V9.85c0-2.51 1.49-3.9 3.78-3.9 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.9h-2.34V22c4.78-.79 8.44-4.94 8.44-9.94Z"/>
                            </svg>
                        </a>
                    @endif
                    @if ($instagram)
                        <a href="{{ $instagram }}" target="_blank" rel="noopener" aria-label="Instagram"
                           class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-slate-300 transition-colors hover:bg-white hover:text-slate-900">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 0 1-1.38-.9 3.7 3.7 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.17 15.58 2.16 15.2 2.16 12s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.42 2.17 8.8 2.16 12 2.16Zm0 1.62c-3.15 0-3.52.01-4.76.07-.95.04-1.46.2-1.8.34-.45.17-.78.38-1.12.72-.34.34-.55.67-.72 1.12-.13.34-.3.85-.34 1.8-.06 1.24-.07 1.61-.07 4.76s.01 3.52.07 4.76c.04.95.21 1.46.34 1.8.17.45.38.78.72 1.12.34.34.67.55 1.12.72.34.13.85.3 1.8.34 1.24.06 1.61.07 4.76.07s3.52-.01 4.76-.07c.95-.04 1.46-.21 1.8-.34.45-.17.78-.38 1.12-.72.34-.34.55-.67.72-1.12.13-.34.3-.85.34-1.8.06-1.24.07-1.61.07-4.76s-.01-3.52-.07-4.76c-.04-.95-.21-1.46-.34-1.8a3.02 3.02 0 0 0-.72-1.12 3.02 3.02 0 0 0-1.12-.72c-.34-.13-.85-.3-1.8-.34-1.24-.06-1.61-.07-4.76-.07Zm0 2.76a5.3 5.3 0 1 1 0 10.6 5.3 5.3 0 0 1 0-10.6Zm0 1.62a3.68 3.68 0 1 0 0 7.36 3.68 3.68 0 0 0 0-7.36Zm5.5-.66a1.24 1.24 0 1 1-2.48 0 1.24 1.24 0 0 1 2.48 0Z"/>
                            </svg>
                        </a>
                    @endif
                    @if ($whatsapp)
                        <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener" aria-label="WhatsApp"
                           class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-slate-300 transition-colors hover:bg-white hover:text-slate-900">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2Zm0 18.13h-.01a8.2 8.2 0 0 1-4.18-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.36c0-4.54 3.7-8.23 8.24-8.23 2.2 0 4.27.86 5.83 2.41a8.18 8.18 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24Zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.16.25-.64.81-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.01-.38.11-.51.11-.11.25-.29.37-.43.12-.14.16-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43-.14-.01-.31-.01-.48-.01a.92.92 0 0 0-.66.31c-.23.25-.87.85-.87 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.14-1.18-.06-.1-.22-.16-.47-.28Z"/>
                            </svg>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</footer>
