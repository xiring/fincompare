<nav x-data="{ open: false, scrolled: false }" x-init="window.addEventListener('scroll', ()=>{ scrolled = window.scrollY > 4 })" :class="scrolled ? 'shadow-sm backdrop-blur bg-white/90' : 'bg-white'" class="border-b border-gray-100 sticky top-0 z-40 transition">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="url('/')" :active="request()->is('/')">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="url('/products')" :active="request()->is('products*')">
                        {{ __('Products') }}
                    </x-nav-link>
                    <x-nav-link :href="url('/compare')" :active="request()->is('compare')">
                        {{ __('Compare') }}
                    </x-nav-link>
                    <x-nav-link :href="url('/about')" :active="request()->is('about')">
                        {{ __('About Us') }}
                    </x-nav-link>
                    <x-nav-link :href="url('/contact')" :active="request()->is('contact')">
                        {{ __('Contact Us') }}
                    </x-nav-link>
                    <x-nav-link :href="url('/faq')" :active="request()->is('faq')">
                        {{ __('FAQ') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center -me-2 sm:hidden">
                <button @click="open = ! open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-700" aria-controls="mobile-menu" :aria-expanded="open.toString()">
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
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ url('/') }}" class="block ps-3 pe-4 py-2 text-base font-medium {{ request()->is('/') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">Home</a>
            <a href="{{ url('/products') }}" class="block ps-3 pe-4 py-2 text-base font-medium {{ request()->is('products*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">Products</a>
            <a href="{{ url('/compare') }}" class="block ps-3 pe-4 py-2 text-base font-medium {{ request()->is('compare') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">Compare</a>
            <a href="{{ url('/about') }}" class="block ps-3 pe-4 py-2 text-base font-medium {{ request()->is('about') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">About Us</a>
            <a href="{{ url('/contact') }}" class="block ps-3 pe-4 py-2 text-base font-medium {{ request()->is('contact') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">Contact Us</a>
            <a href="{{ url('/faq') }}" class="block ps-3 pe-4 py-2 text-base font-medium {{ request()->is('faq') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">FAQ</a>
        </div>
    </div>
</nav>
