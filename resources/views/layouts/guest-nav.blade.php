<nav x-data="{ open: false, mega: null }" class="sticky top-0 z-40 transition bg-gradient-to-r from-[var(--brand-primary)] to-[var(--brand-primary-2)]">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-[var(--brand-text)]" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-stretch">
                    <div class="relative"
                         @mouseenter="mega='products'" @mouseleave="mega=null">
                        <button type="button" class="h-full inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-[var(--brand-text)] hover:text-black hover:border-white/40 focus:outline-none">
                        {{ __('Products') }}
                        </button>
                        <!-- Mega panel -->
                        <div x-show="mega==='products'" x-cloak
                             @mouseenter="mega='products'" @mouseleave="mega=null"
                             class="fixed left-0 right-0 top-16 w-full pt-3 z-50"
                             style="display: none;">
                            <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8">
                                <div class="rounded-2xl bg-white text-gray-800 shadow-xl ring-1 ring-black/10 p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                                        <div>
                                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Loans & Cards</div>
                                            <ul class="space-y-2 text-sm">
                                                <li><a href="{{ url('/products') }}" class="hover:text-[color:var(--brand-primary)]">Personal Loans</a></li>
                                                <li><a href="{{ url('/products') }}" class="hover:text-[color:var(--brand-primary)]">Business Loans</a></li>
                                                <li><a href="{{ url('/products') }}" class="hover:text-[color:var(--brand-primary)]">Credit Cards</a></li>
                                                <li><a href="{{ url('/products') }}" class="hover:text-[color:var(--brand-primary)]">Home Loans</a></li>
                                            </ul>
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Investments & Insurance</div>
                                            <ul class="space-y-2 text-sm">
                                                <li><a href="{{ url('/blog') }}" class="hover:text-[color:var(--brand-primary)]">Fixed Deposits</a></li>
                                                <li><a href="{{ url('/blog') }}" class="hover:text-[color:var(--brand-primary)]">Bonds</a></li>
                                                <li><a href="{{ url('/blog') }}" class="hover:text-[color:var(--brand-primary)]">Health Insurance</a></li>
                                                <li><a href="{{ url('/blog') }}" class="hover:text-[color:var(--brand-primary)]">Term Insurance</a></li>
                                            </ul>
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Tools & Calculators</div>
                                            <ul class="space-y-2 text-sm">
                                                <li><a href="{{ url('/compare') }}" class="hover:text-[color:var(--brand-primary)]">Compare Products</a></li>
                                                <li><a href="{{ url('/blog') }}" class="hover:text-[color:var(--brand-primary)]">EMI Calculators</a></li>
                                                <li><a href="{{ url('/faq') }}" class="hover:text-[color:var(--brand-primary)]">FAQs</a></li>
                                            </ul>
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Learn & Support</div>
                                            <ul class="space-y-2 text-sm">
                                                <li><a href="{{ route('blog.index') }}" class="hover:text-[color:var(--brand-primary)]">Blog</a></li>
                                                <li><a href="{{ url('/about') }}" class="hover:text-[color:var(--brand-primary)]">About Us</a></li>
                                                <li><a href="{{ url('/contact') }}" class="hover:text-[color:var(--brand-primary)]">Contact</a></li>
                                                <li><a href="{{ url('/privacy') }}" class="hover:text-[color:var(--brand-primary)]">Privacy</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <x-nav-link :href="url('/compare')" :active="request()->is('compare')" variant="brand">
                        {{ __('Compare') }}
                    </x-nav-link>
                    <x-nav-link :href="route('blog.index')" :active="request()->is('blog*')" variant="brand">
                        {{ __('Blog') }}
                    </x-nav-link>
                    <x-nav-link :href="url('/about')" :active="request()->is('about')" variant="brand">
                        {{ __('About Us') }}
                    </x-nav-link>
                    <x-nav-link :href="url('/contact')" :active="request()->is('contact')" variant="brand">
                        {{ __('Contact Us') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right actions -->
            <div class="hidden sm:flex items-center gap-4">
                <!-- Talk to Expert -->
                <div class="relative" x-data="{ openExpert: false }" @mouseenter="openExpert=true">
                    <button type="button" class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-sm font-medium text-[var(--brand-text)] hover:text-black hover:bg-white/10 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M2 5.75C2 4.784 2.784 4 3.75 4h2.086c.74 0 1.393.477 1.61 1.184l.741 2.407a1.75 1.75 0 0 1-.47 1.76l-1.016 1.016a14.753 14.753 0 0 0 6.772 6.772l1.016-1.016c.47-.47 1.17-.653 1.76-.47l2.407.742A1.75 1.75 0 0 1 20 18.164V20.25A1.75 1.75 0 0 1 18.25 22h-.5C9.268 22 2 14.732 2 6.25v-.5z"/></svg>
                        <span>{{ __('Talk to Expert') }}</span>
                    </button>
                    <!-- Popover -->
                    <div x-show="openExpert" x-cloak class="absolute right-0 top-full mt-2 w-[24rem] sm:w-[26rem] z-50" @mouseenter="openExpert=true" @mouseleave="openExpert=false" style="display: none; width: 26rem; max-width: 26rem;">
                        <div class="relative w-full rounded-2xl bg-white text-gray-800 shadow-xl ring-1 ring-black/10 p-5">
                            <div class="flex items-center gap-2 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[color:var(--brand-primary)]" viewBox="0 0 24 24" fill="currentColor"><path d="M2 5.75C2 4.784 2.784 4 3.75 4h2.086c.74 0 1.393.477 1.61 1.184l.741 2.407a1.75 1.75 0 0 1-.47 1.76l-1.016 1.016a14.753 14.753 0 0 0 6.772 6.772l1.016-1.016c.47-.47 1.17-.653 1.76-.47l2.407.742A1.75 1.75 0 0 1 20 18.164V20.25A1.75 1.75 0 0 1 18.25 22h-.5C9.268 22 2 14.732 2 6.25v-.5z"/></svg>
                                <h3 class="text-base font-semibold">Talk to Expert</h3>
                            </div>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Sales Enquiry</div>
                                    <div>Call Us: <a href="tel:18005703888" class="font-semibold text-[color:var(--brand-primary)] hover:underline">1800 570 3888</a></div>
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Service Helpline</div>
                                    <div>Call Us: <a href="tel:18002585616" class="font-semibold text-[color:var(--brand-primary)] hover:underline">1800 258 5616</a></div>
                                </div>
                                <p class="text-xs text-gray-600">Our advisors are available 7 days a week, <span class="font-semibold">9:30 am - 6:30 pm</span> to assist you with the best offers or help resolve any queries.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @guest
                    <a href="{{ route('oauth.redirect', ['provider' => 'google']) }}"
                       class="inline-flex items-center rounded-full bg-white/15 px-3 py-1.5 text-sm font-semibold text-[var(--brand-text)] hover:bg-white/25 focus:outline-none">
                        {{ __('Sign in') }}
                    </a>
                @endguest
                @auth
                    <button type="button"
                            class="inline-flex items-center rounded-full bg-white/15 px-3 py-1.5 text-sm font-semibold text-[var(--brand-text)] hover:bg-white/25 focus:outline-none"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </button>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center -me-2 sm:hidden">
                <button @click="open = ! open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-[var(--brand-text)] hover:text-black hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:text-black" aria-controls="mobile-menu" :aria-expanded="open.toString()">
                    <span class="sr-only">Open main menu</span>
                    <svg x-show="!open" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div class="sm:hidden" id="mobile-menu" x-show="open" x-cloak>
        <div class="pt-2 pb-3 space-y-1 bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white">
            <a href="{{ url('/') }}" class="block ps-3 pe-4 py-2 text-base font-medium hover:bg-white/10 {{ request()->is('/') ? 'bg-white/10 text-white' : '' }}">Home</a>
            <a href="{{ url('/products') }}" class="block ps-3 pe-4 py-2 text-base font-medium hover:bg-white/10 {{ request()->is('products*') ? 'bg-white/10 text-white' : '' }}">Products</a>
            <a href="{{ url('/compare') }}" class="block ps-3 pe-4 py-2 text-base font-medium hover:bg-white/10 {{ request()->is('compare') ? 'bg-white/10 text-white' : '' }}">Compare</a>
            <a href="{{ url('/about') }}" class="block ps-3 pe-4 py-2 text-base font-medium hover:bg-white/10 {{ request()->is('about') ? 'bg-white/10 text-white' : '' }}">About Us</a>
            <a href="{{ url('/contact') }}" class="block ps-3 pe-4 py-2 text-base font-medium hover:bg-white/10 {{ request()->is('contact') ? 'bg-white/10 text-white' : '' }}">Contact Us</a>
            <a href="{{ url('/faq') }}" class="block ps-3 pe-4 py-2 text-base font-medium hover:bg-white/10 {{ request()->is('faq') ? 'bg-white/10 text-white' : '' }}">FAQ</a>
            @auth
            <x-nav-link :href="route('logout')" :active="false" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block ps-3 pe-4 py-2 text-base font-medium text-white/90 hover:text-white">
                {{ __('Logout') }}
            </x-nav-link>
            @endauth
        </div>
    </div>
</nav>
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>
